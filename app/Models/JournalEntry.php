<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Exceptions\Accounting\JournalWriteNotAllowedException;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Services\Accounting\JournalWriteGuard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Written exclusively by App\Services\Accounting\PostingService. Never deleted.
 *
 * @property int $id
 * @property int $branch_id
 * @property string $number
 * @property Carbon $date
 * @property string $description
 * @property string|null $source_type
 * @property int|null $source_id
 * @property DocumentStatus $status
 * @property int $period_id
 * @property int|null $reversed_by_id
 * @property int|null $reverses_id
 * @property string $total_base
 */
class JournalEntry extends Model
{
    use Auditable, HasUserstamps;

    protected $fillable = [
        'branch_id', 'number', 'date', 'description', 'source_type', 'source_id',
        'status', 'period_id', 'reversed_by_id', 'reverses_id', 'total_base',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => DocumentStatus::class,
            'total_base' => 'decimal:3',
        ];
    }

    protected static function booted(): void
    {
        static::saving(fn () => JournalWriteGuard::assertAllowed());
        static::deleting(fn () => throw new JournalWriteNotAllowedException);
    }

    /** @return HasMany<JournalLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class, 'entry_id')->orderBy('line_no');
    }

    /** @return MorphTo<Model, $this> */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<Branch, $this> */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** @return BelongsTo<FiscalPeriod, $this> */
    public function period(): BelongsTo
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    /** @return BelongsTo<JournalEntry, $this> */
    public function reversedBy(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'reversed_by_id');
    }

    /** @return BelongsTo<JournalEntry, $this> */
    public function reverses(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'reverses_id');
    }

    public function isReversed(): bool
    {
        return $this->reversed_by_id !== null;
    }
}
