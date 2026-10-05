<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Idempotent: creates missing permissions/roles from config/permissions.php.
 * The admin role always receives every permission. Other roles are filled from the config
 * when first created; afterwards only permissions that did not exist before this run are
 * granted to them, so changes made from the UI are never overwritten.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $all = [];
        $new = [];
        foreach (config('permissions.permissions') as $module => $actions) {
            foreach ($actions as $action) {
                $permission = Permission::findOrCreate("{$module}.{$action}", 'web');
                $all[] = $permission->name;
                if ($permission->wasRecentlyCreated) {
                    $new[] = $permission->name;
                }
            }
        }

        foreach (config('permissions.roles') as $name => $permissions) {
            $role = Role::findOrCreate($name, 'web');

            if ($permissions === '*') {
                $role->syncPermissions($all);
            } elseif ($role->wasRecentlyCreated || $role->permissions()->doesntExist()) {
                $role->syncPermissions($permissions);
            } else {
                $role->givePermissionTo(array_values(array_intersect($permissions, $new)));
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
