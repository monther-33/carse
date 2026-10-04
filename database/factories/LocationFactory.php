<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => fn () => Branch::query()->value('id') ?? Branch::factory(),
            'name' => fake()->unique()->word(),
        ];
    }
}
