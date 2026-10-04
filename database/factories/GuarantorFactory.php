<?php

namespace Database\Factories;

use App\Models\Guarantor;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guarantor>
 */
class GuarantorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'party_id' => Party::factory(),
            'name' => fake()->name(),
            'phone' => fake()->numerify('09########'),
            'national_id' => fake()->numerify('1##########'),
            'relation' => 'أخ',
        ];
    }
}
