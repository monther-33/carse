<?php

namespace App\Support;

use App\Models\User;

/**
 * Sidebar definition. Items are filtered by permission here; routes enforce them again server-side.
 */
class Navigation
{
    /**
     * @return list<array{title: string, items: list<array{label: string, route: string, icon: string, active: string}>}>
     */
    public static function for(User $user): array
    {
        $sections = [
            [
                'title' => '',
                'items' => [
                    ['label' => 'app.nav.dashboard', 'route' => 'dashboard', 'icon' => 'home', 'can' => null],
                ],
            ],
            [
                'title' => 'app.nav.selling',
                'items' => [
                    ['label' => 'app.nav.sales', 'route' => 'sales.index', 'icon' => 'tag', 'can' => ['sales.view', 'sales.view_all']],
                    ['label' => 'app.nav.reservations', 'route' => 'reservations.index', 'icon' => 'calendar', 'can' => 'reservations.view'],
                    ['label' => 'app.nav.installments', 'route' => 'installments.index', 'icon' => 'receipt', 'can' => ['vouchers.view', 'sales.view_all']],
                    ['label' => 'app.nav.commissions', 'route' => 'commissions.index', 'icon' => 'currency', 'can' => 'commissions.view'],
                ],
            ],
            [
                'title' => 'app.nav.trading',
                'items' => [
                    ['label' => 'app.nav.vehicles', 'route' => 'vehicles.index', 'icon' => 'car', 'can' => 'vehicles.view'],
                    ['label' => 'app.nav.purchases', 'route' => 'purchases.index', 'icon' => 'cart', 'can' => 'purchases.view'],
                    ['label' => 'app.nav.parties', 'route' => 'parties.index', 'icon' => 'users', 'can' => 'parties.view'],
                ],
            ],
            [
                'title' => 'app.nav.finance',
                'items' => [
                    ['label' => 'app.nav.vouchers', 'route' => 'vouchers.index', 'icon' => 'receipt', 'can' => 'vouchers.view'],
                    ['label' => 'app.nav.expenses', 'route' => 'expenses.index', 'icon' => 'cash', 'can' => 'expenses.view'],
                    ['label' => 'app.nav.expense_categories', 'route' => 'expense-categories.index', 'icon' => 'tag', 'can' => 'accounts.manage'],
                ],
            ],
            [
                'title' => 'app.nav.accounting',
                'items' => [
                    ['label' => 'app.nav.reports', 'route' => 'reports.index', 'icon' => 'document', 'can' => ['reports.financial', 'reports.sales', 'reports.inventory', 'audit.view', 'sales.view', 'commissions.view', 'cashboxes.view', 'vehicles.view']],
                    ['label' => 'app.nav.journals', 'route' => 'journals.index', 'icon' => 'pencil', 'can' => 'journal.view'],
                    ['label' => 'app.nav.accounts', 'route' => 'accounts.index', 'icon' => 'tree', 'can' => 'accounts.view'],
                    ['label' => 'app.nav.cashboxes', 'route' => 'cashboxes.index', 'icon' => 'cash', 'can' => 'cashboxes.view'],
                    ['label' => 'app.nav.periods', 'route' => 'periods.index', 'icon' => 'calendar', 'can' => 'periods.view'],
                ],
            ],
            [
                'title' => 'app.nav.setup',
                'items' => [
                    ['label' => 'app.nav.currencies', 'route' => 'currencies.index', 'icon' => 'currency', 'can' => ['currencies.manage', 'exchange_rates.manage']],
                    ['label' => 'app.nav.brands', 'route' => 'references.brands', 'icon' => 'tag', 'can' => 'references.manage'],
                    ['label' => 'app.nav.colors', 'route' => 'references.colors', 'icon' => 'palette', 'can' => 'references.manage'],
                    ['label' => 'app.nav.locations', 'route' => 'references.locations', 'icon' => 'pin', 'can' => 'references.manage'],
                    ['label' => 'app.nav.branches', 'route' => 'branches.index', 'icon' => 'building', 'can' => 'branches.manage'],
                    ['label' => 'app.nav.settings', 'route' => 'settings.index', 'icon' => 'cog', 'can' => 'settings.manage'],
                ],
            ],
            [
                'title' => 'app.nav.administration',
                'items' => [
                    ['label' => 'app.nav.users', 'route' => 'users.index', 'icon' => 'users', 'can' => 'users.manage'],
                    ['label' => 'app.nav.roles', 'route' => 'roles.index', 'icon' => 'shield', 'can' => 'roles.manage'],
                    ['label' => 'app.nav.imports', 'route' => 'imports.index', 'icon' => 'upload', 'can' => 'imports.run'],
                    ['label' => 'app.nav.backups', 'route' => 'backups.index', 'icon' => 'database', 'can' => 'backups.manage'],
                ],
            ],
        ];

        $result = [];

        foreach ($sections as $section) {
            $items = [];

            foreach ($section['items'] as $item) {
                if ($item['can'] === null || $user->canAny((array) $item['can'])) {
                    $items[] = [
                        'label' => __($item['label']),
                        'route' => $item['route'],
                        'icon' => $item['icon'],
                        // vehicles.index stays highlighted on vehicles.show, etc.
                        'active' => str_replace('.index', '.*', $item['route']),
                    ];
                }
            }

            if ($items !== []) {
                $result[] = ['title' => $section['title'] === '' ? '' : __($section['title']), 'items' => $items];
            }
        }

        return $result;
    }
}
