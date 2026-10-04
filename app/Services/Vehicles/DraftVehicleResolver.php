<?php

namespace App\Services\Vehicles;

use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Vehicle;
use Closure;

/**
 * Finds or creates the vehicle described on a draft document (purchase line or trade-in).
 *
 * A new VIN creates a vehicle in status "pending" (not in stock). A known VIN is re-used
 * only when the car is out of stock (sold before, or returned to its seller), e.g. a car we
 * once sold coming back. A pending car belonging to another draft is refused.
 */
class DraftVehicleResolver
{
    /**
     * @param  array<string, mixed>  $data  vin + Vehicle::EDITABLE attributes
     * @param  Closure(Vehicle): bool  $isOnThisDraft  whether a pending vehicle already belongs to this document
     */
    public function resolve(array $data, int $branchId, Closure $isOnThisDraft): Vehicle
    {
        $vin = strtoupper(trim((string) $data['vin']));
        $vehicle = Vehicle::query()->lockForUpdate()->where('vin', $vin)->first();
        $attributes = array_intersect_key($data, array_flip(Vehicle::EDITABLE));

        if ($vehicle === null) {
            return Vehicle::query()->create($attributes + [
                'branch_id' => $branchId,
                'vin' => $vin,
                'status' => VehicleStatus::Pending,
            ]);
        }

        if ($vehicle->status === VehicleStatus::Pending && ! $isOnThisDraft($vehicle)) {
            throw BusinessRuleException::make('purchases.errors.vin_on_other_draft', ['vin' => $vin]);
        }
        if ($vehicle->status->isInStock()) {
            throw BusinessRuleException::make('purchases.errors.vin_in_stock', ['vin' => $vin]);
        }

        $vehicle->update($attributes);

        return $vehicle;
    }
}
