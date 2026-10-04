<?php

use App\Actions\Expenses\CancelExpense;
use App\Actions\Purchases\CancelPurchaseInvoice;
use App\Actions\Purchases\DeletePurchaseDraft;
use App\Actions\Purchases\PostPurchaseInvoice;
use App\Actions\Purchases\ReturnPurchaseItem;
use App\Actions\Purchases\SavePurchaseInvoice;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Enums\VoucherType;
use App\Exceptions\BusinessRuleException;
use App\Models\JournalEntry;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Reports\TrialBalance;
use App\Services\Accounting\PartyBalanceService;
use App\Services\Currency\ExchangeRateService;
use App\Services\Vehicles\VehicleStateMachine;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
});

test('a cash purchase puts the car in stock at cost and settles the supplier', function () {
    $supplier = supplier();
    $invoice = purchase([purchaseLine(['price' => '42000'])], [
        'party_id' => $supplier->id, 'paid' => '42000', 'cashbox_id' => cashbox('خزينة دينار')->id,
    ]);

    $vehicle = $invoice->items->first()->vehicle;
    expect($invoice->status)->toBe(DocumentStatus::Posted)
        ->and($invoice->number)->toStartWith('PI-')
        ->and($vehicle->status)->toBe(VehicleStatus::Available)
        ->and((string) $vehicle->purchase_cost)->toBe('42000.000')
        ->and((string) $vehicle->total_cost)->toBe('42000.000')
        ->and($vehicle->received_at->toDateString())->toBe(today()->toDateString());

    // Invoice on payables + automatic payment voucher at the invoice rate.
    $voucher = Voucher::query()->whereMorphedTo('reference', $invoice)->firstOrFail();
    expect($voucher->type)->toBe(VoucherType::Payment)
        ->and($voucher->status)->toBe(DocumentStatus::Posted)
        ->and((string) baseBalance('14'))->toBe('42000.000')
        ->and(baseBalance('21')->isZero())->toBeTrue()
        ->and((string) baseBalance(cashbox('خزينة دينار')->account))->toBe('-42000.000')
        ->and($vehicle->statusLogs()->first()->to_status)->toBe(VehicleStatus::Available)
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('a dollar purchase allocates the discount and books each car cost in dinars', function () {
    app(ExchangeRateService::class)->setRate(usd(), today(), '4.850000');
    $supplier = supplier();

    $invoice = purchase([
        purchaseLine(['price' => '10000', 'entry_status' => 'in_transit']),
        purchaseLine(['price' => '20000', 'entry_status' => 'available']),
    ], ['party_id' => $supplier->id, 'currency_id' => usd()->id, 'rate' => '4.85', 'discount' => '300']);

    [$first, $second] = $invoice->items->sortBy('id')->values()->all();

    // 300 USD discount split 1:2 → 100 / 200.
    expect((string) $first->net)->toBe('9900.000')
        ->and((string) $second->net)->toBe('19800.000')
        ->and((string) $first->cost_base)->toBe('48015.000')   // 9,900 × 4.85
        ->and((string) $second->cost_base)->toBe('96030.000')  // 19,800 × 4.85
        ->and((string) $first->vehicle->total_cost)->toBe('48015.000');

    // The car on its way sits in "in transit" (15), the other in inventory (14).
    expect((string) baseBalance('15'))->toBe('48015.000')
        ->and((string) baseBalance('14'))->toBe('96030.000')
        ->and((string) baseBalance('21'))->toBe('-144045.000');

    // Supplier owes is tracked in dollars.
    $usdOwed = app(PartyBalanceService::class)->balance($supplier->id, account('21')->id, usd()->id);
    expect((string) $usdOwed['amount'])->toBe('-29700.000');

    // Leaving customs moves the cost from 15 to 14 automatically.
    $machine = app(VehicleStateMachine::class);
    $car = $first->vehicle;
    $machine->transition($car, VehicleStatus::InCustoms, manual: true);
    expect(baseBalance('15')->isZero())->toBeFalse();
    $log = $machine->transition($car, VehicleStatus::InPreparation, manual: true);

    expect($log->journal_entry_id)->not->toBeNull()
        ->and(baseBalance('15')->isZero())->toBeTrue()
        ->and((string) baseBalance('14'))->toBe('144045.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('vehicle expenses are capitalised into total_cost and undone on cancellation', function () {
    $vehicle = purchaseVehicle('30000');

    $shipping = postExpense($vehicle, '1500');
    postExpense($vehicle, '750.250');
    $vehicle->refresh();

    expect((string) $vehicle->purchase_cost)->toBe('30000.000')
        ->and((string) $vehicle->extra_cost)->toBe('2250.250')
        ->and((string) $vehicle->total_cost)->toBe('32250.250')
        ->and((string) baseBalance('14'))->toBe('32250.250')
        ->and($vehicle->costs()->count())->toBe(2);

    app(CancelExpense::class)->handle($shipping, 'خطأ');
    $vehicle->refresh();

    expect((string) $vehicle->extra_cost)->toBe('750.250')
        ->and((string) $vehicle->total_cost)->toBe('30750.250')
        ->and((string) baseBalance('14'))->toBe('30750.250')
        ->and($shipping->fresh()->status)->toBe(DocumentStatus::Cancelled)
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('operating expenses go to their category account', function () {
    $expense = postExpense(null, '1200');

    $account = $expense->category->account;
    expect((string) baseBalance($account))->toBe('1200.000')
        ->and($expense->number)->toStartWith('EX-');
});

test('paying a dollar supplier at a different rate books the currency difference', function () {
    app(ExchangeRateService::class)->setRate(usd(), today(), '4.800000');
    $supplier = supplier();
    purchase([purchaseLine(['price' => '1000'])], ['party_id' => $supplier->id, 'currency_id' => usd()->id, 'rate' => '4.80']);

    // Pay the full 1,000 USD when the dollar is worth 4.90: 100 LYD loss.
    postVoucher([
        'type' => 'payment', 'party_id' => $supplier->id, 'cashbox_id' => cashbox('خزينة دولار')->id,
        'account_id' => account('21')->id, 'amount' => '1000', 'rate' => '4.90',
    ]);

    $owed = app(PartyBalanceService::class)->balance($supplier->id, account('21')->id, usd()->id);
    expect($owed['amount']->isZero())->toBeTrue()
        ->and($owed['base']->isZero())->toBeTrue()
        ->and((string) baseBalance('71'))->toBe('100.000')
        ->and((string) baseBalance(cashbox('خزينة دولار')->account))->toBe('-4900.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('cancelling a purchase reverses it, cancels its payment and takes the cars out of stock', function () {
    $invoice = purchase([purchaseLine(['price' => '20000'])], ['paid' => '5000', 'cashbox_id' => cashbox('خزينة دينار')->id]);
    $vehicle = $invoice->items->first()->vehicle;

    app(CancelPurchaseInvoice::class)->handle($invoice, 'فاتورة خاطئة');

    expect($invoice->fresh()->status)->toBe(DocumentStatus::Cancelled)
        ->and(Voucher::query()->whereMorphedTo('reference', $invoice)->first()->status)->toBe(DocumentStatus::Cancelled)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::ReturnedToSupplier)
        ->and(baseBalance('14')->isZero())->toBeTrue()
        ->and(baseBalance('21')->isZero())->toBeTrue()
        ->and(baseBalance(cashbox('خزينة دينار')->account)->isZero())->toBeTrue();

    // The same VIN can be bought again later.
    $again = purchase([purchaseLine(['vin' => $vehicle->vin, 'price' => '19000'])]);
    expect($again->items->first()->vehicle_id)->toBe($vehicle->id)
        ->and((string) $vehicle->fresh()->total_cost)->toBe('19000.000');
});

test('a purchase with capitalised costs cannot be cancelled', function () {
    $vehicle = purchaseVehicle('20000');
    postExpense($vehicle, '500');

    app(CancelPurchaseInvoice::class)->handle($vehicle->purchaseInvoice, 'x');
})->throws(BusinessRuleException::class);

test('returning one car of an invoice reduces payables and removes the car from stock', function () {
    $supplier = supplier();
    $invoice = purchase([purchaseLine(['price' => '10000']), purchaseLine(['price' => '15000'])], ['party_id' => $supplier->id]);
    $item = $invoice->items->sortBy('id')->first();

    $return = app(ReturnPurchaseItem::class)->handle($item, 'عيب مصنعي');

    expect($return->number)->toStartWith('PR-')
        ->and($item->fresh()->return_id)->toBe($return->id)
        ->and($item->vehicle->fresh()->status)->toBe(VehicleStatus::ReturnedToSupplier)
        ->and((string) baseBalance('14'))->toBe('15000.000')
        ->and((string) baseBalance('21'))->toBe('-15000.000')
        ->and(ledgerIsBalanced())->toBeTrue();

    expect(fn () => app(ReturnPurchaseItem::class)->handle($item->fresh(), 'مرة ثانية'))->toThrow(BusinessRuleException::class);
});

test('a VIN already in stock or on another draft is refused', function () {
    $vehicle = purchaseVehicle();

    expect(fn () => purchase([purchaseLine(['vin' => $vehicle->vin])]))->toThrow(BusinessRuleException::class);

    $line = purchaseLine();
    app(SavePurchaseInvoice::class)->handle([
        'date' => today()->toDateString(), 'party_id' => supplier()->id, 'source' => 'supplier',
        'currency_id' => lyd()->id, 'rate' => '1', 'discount' => '0', 'paid' => '0', 'cashbox_id' => null, 'notes' => null,
        'items' => [$line],
    ]);
    expect(fn () => purchase([$line]))->toThrow(BusinessRuleException::class);
});

test('a draft writes nothing to the ledger and deleting it removes its pending cars', function () {
    $draft = app(SavePurchaseInvoice::class)->handle([
        'date' => today()->toDateString(), 'party_id' => supplier()->id, 'source' => 'auction',
        'currency_id' => lyd()->id, 'rate' => '1', 'discount' => '0', 'paid' => '0', 'cashbox_id' => null, 'notes' => null,
        'items' => [purchaseLine()],
    ]);

    $vehicleId = $draft->items->first()->vehicle_id;
    expect($draft->number)->toBeNull()
        ->and(Vehicle::query()->find($vehicleId)->status)->toBe(VehicleStatus::Pending)
        ->and(JournalEntry::query()->count())->toBe(0);

    app(DeletePurchaseDraft::class)->handle($draft);

    expect(Vehicle::query()->find($vehicleId))->toBeNull();
});

test('a posted invoice cannot be posted twice', function () {
    $invoice = purchase([purchaseLine()]);

    app(PostPurchaseInvoice::class)->handle($invoice);
})->throws(BusinessRuleException::class);

test('manual status changes are limited to the steps before available', function () {
    $vehicle = purchaseVehicle('10000', 'in_transit');
    $machine = app(VehicleStateMachine::class);

    expect(fn () => $machine->transition($vehicle, VehicleStatus::Available, manual: true))->toThrow(BusinessRuleException::class);

    $machine->transition($vehicle, VehicleStatus::InCustoms, manual: true);
    $machine->transition($vehicle, VehicleStatus::InPreparation, manual: true);
    $machine->transition($vehicle, VehicleStatus::Available, manual: true);

    expect(fn () => $machine->transition($vehicle, VehicleStatus::Sold, manual: true))->toThrow(BusinessRuleException::class)
        ->and(fn () => $machine->transition($vehicle, VehicleStatus::Reserved, manual: true))->toThrow(BusinessRuleException::class)
        ->and($vehicle->statusLogs()->count())->toBe(4);
});

test('total_cost scenarios keep the trial balance balanced', function () {
    app(ExchangeRateService::class)->setRate(usd(), today(), '5.000000');

    $a = purchaseVehicle('25000');
    $b = purchase([purchaseLine(['price' => '8000', 'entry_status' => 'in_customs'])], ['currency_id' => usd()->id, 'rate' => '5'])
        ->items->first()->vehicle;

    postExpense($a, '1000');
    postExpense($b, '2500');            // capitalised while in customs → account 15
    postExpense($b, '100', cashbox('خزينة دولار')); // 100 USD at 5.00 = 500 LYD
    app(VehicleStateMachine::class)->transition($b, VehicleStatus::InPreparation, manual: true);

    expect((string) $a->fresh()->total_cost)->toBe('26000.000')
        ->and((string) $b->fresh()->total_cost)->toBe('43000.000') // 40,000 + 2,500 + 500
        ->and((string) baseBalance('14'))->toBe('69000.000')
        ->and(baseBalance('15')->isZero())->toBeTrue()
        ->and((new TrialBalance)->isBalanced())->toBeTrue();
});
