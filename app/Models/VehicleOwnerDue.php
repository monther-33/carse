<?php

namespace App\Models;

use App\Enums\PayoutTiming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * What one owner is due from the sale of one vehicle (LYD).
 *
 * @property int $id
 * @property int $ownership_id
 * @property int $sales_invoice_item_id
 * @property int $party_id
 * @property string $amount
 * @property PayoutTiming $payout
 * @property Carbon|null $reversed_at
 */
class VehicleOwnerDue extends Model
{
    protected $fillable = ['ownership_id', 'sales_invoice_item_id', 'party_id', 'amount', 'payout', 'reversed_at'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:3', 'payout' => PayoutTiming::class, 'reversed_at' => 'datetime'];
    }

    /** @param Builder<self> $query */
    public function scopeOpen(Builder $query): void
    {
        $query->whereNull('reversed_at');
    }

    /** @return BelongsTo<SalesInvoiceItem, $this> */
    public function item(): BelongsTo
    {
        return $this->belongsTo(SalesInvoiceItem::class, 'sales_invoice_item_id');
    }

    /** @return BelongsTo<VehicleOwnership, $this> */
    public function ownership(): BelongsTo
    {
        return $this->belongsTo(VehicleOwnership::class, 'ownership_id');
    }

    /** @return BelongsTo<Party, $this> */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class)->withTrashed();
    }
}
