<?php

use App\Actions\Sales\DeliverSalesInvoice;
use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\VehicleStatus;
use App\Livewire\Commissions\Index as CommissionsIndex;
use App\Livewire\Installments\Index as InstallmentsIndex;
use App\Livewire\Reservations\Index as ReservationsIndex;
use App\Livewire\Sales\Form as SalesForm;
use App\Livewire\Sales\Index as SalesIndex;
use App\Livewire\Sales\Show as SalesShow;
use App\Models\Commission;
use App\Models\Reservation;
use App\Models\SalesInvoice;
use App\Support\Settings;
use Livewire\Livewire;

test('a salesperson sells from the quick screen and an accountant approves', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');
    $vehicle->update(['asking_price' => '36000', 'min_price' => '34000']);
    $customer = customer();

    $seller = userWithRole('sales');
    $this->actingAs($seller);

    Livewire::test(SalesForm::class)
        ->set('party_id', $customer->id)
        ->set('pickVehicleId', $vehicle->id)
        ->assertSet('items.0.price', '36000.000')
        ->set('payments.0.cashbox_id', cashbox('خزينة دينار')->id)
        ->call('payInFull')
        ->assertSet('payments.0.amount', '36000.000')
        ->assertSee('36,000.000')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $sale = SalesInvoice::query()->latest('id')->firstOrFail();
    expect($sale->status)->toBe(DocumentStatus::Draft)
        ->and($sale->salesperson_id)->toBe($seller->id);

    Livewire::test(SalesShow::class, ['invoice' => $sale])->call('approve')->assertForbidden();

    $this->actingAs(userWithRole('accountant'));
    Livewire::test(SalesShow::class, ['invoice' => $sale])->call('approve')->assertHasNoErrors();

    expect($sale->fresh()->status)->toBe(DocumentStatus::Posted)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Sold);
});

test('a price below the minimum shows as a form error for a salesperson', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');
    $vehicle->update(['min_price' => '34000']);

    $this->actingAs(userWithRole('sales'));
    Livewire::test(SalesForm::class)
        ->set('party_id', customer()->id)
        ->set('pickVehicleId', $vehicle->id)
        ->set('items.0.price', '33000')
        ->set('payment_type', 'credit')
        ->call('save')
        ->assertHasErrors('document');

    expect(SalesInvoice::query()->count())->toBe(0);
});

test('an installment sale with a new guarantor and a schedule preview', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('20000');

    Livewire::test(SalesForm::class)
        ->set('party_id', customer()->id)
        ->set('pickVehicleId', $vehicle->id)
        ->set('items.0.price', '24000')
        ->set('payment_type', 'installment')
        ->set('payments.0.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('payments.0.amount', '6000')
        ->set('months', 6)
        ->assertSee('3,000.000')                  // 18,000 / 6
        ->set('guarantor.name', 'سالم الكفيل')
        ->call('save')
        ->assertHasNoErrors();

    $sale = SalesInvoice::query()->latest('id')->with('installmentPlan.guarantor')->firstOrFail();
    expect($sale->installmentPlan->months)->toBe(6)
        ->and((string) $sale->installmentPlan->down_payment)->toBe('6000.000')
        ->and($sale->installmentPlan->guarantor->name)->toBe('سالم الكفيل');
});

test('sales staff only see their own invoices', function () {
    $this->actingAs(userWithRole('admin'));
    $mine = userWithRole('sales');
    $other = userWithRole('sales');
    $saleOfMine = saveSale(purchaseVehicle(), ['salesperson_id' => $mine->id]);
    $saleOfOther = saveSale(purchaseVehicle(), ['salesperson_id' => $other->id]);

    $this->actingAs($mine);
    Livewire::test(SalesIndex::class)
        ->assertSee($saleOfMine->displayNumber())
        ->assertDontSee($saleOfOther->displayNumber());

    $this->get(route('sales.show', $saleOfOther))->assertForbidden();
    $this->get(route('sales.show', $saleOfMine))->assertOk();
});

test('reserve from the screen, then sell the reservation', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('25000');
    $customer = customer();

    Livewire::test(ReservationsIndex::class)
        ->call('create')
        ->set('form.vehicle_id', $vehicle->id)
        ->set('form.party_id', $customer->id)
        ->set('form.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('form.deposit', '1000')
        ->call('save')
        ->assertHasNoErrors();

    $reservation = Reservation::query()->latest('id')->firstOrFail();
    expect($vehicle->fresh()->status)->toBe(VehicleStatus::Reserved);

    Livewire::withQueryParams(['reservation' => $reservation->id])
        ->test(SalesForm::class)
        ->assertSet('party_id', $customer->id)
        ->assertSet('reservation_id', $reservation->id)
        ->assertSet('items.0.vehicle_id', $vehicle->id)
        ->set('items.0.price', '30000')
        ->set('payment_type', 'credit')
        ->assertSee('29,000.000')                 // due after the 1,000 deposit
        ->call('save')
        ->assertHasNoErrors();
});

test('installments are collected from the due list', function () {
    $this->actingAs(userWithRole('admin'));
    $sale = sell(purchaseVehicle('20000'), [
        'price' => '24000', 'payment_type' => 'installment',
        'installment' => ['down_payment' => '0', 'months' => 4, 'start_date' => today()->subMonths(2)->toDateString(), 'guarantor' => ['name' => 'كفيل']],
    ]);
    $first = $sale->installmentPlan->installments->first();

    Livewire::test(InstallmentsIndex::class)
        ->assertSee($sale->number)
        ->call('openCollect', $first->id)
        ->assertSet('form.amount', '6000.000')
        ->set('form.cashbox_id', cashbox('خزينة دينار')->id)
        ->call('collect')
        ->assertHasNoErrors();

    expect($first->fresh()->status)->toBe(InstallmentStatus::Paid)
        ->and((string) baseBalance('13'))->toBe('18000.000');
});

test('commissions are paid from the commissions screen', function () {
    $this->actingAs(userWithRole('admin'));
    app(Settings::class)->set(['sales.commission_type' => 'fixed', 'sales.commission_value' => '150']);
    $seller = userWithRole('sales');
    sell(purchaseVehicle(), ['salesperson_id' => $seller->id]);
    $commission = Commission::query()->firstOrFail();

    Livewire::test(CommissionsIndex::class)
        ->set('userId', $seller->id)
        ->set('selected', [$commission->id])
        ->set('cashbox_id', cashbox('خزينة دينار')->id)
        ->call('pay')
        ->assertHasNoErrors();

    expect($commission->fresh()->status->value)->toBe('paid');

    // The salesperson sees their own commission but cannot pay.
    $this->actingAs($seller);
    Livewire::test(CommissionsIndex::class)->set('status', '')->assertSee('150.000')->call('pay')->assertForbidden();
});

test('sales documents, vouchers and statements print as PDF', function () {
    $this->actingAs(userWithRole('admin'));
    $customer = customer();
    $sale = sell(purchaseVehicle('20000'), [
        'party_id' => $customer->id, 'price' => '24000', 'payment_type' => 'installment',
        'payments' => [['cashbox_id' => cashbox('خزينة دينار')->id, 'amount' => '4000']],
        'installment' => ['down_payment' => '4000', 'months' => 4, 'guarantor' => ['name' => 'كفيل']],
    ]);
    app(DeliverSalesInvoice::class)->handle($sale);
    $voucher = $sale->payments->first()->voucher;
    $draft = saveSale(purchaseVehicle());

    $urls = [
        route('print.sales', [$sale, 'invoice']),
        route('print.sales', [$sale, 'contract']),
        route('print.sales', [$sale, 'delivery']),
        route('print.sales', [$sale, 'schedule']),
        route('print.sales', [$draft, 'quotation']),
        route('print.voucher', $voucher),
        route('print.statement', $customer),
    ];

    foreach ($urls as $url) {
        $response = $this->get($url);
        $response->assertOk()->assertHeader('Content-Type', 'application/pdf');
        expect(substr($response->getContent(), 0, 4))->toBe('%PDF');
    }

    // A posted invoice has no quotation; a draft has no invoice.
    $this->get(route('print.sales', [$sale, 'quotation']))->assertNotFound();
    $this->get(route('print.sales', [$draft, 'invoice']))->assertNotFound();

    // Another salesperson cannot print it.
    $this->actingAs(userWithRole('sales'))->get(route('print.sales', [$sale, 'invoice']))->assertForbidden();
});

test('phase 3 screens are enforced per role', function (string $url, array $allowed) {
    foreach (['admin', 'accountant', 'cashier', 'sales', 'purchasing'] as $role) {
        $response = $this->actingAs(userWithRole($role))->get($url);
        in_array($role, $allowed, true) ? $response->assertOk() : $response->assertForbidden();
    }
})->with([
    'sales' => ['/sales', ['admin', 'accountant', 'sales']],
    'sale create' => ['/sales/create', ['admin', 'accountant', 'sales']],
    'reservations' => ['/reservations', ['admin', 'sales']],
    'installments' => ['/installments', ['admin', 'accountant', 'cashier']],
    'commissions' => ['/commissions', ['admin', 'accountant', 'sales']],
]);
