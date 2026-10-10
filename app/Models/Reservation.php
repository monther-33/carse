<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasOpaqueRouteKey;
use App\Models\Concerns\HasUserstamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $number
 * @property Carbon $date
 * @property int $vehicle_id
 * @property int $party_id
 * @property int $salesperson_id
 * @property string $deposit
 * @property string $forfeited_amount
 * @property int $currency_id
 * @property Carbon $expires_at
 * @property ReservationStatus $status
 * @property int|null $voucher_id
 * @property string|null $notes
 */
class Reservation extends Model
{
    use Auditable, HasOpaqueRouteKey, HasUserstamps;

    protected $fillable = [
        'branch_id', 'number', 'date', 'vehicle_id', 'party_id', 'salesperson_id', 'deposit', 'forfeited_amount', 'currency_id',
        'expires_at', 'status', 'voucher_id', 'notes', 'cancelled_by', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'expires_at' => 'date',
            'deposit' => 'decimal:3',
            'forfeited_amount' => 'decimal:3',
            'status' => ReservationStatus::class,
            'cancelled_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
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

    /** @return BelongsTo<Voucher, $this> */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    /** @param Builder<Reservation> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', ReservationStatus::Active);
    }
}
