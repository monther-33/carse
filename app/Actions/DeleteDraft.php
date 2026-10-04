<?php

namespace App\Actions;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Deletes a draft document (expense, voucher...). Drafts have no number and no entry.
 */
class DeleteDraft
{
    use ManagesDocumentLifecycle;

    public function handle(Model $document): void
    {
        DB::transaction(function () use ($document) {
            $this->lockInStatus($document, DocumentStatus::Draft)->delete();
        });
    }
}
