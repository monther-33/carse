<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Append-only log of costs capitalised on a vehicle (base currency).
 *
 * @property int $id
 * @property int $vehicle_id
 * @property int|null $expense_id
 * @property string $amount
 * @property string $description
 * @property bool $to_cost_of_sales
 */
class VehicleCost extends Model
{
    protected $fillable = ['vehicle_id', 'expense_id', 'amount', 'description', 'to_cost_of_sales', 'created_by'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:3',
            'to_cost_of_sales' => 'boolean',
        ];
    }

    /** @return BelongsTo<Expense, $this> */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
