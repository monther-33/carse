<?php

namespace App\Actions\Journals;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Models\JournalEntry;
use App\Models\ManualJournal;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Accounting\ReversalService;
use App\Services\Numbering\SequenceService;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Posts a draft manual journal (through PostingService, which re-validates balance,
 * accounts and the fiscal period) and cancels a posted one with a reversing entry.
 */
class PostManualJournal
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly ReversalService $reversal,
        private readonly SequenceService $sequences,
    ) {}

    public function handle(ManualJournal $journal): ManualJournal
    {
        return DB::transaction(function () use ($journal) {
            $journal = $this->lockInStatus($journal, DocumentStatus::Draft);
            $journal->load('lines');

            $number = $this->sequences->next(SequenceType::ManualJournal, $journal->date);
            $builder = JournalBuilder::make($journal->date, $number.' — '.$journal->description)
                ->source($journal)
                ->branch($journal->branch_id);

            foreach ($journal->lines as $line) {
                $isDebit = Money::of($line->debit)->isPositive();
                $builder->{$isDebit ? 'debit' : 'credit'}(
                    $line->account_id,
                    $isDebit ? $line->debit : $line->credit,
                    $line->currency_id,
                    $line->rate,
                    partyId: $line->party_id,
                    memo: $line->memo,
                );
            }

            $entry = $this->posting->post($builder);
            $this->markPosted($journal, $number, $entry);

            return $journal;
        });
    }

    public function cancel(ManualJournal $journal, string $reason): ManualJournal
    {
        return DB::transaction(function () use ($journal, $reason) {
            $journal = $this->lockInStatus($journal, DocumentStatus::Posted);

            $this->reversal->reverse(
                JournalEntry::query()->findOrFail($journal->journal_entry_id),
                description: __('documents.cancellation_of', ['number' => $journal->number]),
            );
            $this->markCancelled($journal, $reason);

            return $journal;
        });
    }
}
