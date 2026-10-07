<?php

namespace App\Support;

use App\Models\User;

/**
 * Sidebar definition. Items are filtered by permission here; routes enforce them again server-side.
 */
class Navigation
{
    /**
     * @return list<array{title: string, items: list<array{label: string, route: string, icon: string, active: string, children: list<array{label: string, url: string, current: bool}>}>}>
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
                    ['label' => 'app.nav.sales', 'route' => 'sales.index', 'icon' => 'tag', 'can' => ['sales.view', 'sales.view_all'], 'children' => [
                        ['label' => 'app.nav_actions.view_sales', 'route' => 'sales.index'],
                        ['label' => 'app.nav_actions.new_sale', 'route' => 'sales.create', 'can' => 'sales.create'],
                    ]],
                    ['label' => 'app.nav.reservations', 'route' => 'reservations.index', 'icon' => 'calendar', 'can' => 'reservations.view', 'feature' => 'reservations', 'children' => [
                        ['label' => 'app.nav_actions.view_reservations', 'route' => 'reservations.index'],
                        ['label' => 'app.nav_actions.new_reservation', 'route' => 'reservations.index', 'new' => '1', 'can' => 'reservations.create'],
                    ]],
                    ['label' => 'app.nav.installments', 'route' => 'installments.index', 'icon' => 'receipt', 'can' => ['vouchers.view', 'sales.view_all'], 'feature' => 'installments'],
                    ['label' => 'app.nav.commissions', 'route' => 'commissions.index', 'icon' => 'currency', 'can' => 'commissions.view', 'feature' => 'commissions'],
                ],
            ],
            [
                'title' => 'app.nav.trading',
                'items' => [
                    ['label' => 'app.nav.vehicles', 'route' => 'vehicles.index', 'icon' => 'car', 'can' => 'vehicles.view'],
                    ['label' => 'app.nav.purchases', 'route' => 'purchases.index', 'icon' => 'cart', 'can' => 'purchases.view', 'children' => [
                        ['label' => 'app.nav_actions.view_purchases', 'route' => 'purchases.index'],
                        ['label' => 'app.nav_actions.new_purchase', 'route' => 'purchases.create', 'can' => 'purchases.create'],
                    ]],
                    ['label' => 'app.nav.consignments', 'route' => 'consignments.index', 'icon' => 'share', 'can' => 'consignments.view', 'feature' => 'consignment', 'children' => [
                        ['label' => 'app.nav_actions.view_consignments', 'route' => 'consignments.index'],
                        ['label' => 'app.nav_actions.new_consignment', 'route' => 'consignments.create', 'can' => 'consignments.manage'],
                    ]],
                    ['label' => 'app.nav.parties', 'route' => 'parties.index', 'icon' => 'users', 'can' => 'parties.view', 'children' => [
                        ['label' => 'app.nav_actions.view_parties', 'route' => 'parties.index'],
                        ['label' => 'app.nav_actions.new_party', 'route' => 'parties.index', 'new' => '1', 'can' => 'parties.manage'],
                    ]],
                ],
            ],
            [
                'title' => 'app.nav.finance',
                'items' => [
                    ['label' => 'app.nav.vouchers', 'route' => 'vouchers.index', 'icon' => 'receipt', 'can' => 'vouchers.view', 'children' => [
                        ['label' => 'app.nav_actions.view_vouchers', 'route' => 'vouchers.index'],
                        ['label' => 'app.nav_actions.new_receipt', 'route' => 'vouchers.index', 'new' => 'receipt', 'can' => 'vouchers.create'],
                        ['label' => 'app.nav_actions.new_payment', 'route' => 'vouchers.index', 'new' => 'payment', 'can' => 'vouchers.create'],
                        ['label' => 'app.nav_actions.new_transfer', 'route' => 'vouchers.index', 'new' => 'transfer', 'can' => 'vouchers.create'],
                    ]],
                    ['label' => 'app.nav.expenses', 'route' => 'expenses.index', 'icon' => 'cash', 'can' => 'expenses.view', 'children' => [
                        ['label' => 'app.nav_actions.view_expenses', 'route' => 'expenses.index'],
                        ['label' => 'app.nav_actions.new_expense', 'route' => 'expenses.index', 'new' => '1', 'can' => 'expenses.create'],
                    ]],
                    ['label' => 'app.nav.expense_categories', 'route' => 'expense-categories.index', 'icon' => 'tag', 'can' => 'accounts.manage'],
                ],
            ],
            [
                'title' => 'app.nav.accounting',
                'items' => [
                    ['label' => 'app.nav.reports', 'route' => 'reports.index', 'icon' => 'document', 'can' => ['reports.financial', 'reports.sales', 'reports.inventory', 'audit.view', 'sales.view', 'commissions.view', 'cashboxes.view', 'vehicles.view']],
                    ['label' => 'app.nav.journals', 'route' => 'journals.index', 'icon' => 'pencil', 'can' => 'journal.view', 'children' => [
                        ['label' => 'app.nav_actions.view_journals', 'route' => 'journals.index'],
                        ['label' => 'app.nav_actions.new_journal', 'route' => 'journals.index', 'new' => '1', 'can' => 'journal.create'],
                    ]],
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
                    ['label' => 'app.nav.imports', 'route' => 'imports.index', 'icon' => 'upload', 'can' => 'imports.run', 'feature' => 'imports'],
                    ['label' => 'app.nav.backups', 'route' => 'backups.index', 'icon' => 'database', 'can' => 'backups.manage'],
                    ['label' => 'app.nav.system_locks', 'route' => 'system.locks', 'icon' => 'lock', 'can' => 'system.locks'],
                    ['label' => 'app.nav.features', 'route' => 'settings.features', 'icon' => 'power', 'can' => 'system.features'],
                ],
            ],
        ];

        $result = [];

        foreach ($sections as $section) {
            $items = [];

            foreach ($section['items'] as $item) {
                if (isset($item['feature']) && ! app(Features::class)->enabled($item['feature'])) {
                    continue;
                }
                if ($item['can'] === null || $user->canAny((array) $item['can'])) {
                    $items[] = [
                        'label' => __($item['label']),
                        'route' => $item['route'],
                        'icon' => $item['icon'],
                        // vehicles.index stays highlighted on vehicles.show, etc.
                        'active' => str_replace('.index', '.*', $item['route']),
                        'children' => self::children($user, $item['children'] ?? []),
                    ];
                }
            }

            if ($items !== []) {
                $result[] = ['title' => $section['title'] === '' ? '' : __($section['title']), 'items' => $items];
            }
        }

        return $result;
    }

    /**
     * Dropdown entries of a sidebar item ("view" and "new ..."), keeping those the user may use.
     * Fewer than two left means no dropdown: the item stays a plain link.
     *
     * @param  list<array{label: string, route: string, new?: string, can?: string}>  $children
     * @return list<array{label: string, url: string, current: bool}>
     */
    private static function children(User $user, array $children): array
    {
        $result = [];
        foreach ($children as $child) {
            if (isset($child['can']) && ! $user->can($child['can'])) {
                continue;
            }

            $new = $child['new'] ?? null;
            $result[] = [
                'label' => __($child['label']),
                'url' => route($child['route'], $new !== null ? ['new' => $new] : []),
                'current' => request()->routeIs($child['route']) && (string) request()->query('new') === (string) $new,
            ];
        }

        return count($result) > 1 ? $result : [];
    }
}
