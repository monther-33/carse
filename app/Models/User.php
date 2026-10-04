<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Users are never deleted; they are deactivated (is_active = false).
 *
 * @property int $id
 * @property int $branch_id
 * @property string $name
 * @property string $email
 * @property bool $is_active
 * @property string $max_discount
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasFactory, HasRoles, HasUserstamps, Notifiable;

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'password',
        'is_active',
        'max_discount',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'max_discount' => 'decimal:3',
        ];
    }

    /** @return BelongsTo<Branch, $this> */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** @return BelongsToMany<Cashbox, $this> */
    public function cashboxes(): BelongsToMany
    {
        return $this->belongsToMany(Cashbox::class);
    }
}
