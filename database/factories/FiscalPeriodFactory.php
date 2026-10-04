<?php

namespace Database\Factories;

use App\Models\FiscalPeriod;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FiscalPeriod>
 */
class FiscalPeriodFactory extends Factory
{
    public function definition(): array
    {
        $start = CarbonImmutable::now()->startOfMonth();

        return [
            'name' => $start->format('Y-m'),
            'start_date' => $start->toDateString(),
            'end_date' => $start->endOfMonth()->toDateString(),
            'is_closed' => false,
        ];
    }

    public function forMonth(int $year, int $month): static
    {
        $start = CarbonImmutable::create($year, $month, 1);

        return $this->state(fn () => [
            'name' => $start->format('Y-m'),
            'start_date' => $start->toDateString(),
            'end_date' => $start->endOfMonth()->toDateString(),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn () => ['is_closed' => true, 'closed_at' => now()]);
    }
}
