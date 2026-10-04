<?php

namespace App\Models;

use App\Enums\CommissionStatus;
use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Salesperson commission per sold vehicle (base currency).
 *
 * @property int $id
 * @property int $sales_invoice_id
 * @property int $sales_invoice_item_id
 * @property int $user_id
 * @property string $amount
 * @property CommissionStatus $status
 * @property int|null $voucher_id
 */
class Commission extends Model
{
    use Auditable;

    protected $fillable = ['sales_invoice_id', 'sales_invoice_item_id', 'user_id', 'amount', 'status', 'voucher_id'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:3',
            'status' => CommissionStatus::class,
        ];
    }

    /** Sales staff only see their own commissions. */
    /** @param Builder<Commission> $query */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if (! $user->can('commissions.view_all')) {
            $query->where('user_id', $user->id);
        }
    }

    /** @return BelongsTo<SalesInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    /** @return BelongsTo<SalesInvoiceItem, $this> */
    public function item(): BelongsTo
    {
        return $this->belongsTo(SalesInvoiceItem::class, 'sales_invoice_item_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Voucher, $this> */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
