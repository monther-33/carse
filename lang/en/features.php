<?php

return [
    'intro' => 'Switch parts of the system on or off to match how the showroom works. A switched-off part disappears from menus, forms, the dashboard, alerts and reports, and the server refuses it; its data is kept and comes back when switched on again.',
    'on' => 'On',
    'off' => 'Off',
    'names' => [
        'reservations' => 'Reservations and deposits',
        'installments' => 'Installment sales',
        'trade_in' => 'Trade-ins',
        'commissions' => 'Sales commissions',
        'imports' => 'Data import',
        'consignment' => 'Consignment and partnership cars',
    ],
    'descriptions' => [
        'reservations' => 'Reserving vehicles for customers with a deposit, refunding or forfeiting it, applying it to a sale, and alerts for reservations about to end.',
        'installments' => 'The "installments" payment type with guarantor, schedule, collections, report and alerts for due and overdue installments.',
        'trade_in' => 'Taking the customer’s car in part payment and bringing it into stock.',
        'commissions' => 'Salesperson commission per vehicle and its payout, screen, report and settings. When off, no new commission is computed.',
        'consignment' => 'Cars owned by others that the showroom sells for a commission or a net price, cars bought with partners by shares, and what the owners are owed.',
        'imports' => 'The Excel import of customers, vehicles and opening balances. Can be switched off after go-live.',
    ],
    'blockers' => [
        'reservations' => 'There are :count active reservations; end them or turn them into sales first.',
        'deposits' => 'Customers hold deposits worth :amount; apply them to sales or refund them first.',
        'installments' => 'There are :count unpaid installments.',
        'consignment' => ':count consignment or partnership cars are still active.',
        'owners' => ':count owners and partners have unsettled balances.',
        'commissions' => 'There are :count commissions due and not paid.',
    ],
    'cannot_disable' => 'Cannot be switched off now: :reason',
    'confirm_off' => 'Switch off ":feature"? It disappears from the system; its data is kept.',
    'enabled_ok' => ':feature switched on.',
    'disabled_ok' => ':feature switched off.',
    'errors' => [
        'disabled' => '":feature" is switched off in this system.',
        'blocked' => 'Cannot switch off: :reason',
    ],
];
