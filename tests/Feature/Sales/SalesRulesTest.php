<?php

use App\Actions\Reservations\CreateReservation;
use App\Actions\Reservations\EndReservation;
use App\Actions\Sales\CancelSalesInvoice;
use App\Actions\Sales\DeliverSalesInvoice;
use App\Actions\Sales\PayCommissions;
use App\Actions\Sales\PostSalesInvoice;
use App\Actions\Vouchers\CancelVoucher;
use App\Actions\Vouchers\PostVoucher;
use App\Enums\CommissionStatus;
use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Commission;
use App\Models\Voucher;
use App\Reports\PartyStatement;
use App\Services\Currency\ExchangeRateService;
use App\Support\Settings;

test('a cash sale is an invoice on receivables plus a receipt, leaving nothing owed', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');
    $customer = customer();

    $sale = sell($vehicle, [
        'party_id' => $customer->id, 'price' => '36000', 'payment_type' => 'cash',
        'payments' => [['cashbox_id' => cashbox('خزينة دينار')->id, 'amount' => '36000']],
    ]);

    $voucher = Voucher::query()->whereMorphedTo('reference', $sale)->firstOrFail();
    expect($voucher->status)->toBe(DocumentStatus::Posted)
        ->and(baseBalance('13')->isZero())->toBeTrue()
        ->and((string) baseBalance('41'))->toBe('-36000.000')
        ->and((string) baseBalance('51'))->toBe('30000.000')
        ->and(ledgerIsBalanced())->toBeTrue();

    // The customer statement shows both the sale and its settlement.
    $lines = (new PartyStatement($customer, [account('13')->id]))->lines();
    expect($lines)->toHaveCount(2);
});

test('payment rules per payment type', function (string $type, array $payments, ?string $error) {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('20000');
    $box = cashbox('خزينة دينار')->id;

    $save = fn () => saveSale($vehicle, [
        'price' => '25000', 'payment_type' => $type,
        'payments' => array_map(fn ($a) => ['cashbox_id' => $box, 'amount' => $a], $payments),
    ]);

    $error === null ? expect($save())->not->toBeNull() : expect($save)->toThrow(BusinessRuleException::class, __($error, ['due' => '25,000.000']));
})->with([
    'cash in full' => ['cash', ['25000'], null],
    'cash short' => ['cash', ['20000'], 'sales.errors.full_payment'],
    'credit nothing paid' => ['credit', [], null],
    'credit partial' => ['credit', ['5000'], null],
    'credit overpaid' => ['credit', ['26000'], 'sales.errors.overpaid'],
    'mixed two payments' => ['mixed', ['10000', '5000'], null],
]);

test('discount above the user limit and price below the minimum need override permissions', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('20000');
    $vehicle->update(['min_price' => '24000']);

    $seller = userWithRole('sales');
    $seller->update(['max_discount' => '500']);
    $this->actingAs($seller);

    expect(fn () => saveSale($vehicle, ['price' => '26000', 'discount' => '600']))
        ->toThrow(BusinessRuleException::class, __('sales.errors.discount_limit', ['limit' => '500.000']));
    expect(fn () => saveSale($vehicle, ['price' => '24300', 'discount' => '400']))
        ->toThrow(BusinessRuleException::class);
    expect(saveSale($vehicle, ['price' => '26000', 'discount' => '500'])->status)->toBe(DocumentStatus::Draft);

    $this->actingAs(userWithRole('admin'));
    expect(saveSale($vehicle, ['price' => '23000', 'discount' => '1000'])->status)->toBe(DocumentStatus::Draft);
});

test('a reservation can be cancelled: the car is free again and the deposit stays as customer credit', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');
    $customer = customer();

    $reservation = app(CreateReservation::class)->handle([
        'vehicle_id' => $vehicle->id, 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'deposit' => '1500', 'expires_at' => today()->addDays(3)->toDateString(),
    ]);

    expect(fn () => app(CreateReservation::class)->handle([
        'vehicle_id' => $vehicle->id, 'party_id' => customer()->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'deposit' => '100', 'expires_at' => today()->addDays(3)->toDateString(),
    ]))->toThrow(BusinessRuleException::class);

    app(EndReservation::class)->cancel($reservation, 'العميل عدل عن الشراء');

    expect($reservation->fresh()->status)->toBe(ReservationStatus::Cancelled)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Available)
        ->and((string) baseBalance('22'))->toBe('-1500.000');

    // Refund the deposit.
    postVoucher(['type' => 'payment', 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('22')->id, 'amount' => '1500']);
    expect(baseBalance('22')->isZero())->toBeTrue()
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('overdue reservations expire and release the car', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle();
    $reservation = app(CreateReservation::class)->handle([
        'vehicle_id' => $vehicle->id, 'party_id' => customer()->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'deposit' => '500', 'expires_at' => today()->toDateString(),
    ]);
    $reservation->update(['expires_at' => today()->subDay()]);

    $this->artisan('reservations:expire')->assertSuccessful();

    expect($reservation->fresh()->status)->toBe(ReservationStatus::Expired)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Available);
});

test('a salesperson reservation waits for the deposit to be approved before it counts', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');
    $customer = customer();

    $this->actingAs(userWithRole('sales'));
    $reservation = app(CreateReservation::class)->handle([
        'vehicle_id' => $vehicle->id, 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'deposit' => '1000', 'expires_at' => today()->addWeek()->toDateString(),
    ]);
    expect($reservation->voucher->status)->toBe(DocumentStatus::Draft)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Reserved);

    // Not usable on the sale until the deposit receipt is approved.
    $data = ['party_id' => $customer->id, 'reservation_id' => $reservation->id, 'price' => '35000', 'deposit_applied' => '1000'];
    expect(fn () => saveSale($vehicle, $data))->toThrow(BusinessRuleException::class);

    $this->actingAs(userWithRole('accountant'));
    app(PostVoucher::class)->handle($reservation->voucher);
    $sale = app(PostSalesInvoice::class)->handle(saveSale($vehicle, $data));

    expect((string) $sale->deposit_applied)->toBe('1000.000')
        ->and((string) baseBalance('13'))->toBe('34000.000')
        ->and(baseBalance('22')->isZero())->toBeTrue();
});

test('cancelling a sale restores stock, returns the trade-in and cancels its receipts', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');
    $sale = sell($vehicle, [
        'price' => '40000', 'payment_type' => 'mixed',
        'payments' => [['cashbox_id' => cashbox('خزينة دينار')->id, 'amount' => '20000']],
        'trade_in' => purchaseLine(['vin' => 'TRADE000000000001', 'value' => '8000', 'entry_status' => 'available']),
    ]);
    $tradeIn = $sale->tradeIn->vehicle;

    app(CancelSalesInvoice::class)->handle($sale, 'خطأ في البيع');

    expect($sale->fresh()->status)->toBe(DocumentStatus::Cancelled)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Available)
        ->and($vehicle->fresh()->sale_invoice_id)->toBeNull()
        ->and($tradeIn->fresh()->status)->toBe(VehicleStatus::ReturnedToSupplier)
        ->and(Voucher::query()->whereMorphedTo('reference', $sale)->first()->status)->toBe(DocumentStatus::Cancelled)
        ->and((string) baseBalance('14'))->toBe('30000.000')
        ->and(baseBalance('13')->isZero())->toBeTrue()
        ->and(ledgerProfit()->isZero())->toBeTrue()
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('a sale with collected installments cannot be cancelled, only returned', function () {
    $this->actingAs(userWithRole('admin'));
    $customer = customer();
    $sale = sell(purchaseVehicle('20000'), [
        'party_id' => $customer->id, 'price' => '24000', 'payment_type' => 'installment',
        'installment' => ['down_payment' => '0', 'months' => 4, 'guarantor' => ['name' => 'كفيل']],
    ]);
    postVoucher(['type' => 'receipt', 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('13')->id, 'amount' => '6000',
        'reference_type' => 'installment_plan', 'reference_id' => $sale->installmentPlan->id]);

    app(CancelSalesInvoice::class)->handle($sale, 'x');
})->throws(BusinessRuleException::class);

test('cancelling a collection voucher puts the amount back on the installments', function () {
    $this->actingAs(userWithRole('admin'));
    $customer = customer();
    $sale = sell(purchaseVehicle('20000'), [
        'party_id' => $customer->id, 'price' => '24000', 'payment_type' => 'installment',
        'installment' => ['down_payment' => '0', 'months' => 4, 'guarantor' => ['name' => 'كفيل']],
    ]);
    $plan = $sale->installmentPlan;
    $data = ['type' => 'receipt', 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('13')->id, 'reference_type' => 'installment_plan', 'reference_id' => $plan->id];

    $voucher = postVoucher($data + ['amount' => '9000']);
    expect($plan->installments()->where('status', InstallmentStatus::Paid)->count())->toBe(1);

    app(CancelVoucher::class)->handle($voucher, 'شيك مرتجع');
    expect($plan->installments()->where('status', InstallmentStatus::Pending)->count())->toBe(4)
        ->and((string) baseBalance('13'))->toBe('24000.000');

    // More than what is left on the plan is refused.
    expect(fn () => postVoucher($data + ['amount' => '24000.001']))->toThrow(BusinessRuleException::class);
});

test('accrued commissions are paid with one voucher', function () {
    $this->actingAs(userWithRole('admin'));
    app(Settings::class)->set(['sales.commission_type' => 'fixed', 'sales.commission_value' => '250']);
    $seller = userWithRole('sales');

    sell(purchaseVehicle('10000'), ['price' => '12000', 'salesperson_id' => $seller->id]);
    sell(purchaseVehicle('10000'), ['price' => '13000', 'salesperson_id' => $seller->id]);

    $ids = Commission::query()->where('user_id', $seller->id)->pluck('id')->all();
    expect($ids)->toHaveCount(2)
        ->and((string) baseBalance('23'))->toBe('-500.000');

    app(PayCommissions::class)->handle($seller, $ids, cashbox('خزينة دينار'));

    expect(Commission::query()->where('status', CommissionStatus::Paid)->count())->toBe(2)
        ->and(baseBalance('23')->isZero())->toBeTrue()
        ->and((string) baseBalance('65'))->toBe('500.000');
});

test('a dollar sale collected at a different rate books the currency difference', function () {
    $this->actingAs(userWithRole('admin'));
    app(ExchangeRateService::class)->setRate(usd(), today(), '5.000000');
    $customer = customer();

    // Car costs 40,000 LYD, sold for 10,000 USD at 5.00 = 50,000 LYD, on credit.
    sell(purchaseVehicle('40000'), ['party_id' => $customer->id, 'price' => '10000', 'currency_id' => usd()->id, 'rate' => '5']);
    expect((string) ledgerProfit())->toBe('10000.000');

    // Collected when the dollar is worth 5.10: 1,000 LYD exchange gain.
    postVoucher(['type' => 'receipt', 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دولار')->id,
        'account_id' => account('13')->id, 'amount' => '10000', 'rate' => '5.1']);

    expect(baseBalance('13')->isZero())->toBeTrue()
        ->and((string) baseBalance('71'))->toBe('-1000.000')
        ->and((string) ledgerProfit())->toBe('11000.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('a posted sale is delivered once', function () {
    $this->actingAs(userWithRole('admin'));
    $sale = sell(purchaseVehicle());

    app(DeliverSalesInvoice::class)->handle($sale);
    expect($sale->fresh()->delivered_at)->not->toBeNull()
        ->and(fn () => app(DeliverSalesInvoice::class)->handle($sale))->toThrow(BusinessRuleException::class);
});

test('expenses on a sold car go straight to cost of sales without touching the frozen cost', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('20000');
    $sale = sell($vehicle, ['price' => '25000']);

    postExpense($vehicle->fresh(), '300');

    expect((string) $sale->items->first()->fresh()->cost_snapshot)->toBe('20000.000')
        ->and((string) $vehicle->fresh()->total_cost)->toBe('20000.000')
        ->and((string) baseBalance('51'))->toBe('20300.000')
        ->and(baseBalance('14')->isZero())->toBeTrue();
});
