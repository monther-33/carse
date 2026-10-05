<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Models\Concerns\IsDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $branch_id
 * @property string|null $number
 * @property Carbon $date
 * @property string $description
 * @property DocumentStatus $status
 * @property int|null $journal_entry_id
 * @property string|null $notes
 */
class ManualJournal extends Model
{
    use Auditable, HasUserstamps, IsDocument;

    protected $fillable = [
        'branch_id', 'number', 'date', 'description', 'status', 'journal_entry_id', 'notes',
        'approved_by', 'approved_at', 'cancelled_by', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    /** @return HasMany<ManualJournalLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(ManualJournalLine::class)->orderBy('id');
    }
}
