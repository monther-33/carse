<?php

namespace App\Services\Numbering;

use App\Enums\SequenceType;
use App\Models\Sequence;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * Gap-free, duplicate-free document numbers per (type, year).
 *
 * Must be called inside the same DB transaction that saves the document: the
 * row lock serialises concurrent callers, and a rollback also rolls back the
 * counter, so no number is ever burned.
 */
class SequenceService
{
    public function next(SequenceType $type, ?CarbonInterface $date = null): string
    {
        if (DB::transactionLevel() === 0) {
            throw new LogicException('SequenceService::next() must run inside a database transaction.');
        }

        $year = ($date ?? now())->year;

        $sequence = $this->lockRow($type, $year);

        if ($sequence === null) {
            // First number of the year. INSERT IGNORE makes concurrent first-callers safe.
            DB::table('sequences')->insertOrIgnore([
                'type' => $type->value,
                'prefix' => $type->defaultPrefix(),
                'year' => $year,
                'next_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sequence = $this->lockRow($type, $year) ?? throw new LogicException('Sequence row could not be created.');
        }

        $number = $sequence->next_number;

        $sequence->next_number = $number + 1;
        $sequence->save();

        return $this->format($sequence->prefix, $year, $number);
    }

    public function format(string $prefix, int $year, int $number): string
    {
        return sprintf('%s-%d-%06d', $prefix, $year, $number);
    }

    private function lockRow(SequenceType $type, int $year): ?Sequence
    {
        return Sequence::query()
            ->where('type', $type->value)
            ->where('year', $year)
            ->lockForUpdate()
            ->first();
    }
}
