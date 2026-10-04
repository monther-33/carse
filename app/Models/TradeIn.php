<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The customer's car taken in part-exchange; value in the invoice currency.
 *
 * @property int $id
 * @property int $sales_invoice_id
 * @property int $vehicle_id
 * @property string $value
 * @property string $value_base
 * @property VehicleStatus $entry_status
 */
class TradeIn extends Model
{
    protected $fillable = ['sales_invoice_id', 'vehicle_id', 'value', 'value_base', 'entry_status'];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:3',
            'value_base' => 'decimal:3',
            'entry_status' => VehicleStatus::class,
        ];
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return BelongsTo<SalesInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }
}
