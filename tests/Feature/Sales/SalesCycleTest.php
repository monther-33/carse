<?php

use App\Actions\Reservations\CreateReservation;
use App\Actions\Sales\PostSalesInvoice;
use App\Actions\Sales\ReturnSalesItem;
use App\Enums\CommissionStatus;
use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Commission;
use App\Models\SalesInvoice;
use App\Reports\TrialBalance;
use App\Support\Settings;

/*
| Phase 3 acceptance gate (spec section 9):
| purchase → expense → reservation → installment sale with trade-in → two installments
| collected → return, in ONE test, with the trial balance balanced and the profit right
| after every step.
*/
test('full cycle: purchase, expense, reservation, installment sale with trade-in, two collections, return', function () {
    $this->actingAs(userWithRole('admin'));
    app(Settings::class)->set(['sales.commission_type' => 'percent', 'sales.commission_value' => '1']);
    $balanced = fn () => expect((new TrialBalance)->isBalanced())->toBeTrue();

    // 1. Purchase car A for 40,000 on credit.
    $supplier = supplier();
    $carA = purchase([purchaseLine(['price' => '40000', 'vin' => 'CARA0000000000001'])], ['party_id' => $supplier->id])
        ->items->first()->vehicle;
    expect((string) $carA->total_cost)->toBe('40000.000')
        ->and(ledgerProfit()->isZero())->toBeTrue();
    $balanced();

    // 2. Preparation expense of 1,000 capitalised on A.
    postExpense($carA, '1000');
    expect((string) $carA->fresh()->total_cost)->toBe('41000.000')
        ->and((string) baseBalance('14'))->toBe('41000.000')
        ->and(ledgerProfit()->isZero())->toBeTrue();
    $balanced();

    // 3. Customer reserves A with a 2,000 cash deposit.
    $customer = customer();
    $reservation = app(CreateReservation::class)->handle([
        'vehicle_id' => $carA->id, 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'deposit' => '2000', 'expires_at' => today()->addDays(7)->toDateString(),
    ]);
    expect($carA->fresh()->status)->toBe(VehicleStatus::Reserved)
        ->and($reservation->voucher->status)->toBe(DocumentStatus::Posted)
        ->and((string) baseBalance('22'))->toBe('-2000.000')
        ->and(ledgerProfit()->isZero())->toBeTrue();
    $balanced();

    // 4. Installment sale: 50,000 − 500 discount = 49,500; trade-in car B worth 10,000;
    //    deposit 2,000; down payment 7,500 → 30,000 financed over 6 months (5,000 each).
    $sale = sell($carA, [
        'party_id' => $customer->id,
        'reservation_id' => $reservation->id,
        'price' => '50000',
        'discount' => '500',
        'payment_type' => 'installment',
        'payments' => [['cashbox_id' => cashbox('خزينة دينار')->id, 'amount' => '7500']],
        'trade_in' => purchaseLine(['vin' => 'CARB0000000000002', 'value' => '10000', 'entry_status' => 'in_preparation']),
        'installment' => [
            'down_payment' => '7500', 'months' => 6, 'start_date' => today()->addMonth()->toDateString(),
            'guarantor' => ['name' => 'كفيل التجربة', 'phone' => '0911111111', 'relation' => 'أخ'],
        ],
    ]);

    $item = $sale->items->first();
    $carB = $sale->tradeIn->vehicle;
    $installments = $sale->installmentPlan->installments;

    expect($sale->status)->toBe(DocumentStatus::Posted)
        ->and((string) $sale->total)->toBe('49500.000')
        ->and((string) $sale->deposit_applied)->toBe('2000.000')
        ->and((string) $sale->paid)->toBe('7500.000')
        ->and((string) $item->cost_snapshot)->toBe('41000.000')
        ->and((string) $item->commission)->toBe('495.000')
        ->and((string) $item->profit())->toBe('8005.000')                 // 49,500 − 41,000 − 495
        ->and((string) ledgerProfit())->toBe('8005.000')
        ->and($carA->fresh()->status)->toBe(VehicleStatus::Sold)
        ->and($carB->status)->toBe(VehicleStatus::InPreparation)
        ->and((string) $carB->total_cost)->toBe('10000.000')
        ->and($reservation->fresh()->status)->toBe(ReservationStatus::Converted)
        ->and($installments)->toHaveCount(6)
        ->and($installments->every(fn ($i) => (string) $i->amount === '5000.000'))->toBeTrue()
        ->and($sale->installmentPlan->guarantor->name)->toBe('كفيل التجربة')
        ->and((string) baseBalance('13'))->toBe('30000.000')              // customer owes the financed part
        ->and(baseBalance('22')->isZero())->toBeTrue()                    // deposit applied
        ->and((string) baseBalance('14'))->toBe('10000.000')              // only car B in stock
        ->and((string) baseBalance('23'))->toBe('-495.000');              // accrued commission
    $balanced();

    // 5. Collect two installments: 6,000 then 4,000 (oldest first: #1 paid, then #2 completed).
    $plan = $sale->installmentPlan;
    $collect = fn (string $amount) => postVoucher([
        'type' => 'receipt', 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('13')->id, 'amount' => $amount,
        'reference_type' => $plan->getMorphClass(), 'reference_id' => $plan->id,
    ]);

    $collect('6000');
    $installments = $plan->installments()->get();
    expect($installments[0]->status)->toBe(InstallmentStatus::Paid)
        ->and($installments[1]->status)->toBe(InstallmentStatus::Partial)
        ->and((string) $installments[1]->paid_amount)->toBe('1000.000');

    $collect('4000');
    $installments = $plan->installments()->get();
    expect($installments[1]->status)->toBe(InstallmentStatus::Paid)
        ->and($installments[2]->status)->toBe(InstallmentStatus::Pending)
        ->and((string) baseBalance('13'))->toBe('20000.000')
        ->and((string) ledgerProfit())->toBe('8005.000');
    $balanced();

    // 6. The customer returns car A.
    $return = app(ReturnSalesItem::class)->handle($item->fresh(), 'عيب في المحرك');

    expect($return->number)->toStartWith('SR-')
        ->and($carA->fresh()->status)->toBe(VehicleStatus::Returned)
        ->and((string) $carA->fresh()->total_cost)->toBe('41000.000')
        ->and((string) baseBalance('14'))->toBe('51000.000')              // A back in stock + B
        ->and(baseBalance('41')->isZero())->toBeTrue()
        ->and(baseBalance('51')->isZero())->toBeTrue()
        ->and(baseBalance('23')->isZero())->toBeTrue()                    // unpaid commission reversed
        ->and(ledgerProfit()->isZero())->toBeTrue()
        // Customer paid 2,000 + 7,500 + 10,000 and handed over a 10,000 car: we owe 29,500.
        ->and((string) baseBalance('13'))->toBe('-29500.000')
        ->and($plan->installments()->where('status', InstallmentStatus::Cancelled)->count())->toBe(4)
        ->and(Commission::query()->first()->status)->toBe(CommissionStatus::Cancelled);
    $balanced();
});

test('the same vehicle can never be sold twice', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');

    $first = saveSale($vehicle, ['price' => '35000']);
    $second = saveSale($vehicle, ['price' => '36000']);

    app(PostSalesInvoice::class)->handle($first);

    expect(fn () => app(PostSalesInvoice::class)->handle($second))
        ->toThrow(BusinessRuleException::class);

    expect($second->fresh()->status)->toBe(DocumentStatus::Draft)
        ->and(SalesInvoice::query()->posted()->count())->toBe(1)
        ->and(fn () => saveSale($vehicle, ['price' => '37000']))->toThrow(BusinessRuleException::class);
});
