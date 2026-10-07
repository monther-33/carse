<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $ownership_id
 * @property int $party_id
 * @property string $share
 * @property string $contribution
 */
class VehicleOwnershipOwner extends Model
{
    protected $fillable = ['ownership_id', 'party_id', 'share', 'contribution'];

    protected function casts(): array
    {
        return ['share' => 'decimal:4', 'contribution' => 'decimal:3'];
    }

    /** @return BelongsTo<Party, $this> */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class)->withTrashed();
    }

    /** @return BelongsTo<VehicleOwnership, $this> */
    public function ownership(): BelongsTo
    {
        return $this->belongsTo(VehicleOwnership::class, 'ownership_id');
    }
}
