<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExchangeRate>
 */
class ExchangeRateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'currency_id' => Currency::factory(),
            'rate' => fake()->numberBetween(4, 7).'.'.fake()->numerify('######'),
            'date' => fake()->unique()->dateTimeBetween('-1 year')->format('Y-m-d'),
        ];
    }
}
