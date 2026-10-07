<?php

namespace App\Models;

use App\Enums\PayoutTiming;
use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * price/discount/net in the invoice currency; cost_base = toBase(net, rate) in LYD.
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $vehicle_id
 * @property VehicleStatus $entry_status
 * @property string $price
 * @property string $discount
 * @property string $net
 * @property string $cost_base
 * @property int|null $return_id
 */
class PurchaseInvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'vehicle_id', 'entry_status', 'price', 'discount', 'net', 'cost_base', 'return_id', 'partners', 'partner_payout'];

    protected function casts(): array
    {
        return [
            'entry_status' => VehicleStatus::class,
            'price' => 'decimal:3',
            'discount' => 'decimal:3',
            'net' => 'decimal:3',
            'cost_base' => 'decimal:3',
            'partners' => 'array',
            'partner_payout' => PayoutTiming::class,
        ];
    }

    /** @return BelongsTo<PurchaseInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
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
