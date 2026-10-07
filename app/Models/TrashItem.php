<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One deleted row in the recycle bin (App\Services\Trash\RecycleBin).
 *
 * @property int $id
 * @property string $batch
 * @property int $position
 * @property string $label
 * @property class-string<Model> $model_type
 * @property int $model_id
 * @property bool $soft
 * @property array<string, mixed> $data
 * @property array<string, mixed>|null $extra
 * @property int|null $deleted_by
 * @property Carbon $deleted_at
 * @property int|null $restored_by
 * @property Carbon|null $restored_at
 */
class TrashItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['batch', 'position', 'label', 'model_type', 'model_id', 'soft', 'data', 'extra', 'deleted_by', 'deleted_at', 'restored_by', 'restored_at'];

    protected function casts(): array
    {
        return [
            'soft' => 'boolean',
            'data' => 'array',
            'extra' => 'array',
            'deleted_at' => 'datetime',
            'restored_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /** @return BelongsTo<User, $this> */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }
}
