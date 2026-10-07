<?php

use App\Actions\Purchases\CancelPurchaseInvoice;
use App\Actions\Purchases\ReturnPurchaseItem;
use App\Enums\OwnershipKind;
use App\Enums\OwnershipStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\PurchaseInvoice;
use App\Services\Ownership\OwnerPayouts;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
    $this->a = customer();
    $this->b = customer();
});

/** 60,000 car: partner A 30%, partner B 20%, the showroom 50%. */
function partnershipPurchase(object $test): PurchaseInvoice
{
    return purchase([purchaseLine(['price' => '60000', 'partner_payout' => 'on_sale', 'partners' => [
        ['party_id' => $test->a->id, 'share' => '30'],
        ['party_id' => $test->b->id, 'share' => '20'],
    ]])]);
}

it('keeps only the showroom share of the cost on the car', function () {
    $invoice = partnershipPurchase($this);
    $vehicle = $invoice->items->first()->vehicle->refresh();
    $ownership = $vehicle->ownership()->with('owners')->firstOrFail();
    $payouts = app(OwnerPayouts::class);

    expect($ownership->kind)->toBe(OwnershipKind::Partnership)
        ->and($ownership->showroom_share)->toBe('50.0000')
        ->and($ownership->owners->pluck('contribution')->all())->toBe(['18000.000', '12000.000'])
        ->and($vehicle->total_cost)->toBe('30000.000')
        ->and((string) baseBalance('14'))->toBe('30000.000')
        ->and((string) $payouts->balance($this->a->id))->toBe('-18000.000')   // owes their contribution
        ->and((string) baseBalance('21'))->toBe('-60000.000')                 // the supplier is owed in full
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('splits the sale by shares', function () {
    $vehicle = partnershipPurchase($this)->items->first()->vehicle->refresh();

    $invoice = sell($vehicle, ['price' => '80000']);
    $payouts = app(OwnerPayouts::class);

    expect((string) baseBalance('41'))->toBe('-40000.000')
        ->and((string) baseBalance('51'))->toBe('30000.000')
        ->and((string) $payouts->balance($this->a->id))->toBe('6000.000')     // 24,000 − 18,000
        ->and((string) $payouts->balance($this->b->id))->toBe('4000.000')     // 16,000 − 12,000
        ->and((string) $invoice->items->first()->profit())->toBe('10000.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('gives the partners their contribution back when the purchase is cancelled', function () {
    $invoice = partnershipPurchase($this);
    $vehicle = $invoice->items->first()->vehicle;
    $ownership = $vehicle->refresh()->ownership;

    app(CancelPurchaseInvoice::class)->handle($invoice, 'خطأ');

    expect((string) baseBalance('24'))->toBe('0.000')
        ->and((string) baseBalance('14'))->toBe('0.000')
        ->and($ownership->refresh()->status)->toBe(OwnershipStatus::Closed)
        ->and($vehicle->refresh()->ownership_id)->toBeNull();
});

it('credits the contributions back on a purchase return', function () {
    $invoice = partnershipPurchase($this);

    app(ReturnPurchaseItem::class)->handle($invoice->items->first(), 'عيب');

    expect((string) baseBalance('24'))->toBe('0.000')
        ->and((string) baseBalance('14'))->toBe('0.000')
        ->and((string) baseBalance('21'))->toBe('0.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('leaves the showroom a share', function () {
    purchase([purchaseLine(['partners' => [['party_id' => $this->a->id, 'share' => '100']]])]);
})->throws(BusinessRuleException::class);

it('buys an own car back without its old partners', function () {
    $vehicle = partnershipPurchase($this)->items->first()->vehicle->refresh();
    sell($vehicle, ['price' => '80000']);

    $again = purchase([purchaseLine(['vin' => $vehicle->vin])])->items->first()->vehicle->refresh();

    expect($again->ownership_id)->toBeNull()
        ->and($again->total_cost)->toBe('50000.000');
});
