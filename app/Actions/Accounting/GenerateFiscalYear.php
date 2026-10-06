<?php

namespace App\Actions\Accounting;

use App\Models\FiscalPeriod;
use App\Services\Numbering\SequenceService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Creates the twelve monthly periods of a year. Existing months are left untouched.
 */
class GenerateFiscalYear
{
    public function __construct(private readonly SequenceService $sequences) {}

    public function handle(int $year): int
    {
        return DB::transaction(function () use ($year) {
            $created = 0;

            for ($month = 1; $month <= 12; $month++) {
                $start = CarbonImmutable::create($year, $month, 1);

                $period = FiscalPeriod::query()->firstOrCreate(
                    ['start_date' => $start->toDateString()],
                    ['name' => $start->format('Y-m'), 'end_date' => $start->endOfMonth()->toDateString()],
                );

                $created += $period->wasRecentlyCreated ? 1 : 0;
            }

            // The year's document counters, created now rather than inside concurrent postings.
            $this->sequences->ensureYear($year);

            return $created;
        });
    }
}
