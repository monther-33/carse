<?php

namespace App\Actions;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Models\ManualJournal;
use App\Services\Trash\RecycleBin;
use Illuminate\Database\Eloquent\Model;

/**
 * Deletes a draft document (expense, voucher...). Drafts have no number and no entry.
 * A copy goes to the recycle bin.
 */
class DeleteDraft
{
    use ManagesDocumentLifecycle;

    public function __construct(private readonly RecycleBin $bin) {}

    public function handle(Model $document): void
    {
        $this->bin->keep($document, function () use ($document) {
            $draft = $this->lockInStatus($document, DocumentStatus::Draft);
            // A manual journal's lines go with it.
            if ($draft instanceof ManualJournal) {
                $draft->lines()->get()->each->delete();
            }
            $draft->delete();
        });
    }
}
