<?php

namespace App\Models;

use App\Exceptions\Accounting\JournalWriteNotAllowedException;
use App\Services\Accounting\JournalWriteGuard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Written exclusively by App\Services\Accounting\PostingService. Never updated or deleted.
 * Decimal columns are strings (decimal:N casts); use App\Support\Money for math.
 *
 * @property int $id
 * @property int $entry_id
 * @property int $line_no
 * @property int $account_id
 * @property int|null $party_id
 * @property int|null $vehicle_id
 * @property string $debit
 * @property string $credit
 * @property int $currency_id
 * @property string $rate
 * @property string $debit_base
 * @property string $credit_base
 * @property string|null $memo
 */
class JournalLine extends Model
{
    protected $fillable = [
        'entry_id', 'line_no', 'account_id', 'party_id', 'vehicle_id',
        'debit', 'credit', 'currency_id', 'rate', 'debit_base', 'credit_base', 'memo',
    ];

    protected function casts(): array
    {
        return [
            'debit' => 'decimal:3',
            'credit' => 'decimal:3',
            'rate' => 'decimal:6',
            'debit_base' => 'decimal:3',
            'credit_base' => 'decimal:3',
        ];
    }

    protected static function booted(): void
    {
        static::creating(fn () => JournalWriteGuard::assertAllowed());
        static::updating(fn () => throw new JournalWriteNotAllowedException);
        static::deleting(fn () => throw new JournalWriteNotAllowedException);
    }

    /** @return BelongsTo<JournalEntry, $this> */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'entry_id');
    }

    /** @return BelongsTo<Account, $this> */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
