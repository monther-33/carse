<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Support\PermissionLocks;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Users are never deleted; they are deactivated (is_active = false). They sign in with a
 * username (no email: owner's decision).
 *
 * @property int $id
 * @property int $branch_id
 * @property string $name
 * @property string $username login name (lowercase Latin letters, digits, . _ -)
 * @property bool $is_active
 * @property string $max_discount
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasFactory, HasUserstamps, Notifiable;

    use HasRoles {
        hasPermissionTo as protected roleHasPermissionTo;
    }

    protected $fillable = [
        'branch_id',
        'name',
        'username',
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
            'password' => 'hashed',
            'is_active' => 'boolean',
            'max_discount' => 'decimal:3',
        ];
    }

    /**
     * Spatie's permission check, minus the permissions the developer locked
     * (App\Support\PermissionLocks). Every Gate / can() / @can check goes through here.
     *
     * @param  mixed  $permission
     * @param  string|null  $guardName
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $name = is_object($permission) && isset($permission->name) ? $permission->name : $permission;

        if (is_string($name) && app(PermissionLocks::class)->isLocked($name) && ! $this->isDeveloper()) {
            return false;
        }

        return $this->roleHasPermissionTo($permission, $guardName);
    }

    public function isDeveloper(): bool
    {
        return $this->hasRole(PermissionLocks::DEVELOPER_ROLE);
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
