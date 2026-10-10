<?php

use App\Actions\Purchases\SavePurchaseInvoice;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Livewire\Expenses\Index as ExpensesIndex;
use App\Livewire\Parties\Index as PartiesIndex;
use App\Livewire\Purchases\Form as PurchaseForm;
use App\Livewire\Purchases\Show as PurchaseShow;
use App\Livewire\Vehicles\Index as VehiclesIndex;
use App\Livewire\Vehicles\Show as VehicleShow;
use App\Livewire\Vouchers\Index as VouchersIndex;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Party;
use App\Models\PurchaseInvoice;
use App\Models\Voucher;
use App\Support\Settings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('a purchasing clerk creates a draft and an accountant approves it', function () {
    $supplier = supplier();
    $line = purchaseLine(['price' => '33000', 'vin' => 'WVWZZZ1KZ6W000001']);

    $this->actingAs(userWithRole('purchasing'));
    Livewire::test(PurchaseForm::class)
        ->set('party_id', $supplier->id)
        ->set('items.0', $line + ['color_id' => null, 'plate_no' => '', 'mileage' => null, 'trim' => '', 'origin' => '', 'location_id' => null, 'asking_price' => '38000', 'min_price' => '35000', 'fuel' => 'petrol', 'transmission' => 'automatic', 'notes' => null])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $invoice = PurchaseInvoice::query()->latest('id')->firstOrFail();
    expect($invoice->status)->toBe(DocumentStatus::Draft);

    // The clerk cannot approve.
    Livewire::test(PurchaseShow::class, ['invoice' => $invoice])->call('approve')->assertForbidden();

    $this->actingAs(userWithRole('accountant'));
    Livewire::test(PurchaseShow::class, ['invoice' => $invoice])->call('approve')->assertHasNoErrors();

    $vehicle = $invoice->fresh()->items()->firstOrFail()->vehicle;
    expect($invoice->fresh()->status)->toBe(DocumentStatus::Posted)
        ->and($vehicle->status)->toBe(VehicleStatus::Available)
        ->and((string) $vehicle->asking_price)->toBe('38000.000')
        ->and((string) $vehicle->min_price)->toBe('35000.000');
});

test('a draft is reopened in the form, edited and saved', function () {
    $this->actingAs(userWithRole('purchasing'));
    $draft = app(SavePurchaseInvoice::class)->handle([
        'date' => today()->toDateString(), 'party_id' => supplier()->id, 'source' => 'supplier',
        'currency_id' => lyd()->id, 'rate' => '1', 'discount' => '0', 'paid' => '0', 'cashbox_id' => null, 'notes' => null,
        'items' => [purchaseLine(['price' => '10000', 'fuel' => 'diesel'])],
    ]);

    Livewire::test(PurchaseForm::class, ['invoice' => $draft])
        ->assertSet('items.0.fuel', 'diesel')
        ->assertSet('items.0.price', '10000.000')
        ->set('items.0.price', '9500')
        ->set('discount', '500')
        ->call('save')
        ->assertHasNoErrors();

    $draft->refresh();
    expect((string) $draft->total)->toBe('9000.000')
        ->and($draft->items()->count())->toBe(1);
});

test('with approval separation disabled, saving posts immediately', function () {
    app(Settings::class)->set(['documents.require_approval' => false]);
    $this->actingAs(userWithRole('admin'));

    Livewire::test(PurchaseForm::class)
        ->set('party_id', supplier()->id)
        ->set('items.0.vin', 'JTDBR32E000000002')
        ->set('items.0.brand_id', purchaseLine()['brand_id'])
        ->set('items.0.model_id', purchaseLine()['model_id'])
        ->set('items.0.price', '15000')
        ->call('save')
        ->assertHasNoErrors();

    expect(PurchaseInvoice::query()->latest('id')->first()->status)->toBe(DocumentStatus::Posted);
});

test('cancelling a purchase from the screen needs a reason and the admin role', function () {
    $this->actingAs(userWithRole('admin'));
    $invoice = purchase([purchaseLine()]);

    $this->actingAs(userWithRole('accountant'));
    Livewire::test(PurchaseShow::class, ['invoice' => $invoice])->call('openCancel')->assertForbidden();

    $this->actingAs(userWithRole('admin'));
    Livewire::test(PurchaseShow::class, ['invoice' => $invoice])
        ->call('openCancel')
        ->call('cancel')->assertHasErrors('reason')
        ->set('reason', 'خطأ إدخال')->call('cancel')->assertHasNoErrors();

    expect($invoice->fresh()->status)->toBe(DocumentStatus::Cancelled);
});

test('business-rule refusals show as form errors, not error pages', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle();
    postExpense($vehicle, '300');

    Livewire::test(PurchaseShow::class, ['invoice' => $vehicle->purchaseInvoice])
        ->call('openCancel')
        ->set('reason', 'x')
        ->call('cancel')
        ->assertHasErrors('reason');

    expect($vehicle->purchaseInvoice->fresh()->status)->toBe(DocumentStatus::Posted);
});

test('vehicle cost is hidden from roles without view_cost', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('47321.5');

    $this->actingAs(userWithRole('sales'));
    Livewire::test(VehiclesIndex::class)->assertSee($vehicle->vin)->assertDontSee('47,321.500');
    Livewire::test(VehicleShow::class, ['vehicle' => $vehicle])->assertDontSee('47,321.500')->call('edit')->assertForbidden();

    $this->actingAs(userWithRole('purchasing'));
    Livewire::test(VehiclesIndex::class)->assertSee('47,321.500');
});

test('manual status changes from the vehicle card', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('10000', 'in_customs');

    Livewire::test(VehicleShow::class, ['vehicle' => $vehicle])
        ->call('moveTo', 'in_preparation')->assertHasNoErrors()
        ->call('moveTo', 'sold')->assertHasErrors('status');

    expect($vehicle->fresh()->status)->toBe(VehicleStatus::InPreparation);
});

test('a treasurer only sees vouchers and expenses of their cashbox', function () {
    $this->actingAs(userWithRole('admin'));
    postExpense(null, '111', cashbox('خزينة دينار'));
    postExpense(null, '222', cashbox('حساب مصرفي رئيسي'));

    $cashier = userWithRole('cashier');
    $cashier->cashboxes()->attach(cashbox('خزينة دينار'));
    $this->actingAs($cashier);

    Livewire::test(ExpensesIndex::class)->assertSee('111.000')->assertDontSee('222.000');

    // Cannot use a cashbox outside their assignment.
    Livewire::test(VouchersIndex::class)
        ->call('create', 'payment')
        ->set('form.purpose', 'other')
        ->set('form.account_id', account('66')->id)
        ->set('form.cashbox_id', cashbox('حساب مصرفي رئيسي')->id)
        ->set('form.amount', '50')
        ->set('form.description', 'x')
        ->call('save')
        ->assertHasErrors('form.cashbox_id');

    // Can create a draft on their own cashbox, but not approve it.
    Livewire::test(VouchersIndex::class)
        ->call('create', 'payment')
        ->set('form.purpose', 'other')
        ->set('form.account_id', account('66')->id)
        ->set('form.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('form.amount', '50')
        ->set('form.description', 'نثريات')
        ->call('save')
        ->assertHasNoErrors();

    $voucher = Voucher::query()->latest('id')->firstOrFail();
    expect($voucher->status)->toBe(DocumentStatus::Draft);
    Livewire::test(VouchersIndex::class)->call('approve', $voucher->id)->assertForbidden();
});

test('a supplier payment voucher from the screen settles the supplier', function () {
    $this->actingAs(userWithRole('accountant'));
    $supplier = supplier();
    $invoice = purchase([purchaseLine(['price' => '12000'])], ['party_id' => $supplier->id]);

    Livewire::test(VouchersIndex::class)
        ->call('create', 'payment')
        ->set('form.party_id', $supplier->id)
        ->set('form.reference_id', $invoice->id)
        ->set('form.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('form.amount', '12000')
        ->set('form.description', 'سداد')
        ->call('save')
        ->assertHasNoErrors();

    $voucher = Voucher::query()->latest('id')->firstOrFail();
    expect($voucher->reference_id)->toBe($invoice->id);

    Livewire::test(VouchersIndex::class)->call('approve', $voucher->id)->assertHasNoErrors();

    expect(baseBalance('21')->isZero())->toBeTrue()
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('expenses are capitalised from the screen with a receipt image', function () {
    $this->actingAs(userWithRole('accountant'));
    $vehicle = purchaseVehicle('20000');

    Livewire::test(ExpensesIndex::class)
        ->call('create')
        ->set('form.category_id', ExpenseCategory::query()->first()->id)
        ->set('form.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('form.amount', '850')
        ->set('form.vehicle_id', $vehicle->id)
        ->set('form.description', 'تلميع')
        ->set('receipt', UploadedFile::fake()->image('r.jpg'))
        ->call('save')
        ->assertHasNoErrors();

    $expense = Expense::query()->latest('id')->firstOrFail();
    expect($expense->getFirstMedia('receipt'))->not->toBeNull();

    Livewire::test(ExpensesIndex::class)->call('approve', $expense->id)->assertHasNoErrors();
    expect((string) $vehicle->fresh()->total_cost)->toBe('20850.000');
});

test('parties are created with an ID card and protected from deletion once used', function () {
    Storage::fake('public');
    $this->actingAs(userWithRole('accountant'));

    Livewire::test(PartiesIndex::class)
        ->call('create')
        ->set('form.name', 'محمد علي')
        ->set('form.phone', '0912345678')
        ->set('idCard', UploadedFile::fake()->image('id.jpg'))
        ->call('save')
        ->assertHasNoErrors();

    $party = Party::query()->where('name', 'محمد علي')->firstOrFail();
    expect($party->getFirstMedia('id_card'))->not->toBeNull()
        ->and(auth()->user()->can('delete', $party))->toBeTrue();

    purchase([purchaseLine()], ['party_id' => $party->id]);
    expect(auth()->user()->can('delete', $party->fresh()))->toBeFalse();
});

test('phase 2 screens render for the admin and are forbidden to others', function (string $url, array $allowed) {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle();
    $party = $vehicle->purchaseInvoice->party;
    // Links carry each record's code, not its id (HasOpaqueRouteKey).
    $url = str_replace(['{vehicle}', '{invoice}', '{party}'], [$vehicle->getRouteKey(), $vehicle->purchaseInvoice->getRouteKey(), $party->getRouteKey()], $url);

    foreach (['admin', 'accountant', 'cashier', 'sales', 'purchasing'] as $role) {
        $response = $this->actingAs(userWithRole($role))->get($url);
        in_array($role, $allowed, true) ? $response->assertOk() : $response->assertForbidden();
    }
})->with([
    'vehicles' => ['/vehicles', ['admin', 'accountant', 'cashier', 'sales', 'purchasing']],
    'vehicle card' => ['/vehicles/{vehicle}', ['admin', 'accountant', 'cashier', 'sales', 'purchasing']],
    'purchases' => ['/purchases', ['admin', 'accountant', 'purchasing']],
    'purchase create' => ['/purchases/create', ['admin', 'accountant', 'purchasing']],
    'purchase show' => ['/purchases/{invoice}', ['admin', 'accountant', 'purchasing']],
    'parties' => ['/parties', ['admin', 'accountant', 'cashier', 'sales', 'purchasing']],
    'statement' => ['/parties/{party}/statement', ['admin', 'accountant', 'cashier', 'purchasing']],
    'expenses' => ['/expenses', ['admin', 'accountant', 'cashier']],
    'expense categories' => ['/expense-categories', ['admin', 'accountant']],
    'vouchers' => ['/vouchers', ['admin', 'accountant', 'cashier']],
]);
