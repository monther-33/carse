<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\PaymentType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Models\Concerns\IsDocument;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * A draft sales invoice doubles as the printable price quotation.
 * Amounts are in the invoice currency at `rate`.
 *
 * @property int $id
 * @property int $branch_id
 * @property string|null $number
 * @property Carbon $date
 * @property int $party_id
 * @property int $salesperson_id
 * @property int|null $reservation_id
 * @property PaymentType $payment_type
 * @property int $currency_id
 * @property string $rate
 * @property string $subtotal
 * @property string $discount
 * @property string $trade_in_value
 * @property string $total
 * @property string $deposit_applied
 * @property string $paid
 * @property Carbon|null $delivered_at
 * @property int|null $delivered_by
 * @property DocumentStatus $status
 * @property int|null $journal_entry_id
 * @property string|null $notes
 * @property int|null $created_by
 */
class SalesInvoice extends Model
{
    use Auditable, HasUserstamps, IsDocument;

    protected $fillable = [
        'branch_id', 'number', 'date', 'party_id', 'salesperson_id', 'reservation_id', 'payment_type', 'currency_id', 'rate',
        'subtotal', 'discount', 'trade_in_value', 'total', 'deposit_applied', 'paid', 'delivered_at', 'delivered_by',
        'status', 'journal_entry_id', 'notes', 'approved_by', 'approved_at', 'cancelled_by', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'payment_type' => PaymentType::class,
            'rate' => 'decimal:6',
            'subtotal' => 'decimal:3',
            'discount' => 'decimal:3',
            'trade_in_value' => 'decimal:3',
            'total' => 'decimal:3',
            'deposit_applied' => 'decimal:3',
            'paid' => 'decimal:3',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * Sales staff only list their own invoices.
     *
     * @param  Builder<SalesInvoice>  $query
     */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if (! $user->can('sales.view_all')) {
            $query->where(fn ($q) => $q->where('salesperson_id', $user->id)->orWhere('created_by', $user->id));
        }
    }

    /** What the customer still owed right after the sale (before later collections). */
    public function balanceAfterSale(): BigDecimal
    {
        return Money::of($this->total)
            ->minus(Money::of($this->trade_in_value))
            ->minus(Money::of($this->deposit_applied))
            ->minus(Money::of($this->paid));
    }

    /** @return HasMany<SalesInvoiceItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(SalesInvoiceItem::class, 'invoice_id');
    }

    /** @return HasMany<SalesInvoicePayment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(SalesInvoicePayment::class, 'invoice_id');
    }

    /** @return HasOne<TradeIn, $this> */
    public function tradeIn(): HasOne
    {
        return $this->hasOne(TradeIn::class);
    }

    /** @return HasOne<InstallmentPlan, $this> */
    public function installmentPlan(): HasOne
    {
        return $this->hasOne(InstallmentPlan::class);
    }

    /** @return HasMany<Commission, $this> */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /** @return BelongsTo<Party, $this> */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class)->withTrashed();
    }

    /** @return BelongsTo<User, $this> */
    public function salesperson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    /** @return BelongsTo<Reservation, $this> */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    /** @return BelongsTo<User, $this> */
    public function deliverer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

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
