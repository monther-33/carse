<?php

use App\Actions\Sales\CancelSalesInvoice;
use App\Actions\Sales\ReturnSalesItem;
use App\Enums\OwnershipStatus;
use App\Enums\VehicleStatus;
use App\Models\VehicleOwnerDue;
use App\Services\Ownership\OwnerPayouts;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
    $this->a = customer();
    $this->b = customer();
});

function owners(object $test): array
{
    return [['party_id' => $test->a->id, 'share' => '60'], ['party_id' => $test->b->id, 'share' => '40']];
}

it('credits the showroom commission and each owner on a consignment sale', function () {
    $ownership = receiveConsignment(['earning_mode' => 'percent', 'earning_percent' => '4', 'owners' => owners($this)]);
    $profit = ledgerProfit();

    $invoice = sell($ownership->vehicle, ['price' => '50000']);
    $item = $invoice->items->first();
    $payouts = app(OwnerPayouts::class);

    expect((string) baseBalance('43'))->toBe('-2000.000')            // the showroom's 4%
        ->and((string) baseBalance('24'))->toBe('-48000.000')        // owed to the owners
        ->and((string) baseBalance('41'))->toBe('0.000')             // not the showroom's sale
        ->and((string) baseBalance('51'))->toBe('0.000')             // nor its cost
        ->and((string) $payouts->balance($this->a->id))->toBe('28800.000')
        ->and((string) $payouts->balance($this->b->id))->toBe('19200.000')
        ->and($item->showroom_revenue)->toBe('2000.000')
        ->and((string) $item->profit())->toBe('2000.000')
        ->and($ownership->refresh()->status)->toBe(OwnershipStatus::Sold)
        ->and(VehicleOwnerDue::query()->open()->sum('amount'))->toEqual('48000.000')
        ->and((string) ledgerProfit()->minus($profit))->toBe('2000.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('lets the showroom bear a sale below the agreed net price', function () {
    $ownership = receiveConsignment(['earning_mode' => 'net_price', 'earning_amount' => '52000', 'owners' => owners($this)]);

    sell($ownership->vehicle, ['price' => '50000']);

    expect((string) baseBalance('43'))->toBe('2000.000')             // a debit: the showroom's loss
        ->and((string) baseBalance('24'))->toBe('-52000.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('splits a foreign-currency sale in dinars', function () {
    $ownership = receiveConsignment(['earning_mode' => 'fixed', 'earning_amount' => '3000', 'owners' => owners($this)]);

    sell($ownership->vehicle, ['currency_id' => usd()->id, 'rate' => '4.85', 'price' => '10000']);

    expect((string) baseBalance('43'))->toBe('-3000.000')
        ->and((string) baseBalance('24'))->toBe('-45500.000')        // 48,500 − 3,000
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('undoes the owners dues when the sale is cancelled', function () {
    $ownership = receiveConsignment(['owners' => owners($this)]);
    $invoice = sell($ownership->vehicle, ['price' => '50000']);

    app(CancelSalesInvoice::class)->handle($invoice, 'خطأ');

    expect((string) baseBalance('24'))->toBe('0.000')
        ->and((string) baseBalance('43'))->toBe('0.000')
        ->and(VehicleOwnerDue::query()->open()->count())->toBe(0)
        ->and($ownership->refresh()->status)->toBe(OwnershipStatus::Active)
        ->and($ownership->vehicle->refresh()->status)->toBe(VehicleStatus::Available);
});

it('undoes the owners dues when the car is returned', function () {
    $ownership = receiveConsignment(['owners' => owners($this)]);
    $invoice = sell($ownership->vehicle, ['price' => '50000']);

    app(ReturnSalesItem::class)->handle($invoice->items->first(), 'عيب');

    expect((string) baseBalance('24'))->toBe('0.000')
        ->and((string) baseBalance('43'))->toBe('0.000')
        ->and($ownership->refresh()->status)->toBe(OwnershipStatus::Active)
        ->and($ownership->vehicle->refresh()->status)->toBe(VehicleStatus::Returned)
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('holds back what the customer has not paid when owners are paid as collected', function () {
    $ownership = receiveConsignment(['payout' => 'on_collection', 'earning_mode' => 'fixed', 'earning_amount' => '2000', 'owners' => owners($this)]);
    $invoice = sell($ownership->vehicle, ['price' => '50000']);   // on credit, nothing paid
    $payouts = app(OwnerPayouts::class);

    expect((string) $payouts->available($this->a->id))->toBe('0.000');

    postVoucher([
        'type' => 'receipt', 'party_id' => $invoice->party_id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('13')->id, 'amount' => '25000',
        'reference_type' => $invoice->getMorphClass(), 'reference_id' => $invoice->id,
    ]);

    expect((string) $payouts->available($this->a->id))->toBe('14400.000')   // half of 28,800
        ->and((string) $payouts->available($this->b->id))->toBe('9600.000');
});
