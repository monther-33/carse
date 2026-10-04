<?php

namespace App\Services\Installments;

use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Equal monthly installments; the last one absorbs the rounding remainder so the
 * schedule always adds up exactly to the financed amount. No interest is added
 * (the agreed price already reflects the instalment terms).
 */
class InstallmentScheduleService
{
    /**
     * @return list<array{sequence: int, due_date: CarbonImmutable, amount: BigDecimal}>
     */
    public function build(BigDecimal $financed, int $months, CarbonInterface $startDate): array
    {
        if ($months < 1 || ! $financed->isPositive()) {
            return [];
        }

        $amounts = Money::allocate($financed, array_fill(0, $months, '1'));
        $start = CarbonImmutable::parse($startDate);
        $schedule = [];

        foreach ($amounts as $i => $amount) {
            $schedule[] = [
                'sequence' => $i + 1,
                'due_date' => $start->addMonthsNoOverflow($i),
                'amount' => $amount,
            ];
        }

        return $schedule;
    }
}
