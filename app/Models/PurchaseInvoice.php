<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\PurchaseSource;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Models\Concerns\IsDocument;
use Database\Factories\PurchaseInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * Amounts are in the invoice currency at `rate` (LYD per unit).
 *
 * @property int $id
 * @property int $branch_id
 * @property string|null $number
 * @property Carbon $date
 * @property int $party_id
 * @property PurchaseSource $source
 * @property int $currency_id
 * @property string $rate
 * @property string $subtotal
 * @property string $discount
 * @property string $total
 * @property string $paid
 * @property int|null $cashbox_id
 * @property DocumentStatus $status
 * @property int|null $journal_entry_id
 * @property string|null $notes
 */
class PurchaseInvoice extends Model
{
    /** @use HasFactory<PurchaseInvoiceFactory> */
    use Auditable, HasFactory, HasUserstamps, IsDocument;

    protected $fillable = [
        'branch_id', 'number', 'date', 'party_id', 'source', 'currency_id', 'rate',
        'subtotal', 'discount', 'total', 'paid', 'cashbox_id', 'status', 'journal_entry_id', 'notes',
        'approved_by', 'approved_at', 'cancelled_by', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'source' => PurchaseSource::class,
            'rate' => 'decimal:6',
            'subtotal' => 'decimal:3',
            'discount' => 'decimal:3',
            'total' => 'decimal:3',
            'paid' => 'decimal:3',
        ];
    }

    /** @return HasMany<PurchaseInvoiceItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseInvoiceItem::class, 'invoice_id');
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

    /** @return BelongsTo<Cashbox, $this> */
    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }

    /** Vouchers referencing this invoice (the automatic payment, later supplier payments). */
    /** @return MorphMany<Voucher, $this> */
    public function vouchers(): MorphMany
    {
        return $this->morphMany(Voucher::class, 'reference');
    }

    /** @return MorphMany<ReturnDocument, $this> */
    public function returns(): MorphMany
    {
        return $this->morphMany(ReturnDocument::class, 'invoice');
    }
}
