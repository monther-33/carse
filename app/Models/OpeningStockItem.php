<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $opening_stock_id
 * @property int $vehicle_id
 * @property VehicleStatus $entry_status
 * @property string $cost
 * @property Carbon $received_at
 */
class OpeningStockItem extends Model
{
    protected $fillable = ['opening_stock_id', 'vehicle_id', 'entry_status', 'cost', 'received_at'];

    protected function casts(): array
    {
        return [
            'entry_status' => VehicleStatus::class,
            'cost' => 'decimal:3',
            'received_at' => 'date',
        ];
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
