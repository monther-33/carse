<?php

namespace App\Actions\Vouchers;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Models\JournalEntry;
use App\Models\Voucher;
use App\Services\Accounting\ReversalService;
use App\Services\Installments\InstallmentAllocator;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a posted voucher with a full reversing entry. An installment collection
 * is first taken back off the installments it was spread over.
 */
class CancelVoucher
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ReversalService $reversal,
        private readonly InstallmentAllocator $installments,
    ) {}

    public function handle(Voucher $voucher, string $reason): Voucher
    {
        return DB::transaction(function () use ($voucher, $reason) {
            $voucher = $this->lockInStatus($voucher, DocumentStatus::Posted);

            $this->installments->release($voucher);
            $this->reversal->reverse(
                JournalEntry::query()->findOrFail($voucher->journal_entry_id),
                description: __('documents.cancellation_of', ['number' => $voucher->number]),
            );
            $this->markCancelled($voucher, $reason);

            return $voucher;
        });
    }
}
