<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $vehicle_id
 * @property VehicleStatus|null $from_status
 * @property VehicleStatus $to_status
 * @property int|null $user_id
 * @property string|null $note
 * @property int|null $journal_entry_id
 */
class VehicleStatusLog extends Model
{
    protected $fillable = ['vehicle_id', 'from_status', 'to_status', 'user_id', 'note', 'source_type', 'source_id', 'journal_entry_id'];

    protected function casts(): array
    {
        return [
            'from_status' => VehicleStatus::class,
            'to_status' => VehicleStatus::class,
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return MorphTo<Model, $this> */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
