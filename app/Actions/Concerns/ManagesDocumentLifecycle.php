<?php

namespace App\Actions\Concerns;

use App\Enums\DocumentStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Status bookkeeping shared by the document Actions. Callers run inside DB::transaction().
 */
trait ManagesDocumentLifecycle
{
    /**
     * @template T of Model
     *
     * @param  T  $document
     * @return T
     */
    protected function lockInStatus(Model $document, DocumentStatus $expected): Model
    {
        $locked = $document->newQuery()->lockForUpdate()->findOrFail($document->getKey());

        if ($locked->getAttribute('status') !== $expected) {
            throw BusinessRuleException::make('documents.errors.wrong_status', [
                'status' => $locked->getAttribute('status')->label(),
            ]);
        }

        return $locked;
    }

    protected function markPosted(Model $document, string $number, ?JournalEntry $entry): void
    {
        $document->forceFill([
            'number' => $number,
            'status' => DocumentStatus::Posted,
            'journal_entry_id' => $entry?->id,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ])->save();
    }

    protected function markCancelled(Model $document, string $reason): void
    {
        $document->forceFill([
            'status' => DocumentStatus::Cancelled,
            'cancelled_by' => Auth::id(),
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ])->save();
    }

    protected function assertDraft(Model $document): void
    {
        if ($document->getAttribute('status') !== DocumentStatus::Draft) {
            throw BusinessRuleException::make('documents.errors.not_draft');
        }
    }
}
