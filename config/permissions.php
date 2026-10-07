<?php

/*
|--------------------------------------------------------------------------
| Permission catalog and default role matrix (spec section 7)
|--------------------------------------------------------------------------
| Permission names use "module.action". Labels live in lang/{ar,en}/permissions.php.
| RolesAndPermissionsSeeder syncs this file into the database; after seeding, the
| admin manages role permissions from the UI.
*/

return [
    'permissions' => [
        'vehicles' => ['view', 'view_cost', 'create', 'update'],
        'purchases' => ['view', 'create', 'approve', 'cancel'],
        'consignments' => ['view', 'manage'],
        'quotations' => ['view', 'create'],
        'reservations' => ['view', 'create', 'cancel'],
        'sales' => ['view', 'view_all', 'create', 'approve', 'cancel', 'override_discount', 'override_min_price'],
        'commissions' => ['view', 'view_all'],
        'parties' => ['view', 'manage'],
        'vouchers' => ['view', 'create', 'approve', 'cancel'],
        'expenses' => ['view', 'create', 'approve', 'cancel'],
        'journal' => ['view', 'create', 'approve', 'cancel'],
        'accounts' => ['view', 'manage'],
        'cashboxes' => ['view', 'view_all', 'manage'],
        'periods' => ['view', 'manage', 'close'],
        'exchange_rates' => ['manage'],
        'reports' => ['financial', 'sales', 'inventory'],
        'references' => ['manage'],
        'currencies' => ['manage'],
        'branches' => ['manage'],
        'settings' => ['manage'],
        'users' => ['manage'],
        'roles' => ['manage'],
        'audit' => ['view'],
        'imports' => ['run'],
        'backups' => ['manage'],
        'system' => ['locks', 'features', 'trash'],
    ],

    /*
    | Held by the developer role only, never by the admin or any other role: the developer
    | locks permissions away from everyone else (App\Support\PermissionLocks), switches
    | features, restores from the recycle bin and manages currencies (owner's request).
    */
    'developer_only' => ['system.locks', 'system.features', 'system.trash', 'currencies.manage'],

    'roles' => [
        // Above the admin: every permission, always; cannot be edited, locked or seen by others.
        'developer' => '*',

        // Every permission except the developer-only ones, minus any the developer locked.
        'admin' => '*',

        'accountant' => [
            'vehicles.view', 'vehicles.view_cost',
            'purchases.view', 'purchases.create', 'purchases.approve',
            'consignments.view', 'consignments.manage',
            'sales.view', 'sales.view_all', 'sales.create', 'sales.approve',
            'commissions.view', 'commissions.view_all',
            'parties.view', 'parties.manage',
            'vouchers.view', 'vouchers.create', 'vouchers.approve',
            'expenses.view', 'expenses.create', 'expenses.approve',
            'journal.view', 'journal.create', 'journal.approve',
            'accounts.view', 'accounts.manage',
            'cashboxes.view', 'cashboxes.view_all', 'cashboxes.manage',
            'periods.view', 'periods.manage',
            'exchange_rates.manage',
            'reports.financial', 'reports.sales', 'reports.inventory',
            'imports.run',
        ],

        'cashier' => [
            'vehicles.view',
            'parties.view',
            'vouchers.view', 'vouchers.create',
            'expenses.view', 'expenses.create',
            'cashboxes.view',
        ],

        'sales' => [
            'vehicles.view',
            'quotations.view', 'quotations.create',
            'reservations.view', 'reservations.create',
            'sales.view', 'sales.create',
            'commissions.view',
            'parties.view', 'parties.manage',
        ],

        'purchasing' => [
            'vehicles.view', 'vehicles.view_cost', 'vehicles.create', 'vehicles.update',
            'purchases.view', 'purchases.create',
            'consignments.view', 'consignments.manage',
            'parties.view', 'parties.manage',
            'references.manage',
            'reports.inventory',
        ],
    ],
];
