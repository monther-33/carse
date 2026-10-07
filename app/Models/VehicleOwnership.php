<?php

namespace App\Models;

use App\Enums\EarningMode;
use App\Enums\OwnershipKind;
use App\Enums\OwnershipStatus;
use App\Enums\PayoutTiming;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Who owns a vehicle for one stay in the showroom, and the agreement with them.
 * consignment: the showroom owns nothing (showroom_share 0) and earns per earning_mode;
 * partnership: the showroom owns showroom_share percent and the sale is split by shares.
 *
 * @property int $id
 * @property int $branch_id
 * @property string|null $number
 * @property int $vehicle_id
 * @property OwnershipKind $kind
 * @property string $showroom_share
 * @property EarningMode|null $earning_mode
 * @property string|null $earning_amount
 * @property string|null $earning_percent
 * @property PayoutTiming $payout
 * @property OwnershipStatus $status
 * @property Carbon $received_at
 * @property Carbon|null $ended_at
 * @property string|null $end_reason
 * @property string|null $notes
 */
class VehicleOwnership extends Model
{
    use Auditable, HasUserstamps;

    protected $fillable = [
        'branch_id', 'number', 'vehicle_id', 'kind', 'showroom_share', 'earning_mode', 'earning_amount', 'earning_percent',
        'payout', 'status', 'received_at', 'ended_at', 'end_reason', 'source_type', 'source_id', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'kind' => OwnershipKind::class,
            'earning_mode' => EarningMode::class,
            'payout' => PayoutTiming::class,
            'status' => OwnershipStatus::class,
            'showroom_share' => 'decimal:4',
            'earning_amount' => 'decimal:3',
            'earning_percent' => 'decimal:4',
            'received_at' => 'date',
            'ended_at' => 'date',
        ];
    }

    public function isConsignment(): bool
    {
        return $this->kind === OwnershipKind::Consignment;
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return HasMany<VehicleOwnershipOwner, $this> */
    public function owners(): HasMany
    {
        return $this->hasMany(VehicleOwnershipOwner::class, 'ownership_id')->orderBy('id');
    }

    /** @return HasMany<VehicleOwnerDue, $this> */
    public function dues(): HasMany
    {
        return $this->hasMany(VehicleOwnerDue::class, 'ownership_id');
    }

    /** @return MorphTo<Model, $this> */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
