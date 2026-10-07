<?php

namespace App\Actions\Ownership;

use App\Enums\AccountRole;
use App\Enums\OwnershipStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Vehicle;
use App\Models\VehicleOwnership;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Hands a consignment vehicle back to its owners unsold. The car was never the showroom's, so
 * there is no entry, except for expenses the showroom bore and capitalised on it: they become
 * a cost of the showroom (Dr cost of vehicles sold / Cr the stock account). Expenses charged
 * to the owners stay on their balance, to be settled with a voucher.
 * Only while the car is in stock and not reserved.
 */
class ReturnToOwner
{
    public function __construct(
        private readonly VehicleStateMachine $states,
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
    ) {}

    public function handle(VehicleOwnership $ownership, string $reason): VehicleOwnership
    {
        return DB::transaction(function () use ($ownership, $reason) {
            $ownership = VehicleOwnership::query()->lockForUpdate()->findOrFail($ownership->id);
            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($ownership->vehicle_id);

            if (! $ownership->isConsignment() || $ownership->status !== OwnershipStatus::Active || $vehicle->ownership_id !== $ownership->id) {
                throw BusinessRuleException::make('ownership.errors.not_active');
            }
            if (! $vehicle->status->canTransitionTo(VehicleStatus::ReturnedToSupplier)) {
                throw BusinessRuleException::make('ownership.errors.cannot_return', ['status' => $vehicle->status->label()]);
            }

            $cost = Money::of($vehicle->total_cost);
            if ($cost->isPositive()) {
                $this->posting->post(
                    JournalBuilder::make(now(), __('ownership.return_entry', ['number' => $ownership->number, 'vin' => $vehicle->vin]))
                        ->source($ownership)
                        ->branch($vehicle->branch_id)
                        ->debit($this->accounts->idFor(AccountRole::CostOfSales), $cost, vehicleId: $vehicle->id, memo: $vehicle->vin)
                        ->credit($this->accounts->idFor($vehicle->status->stockRole()), $cost, vehicleId: $vehicle->id, memo: $vehicle->vin)
                );
            }

            $this->states->transition($vehicle, VehicleStatus::ReturnedToSupplier, $ownership, $reason);
            $vehicle->forceFill(['ownership_id' => null, 'total_cost' => '0', 'extra_cost' => '0'])->save();

            $ownership->update([
                'status' => OwnershipStatus::Returned,
                'ended_at' => now(),
                'end_reason' => $reason,
            ]);

            return $ownership;
        });
    }
}
