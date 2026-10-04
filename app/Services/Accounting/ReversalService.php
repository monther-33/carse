<?php

namespace App\Services\Accounting;

use App\Enums\DocumentStatus;
use App\Exceptions\Accounting\AlreadyReversedException;
use App\Exceptions\Accounting\InvalidJournalLineException;
use App\Models\JournalEntry;
use App\Support\Money;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a posted entry by posting its exact mirror image (debits <-> credits),
 * linked both ways: original.reversed_by_id and reversal.reverses_id.
 */
class ReversalService
{
    public function __construct(private readonly PostingService $posting) {}

    public function reverse(JournalEntry $entry, ?CarbonInterface $date = null, ?string $description = null): JournalEntry
    {
        return DB::transaction(function () use ($entry, $date, $description) {
            $original = JournalEntry::query()->with('lines')->lockForUpdate()->findOrFail($entry->getKey());

            if ($original->isReversed() || $original->status === DocumentStatus::Cancelled) {
                throw new AlreadyReversedException($original->number);
            }

            if ($original->reverses_id !== null) {
                throw new InvalidJournalLineException(__('accounting.errors.cannot_reverse_reversal'));
            }

            $builder = JournalBuilder::make(
                $date ?? now(),
                $description ?? __('accounting.reversal_of', ['number' => $original->number]),
            )
                ->branch($original->branch_id)
                ->reversing($original)
                ->source($original->source_type !== null ? $original->source()->first() : null);

            foreach ($original->lines as $line) {
                $isDebit = Money::of($line->credit)->isPositive();
                $method = $isDebit ? 'debit' : 'credit';

                $builder->{$method}(
                    account: $line->account_id,
                    amount: $isDebit ? $line->credit : $line->debit,
                    currency: $line->currency_id,
                    rate: $line->rate,
                    partyId: $line->party_id,
                    vehicleId: $line->vehicle_id,
                    memo: $line->memo,
                );
            }

            $reversal = $this->posting->post($builder);
            $this->posting->markReversed($original, $reversal);

            $entry->setRawAttributes($original->getAttributes(), true);

            return $reversal;
        });
    }
}
