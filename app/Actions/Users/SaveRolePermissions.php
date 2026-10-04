<?php

namespace App\Actions\Users;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * The admin role always holds every permission, so it cannot be edited or locked out.
 */
class SaveRolePermissions
{
    public const ADMIN_ROLE = 'admin';

    /**
     * @param  list<string>  $permissions
     */
    public function handle(Role $role, array $permissions): void
    {
        if ($role->name === self::ADMIN_ROLE) {
            throw ValidationException::withMessages(['permissions' => __('app.roles.admin_locked')]);
        }

        DB::transaction(function () use ($role, $permissions) {
            $before = $role->permissions()->pluck('name')->all();
            $role->syncPermissions($permissions);

            activity('Role')->performedOn($role)->event('permissions_synced')
                ->withProperties(['old' => $before, 'attributes' => $permissions])
                ->log('permissions_synced');
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function create(string $name): Role
    {
        $role = new Role(['name' => $name, 'guard_name' => 'web']);
        $role->save();
        activity('Role')->performedOn($role)->event('created')->log('created');

        return $role;
    }

    public function delete(Role $role): void
    {
        if (array_key_exists($role->name, config('permissions.roles')) || $role->users()->exists()) {
            throw ValidationException::withMessages(['role' => __('app.roles.cannot_delete')]);
        }

        activity('Role')->performedOn($role)->event('deleted')->withProperties(['name' => $role->name])->log('deleted');
        $role->delete();
    }
}
