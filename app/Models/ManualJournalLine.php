<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $manual_journal_id
 * @property int $account_id
 * @property int|null $party_id
 * @property string $debit
 * @property string $credit
 * @property int $currency_id
 * @property string $rate
 * @property string|null $memo
 */
class ManualJournalLine extends Model
{
    protected $fillable = ['manual_journal_id', 'account_id', 'party_id', 'debit', 'credit', 'currency_id', 'rate', 'memo'];

    protected function casts(): array
    {
        return [
            'debit' => 'decimal:3',
            'credit' => 'decimal:3',
            'rate' => 'decimal:6',
        ];
    }

    /** @return BelongsTo<Account, $this> */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /** @return BelongsTo<Party, $this> */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class)->withTrashed();
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }
}
