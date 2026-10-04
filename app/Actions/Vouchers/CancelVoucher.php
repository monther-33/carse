<?php

namespace App\Actions\Vouchers;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Models\JournalEntry;
use App\Models\Voucher;
use App\Services\Accounting\ReversalService;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a posted voucher with a full reversing entry.
 */
class CancelVoucher
{
    use ManagesDocumentLifecycle;

    public function __construct(private readonly ReversalService $reversal) {}

    public function handle(Voucher $voucher, string $reason): Voucher
    {
        return DB::transaction(function () use ($voucher, $reason) {
            $voucher = $this->lockInStatus($voucher, DocumentStatus::Posted);

            $this->reversal->reverse(
                JournalEntry::query()->findOrFail($voucher->journal_entry_id),
                description: __('documents.cancellation_of', ['number' => $voucher->number]),
            );
            $this->markCancelled($voucher, $reason);

            return $voucher;
        });
    }
}
