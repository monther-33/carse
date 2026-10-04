<?php

namespace Database\Factories;

use App\Enums\PartyType;
use App\Models\Branch;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Party>
 */
class PartyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => fn () => Branch::query()->value('id') ?? Branch::factory(),
            'type' => PartyType::Customer,
            'name' => fake()->name(),
            'phone' => fake()->numerify('09########'),
            'national_id' => fake()->numerify('1##########'),
            'address' => fake()->city(),
            'credit_limit' => '0',
            'is_active' => true,
        ];
    }

    public function supplier(): static
    {
        return $this->state(fn () => ['type' => PartyType::Supplier, 'name' => 'شركة '.fake()->company()]);
    }
}
