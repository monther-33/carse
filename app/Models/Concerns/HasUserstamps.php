<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Fills created_by / updated_by from the authenticated user.
 *
 * @mixin Model
 */
trait HasUserstamps
{
    public static function bootHasUserstamps(): void
    {
        static::creating(function (Model $model) {
            $userId = Auth::id();
            $model->setAttribute('created_by', $model->getAttribute('created_by') ?? $userId);
            $model->setAttribute('updated_by', $model->getAttribute('updated_by') ?? $userId);
        });

        static::updating(function (Model $model) {
            if (Auth::id() !== null) {
                $model->setAttribute('updated_by', Auth::id());
            }
        });
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return BelongsTo<User, $this> */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
