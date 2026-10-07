<?php

namespace App\Actions\Ownership;

use App\Enums\OwnershipStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Vehicle;
use App\Models\VehicleOwnership;
use App\Services\Vehicles\VehicleStateMachine;
use Illuminate\Support\Facades\DB;

/**
 * Hands a consignment vehicle back to its owners unsold. No journal entry (the car was never
 * the showroom's); expenses charged to the owners stay on their balance to be settled.
 * Only while the car is in stock and not reserved.
 */
class ReturnToOwner
{
    public function __construct(private readonly VehicleStateMachine $states) {}

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

            $this->states->transition($vehicle, VehicleStatus::ReturnedToSupplier, $ownership, $reason);
            $vehicle->forceFill(['ownership_id' => null])->save();

            $ownership->update([
                'status' => OwnershipStatus::Returned,
                'ended_at' => now(),
                'end_reason' => $reason,
            ]);

            return $ownership;
        });
    }
}
