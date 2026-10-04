<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Idempotent: creates missing permissions/roles from config/permissions.php.
 * The admin role always receives every permission; other roles are only filled
 * when first created, so changes made from the UI are not overwritten.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $all = [];
        foreach (config('permissions.permissions') as $module => $actions) {
            foreach ($actions as $action) {
                $all[] = Permission::findOrCreate("{$module}.{$action}", 'web')->name;
            }
        }

        foreach (config('permissions.roles') as $name => $permissions) {
            $role = Role::findOrCreate($name, 'web');

            if ($permissions === '*') {
                $role->syncPermissions($all);
            } elseif ($role->wasRecentlyCreated || $role->permissions()->doesntExist()) {
                $role->syncPermissions($permissions);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
