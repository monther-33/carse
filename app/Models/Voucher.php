<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\VoucherType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Models\Concerns\IsDocument;
use Database\Factories\VoucherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Receipt, payment or cashbox transfer. Amount is in the cashbox currency.
 * account_id is the counter account (receivables, payables, deposits, accrued commissions...).
 *
 * @property int $id
 * @property int $branch_id
 * @property VoucherType $type
 * @property string|null $number
 * @property Carbon $date
 * @property int|null $party_id
 * @property int $cashbox_id
 * @property int|null $to_cashbox_id
 * @property int|null $account_id
 * @property string $amount
 * @property int $currency_id
 * @property string $rate
 * @property string $amount_base
 * @property string $description
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property DocumentStatus $status
 * @property int|null $journal_entry_id
 */
class Voucher extends Model
{
    /** @use HasFactory<VoucherFactory> */
    use Auditable, HasFactory, HasUserstamps, IsDocument;

    protected $fillable = [
        'branch_id', 'type', 'number', 'date', 'party_id', 'cashbox_id', 'to_cashbox_id', 'account_id',
        'amount', 'currency_id', 'rate', 'amount_base', 'description', 'reference_type', 'reference_id',
        'status', 'journal_entry_id', 'notes', 'approved_by', 'approved_at', 'cancelled_by', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'type' => VoucherType::class,
            'date' => 'date',
            'amount' => 'decimal:3',
            'rate' => 'decimal:6',
            'amount_base' => 'decimal:3',
        ];
    }

    /** @return BelongsTo<Party, $this> */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class)->withTrashed();
    }

    /** @return BelongsTo<Cashbox, $this> */
    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }

    /** @return BelongsTo<Cashbox, $this> */
    public function toCashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class, 'to_cashbox_id');
    }

    /** @return BelongsTo<Account, $this> */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    /** @return MorphTo<Model, $this> */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
