<?php

use App\Actions\Ownership\ReceiveConsignment;
use App\Actions\Ownership\ReturnToOwner;
use App\Enums\OwnershipStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\JournalEntry;
use App\Models\VehicleOwnership;
use App\Services\Ownership\OwnershipSplit;
use App\Support\Money;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
});

/**
 * @param  array<string, mixed>  $overrides
 */
function receiveConsignment(array $overrides = []): VehicleOwnership
{
    return app(ReceiveConsignment::class)->handle($overrides + purchaseLine() + [
        'received_at' => today()->toDateString(),
        'earning_mode' => 'percent',
        'earning_percent' => '5',
        'payout' => 'on_sale',
        'owners' => [['party_id' => customer()->id, 'share' => '100']],
    ]);
}

it('receives a consignment car at zero cost without any journal entry', function () {
    $entries = JournalEntry::query()->count();

    $ownership = receiveConsignment();
    $vehicle = $ownership->vehicle->refresh();

    expect($ownership->number)->toStartWith('CI-'.today()->year.'-')
        ->and($ownership->status)->toBe(OwnershipStatus::Active)
        ->and($vehicle->status)->toBe(VehicleStatus::Available)
        ->and($vehicle->ownership_id)->toBe($ownership->id)
        ->and($vehicle->total_cost)->toBe('0.000')
        ->and(JournalEntry::query()->count())->toBe($entries);
});

it('requires the owners shares to add up to 100', function () {
    receiveConsignment(['owners' => [
        ['party_id' => customer()->id, 'share' => '60'],
        ['party_id' => customer()->id, 'share' => '30'],
    ]]);
})->throws(BusinessRuleException::class);

it('refuses a VIN that is already in stock', function () {
    $vehicle = purchaseVehicle();

    receiveConsignment(['vin' => $vehicle->vin]);
})->throws(BusinessRuleException::class);

it('hands the car back to its owner', function () {
    $ownership = receiveConsignment();

    app(ReturnToOwner::class)->handle($ownership, 'سحبها المالك');

    $vehicle = $ownership->vehicle->refresh();
    expect($ownership->refresh()->status)->toBe(OwnershipStatus::Returned)
        ->and($vehicle->status)->toBe(VehicleStatus::ReturnedToSupplier)
        ->and($vehicle->ownership_id)->toBeNull();
});

it('splits a consignment sale per the agreement', function (string $mode, array $terms, string $showroom) {
    $a = customer();
    $b = customer();
    $ownership = receiveConsignment(['earning_mode' => $mode, ...$terms, 'owners' => [
        ['party_id' => $a->id, 'share' => '60'],
        ['party_id' => $b->id, 'share' => '40'],
    ]]);

    $split = app(OwnershipSplit::class)->sale($ownership, Money::of('50000'));
    $owners = Money::of('50000')->minus(Money::of($showroom));

    expect((string) $split['showroom'])->toBe($showroom)
        ->and((string) $split['owners'][$a->id])->toBe((string) Money::allocate($owners, ['60', '40'])[0])
        ->and((string) Money::sum($split['owners']))->toBe((string) $owners);
})->with([
    'percent' => ['percent', ['earning_percent' => '3.5'], '1750.000'],
    'fixed' => ['fixed', ['earning_amount' => '1200'], '1200.000'],
    'net price' => ['net_price', ['earning_amount' => '47000'], '3000.000'],
    'sold below the net price' => ['net_price', ['earning_amount' => '52000'], '-2000.000'],
]);
