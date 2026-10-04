<?php

namespace Database\Factories;

use App\Enums\FuelType;
use App\Enums\Transmission;
use App\Enums\VehicleCondition;
use App\Enums\VehicleStatus;
use App\Models\Branch;
use App\Models\CarModel;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * A vehicle record without accounting. To put a car in stock with correct entries,
 * post a purchase invoice (see tests/Pest.php: purchaseVehicle()).
 *
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => fn () => Branch::query()->value('id') ?? Branch::factory(),
            'vin' => strtoupper(fake()->unique()->bothify('?##?#??#?#??#####')),
            'plate_no' => fake()->numerify('#-######'),
            'model_id' => fn () => CarModel::query()->value('id') ?? CarModel::factory(),
            'brand_id' => fn (array $attributes) => CarModel::query()->findOrFail($attributes['model_id'])->brand_id,
            'year' => fake()->numberBetween(2015, 2026),
            'mileage' => fake()->numberBetween(0, 150000),
            'fuel' => FuelType::Petrol,
            'transmission' => Transmission::Automatic,
            'condition' => VehicleCondition::Used,
            'status' => VehicleStatus::Pending,
        ];
    }
}
