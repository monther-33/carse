<?php

namespace App\Models;

use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * price/discount/net in the invoice currency; net_base, cost_snapshot and commission in LYD.
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $vehicle_id
 * @property string $price
 * @property string $discount
 * @property string $net
 * @property string $net_base
 * @property string|null $cost_snapshot
 * @property string $commission
 * @property int|null $return_id
 */
class SalesInvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'vehicle_id', 'price', 'discount', 'net', 'net_base', 'cost_snapshot', 'commission', 'return_id', 'ownership_id', 'showroom_revenue'];

    protected function casts(): array
    {
        return [
            'showroom_revenue' => 'decimal:3',
            'price' => 'decimal:3',
            'discount' => 'decimal:3',
            'net' => 'decimal:3',
            'net_base' => 'decimal:3',
            'cost_snapshot' => 'decimal:3',
            'commission' => 'decimal:3',
        ];
    }

    /** Spec 4.3: profit = sale price − cost_snapshot − commission (base currency). */
    public function profit(): ?BigDecimal
    {
        if ($this->cost_snapshot === null) {
            return null;
        }

        return Money::of($this->net_base)->minus(Money::of($this->cost_snapshot))->minus(Money::of($this->commission));
    }

    /** @return BelongsTo<SalesInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return BelongsTo<ReturnDocument, $this> */
    public function returnDocument(): BelongsTo
    {
        return $this->belongsTo(ReturnDocument::class, 'return_id');
    }
}
