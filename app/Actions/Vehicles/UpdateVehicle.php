<?php

namespace App\Actions\Vehicles;

use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

/**
 * Edits the descriptive card of a vehicle and its selling prices. Status and costs are
 * never edited here. The VIN can only change while the car is still a draft (pending).
 */
class UpdateVehicle
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Vehicle $vehicle, array $data): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $data) {
            $vehicle = Vehicle::query()->lockForUpdate()->findOrFail($vehicle->id);

            $attributes = array_intersect_key($data, array_flip(Vehicle::EDITABLE));

            if (isset($data['vin']) && strtoupper($data['vin']) !== $vehicle->vin) {
                if ($vehicle->status !== VehicleStatus::Pending) {
                    throw BusinessRuleException::make('vehicles.errors.vin_locked');
                }
                $attributes['vin'] = strtoupper($data['vin']);
            }

            $vehicle->update($attributes);

            return $vehicle;
        });
    }
}
