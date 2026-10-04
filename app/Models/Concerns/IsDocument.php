<?php

namespace App\Models\Concerns;

use App\Enums\DocumentStatus;
use App\Models\Branch;
use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Shared behaviour of approvable financial documents: draft → posted → cancelled.
 * Posted documents are never deleted; a draft may be deleted (it has no number yet).
 *
 * @mixin Model
 */
trait IsDocument
{
    public function initializeIsDocument(): void
    {
        $this->mergeCasts([
            'status' => DocumentStatus::class,
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ]);
    }

    public static function bootIsDocument(): void
    {
        static::deleting(function (Model $model) {
            if ($model->getAttribute('status') !== DocumentStatus::Draft) {
                throw new \LogicException('Only draft documents can be deleted.');
            }
        });
    }

    public function isDraft(): bool
    {
        return $this->status === DocumentStatus::Draft;
    }

    public function isPosted(): bool
    {
        return $this->status === DocumentStatus::Posted;
    }

    public function isCancelled(): bool
    {
        return $this->status === DocumentStatus::Cancelled;
    }

    /** Label for lists: the number once posted, otherwise "draft #id". */
    public function displayNumber(): string
    {
        return $this->number ?? __('app.draft_ref', ['id' => $this->getKey()]);
    }

    /** @return BelongsTo<JournalEntry, $this> */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /** @return BelongsTo<Branch, $this> */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** @return BelongsTo<User, $this> */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** @return BelongsTo<User, $this> */
    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /** @param Builder<static> $query */
    public function scopePosted(Builder $query): void
    {
        $query->where('status', DocumentStatus::Posted);
    }
}
