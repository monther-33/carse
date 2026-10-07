<?php

return [
    'new' => 'New expense',
    'edit' => 'Edit expense',
    'category' => 'Category',
    'category_account_hint' => 'Account charged for operating expenses. Vehicle expenses are capitalised on the car.',
    'vehicle' => 'Charged to vehicle',
    'vehicle_hint' => 'Leave empty for a general operating expense.',
    'receipt' => 'Receipt image',
    'recurs_every_months' => 'Repeats every (months)',
    'recurs_hint' => 'For recurring expenses such as rent; a reminder shows when due.',
    'every_months' => 'every :count months',
    'recurring_due' => ':count recurring expense(s) are due.',
    'due_only' => 'Due only',
    'repeat' => 'Repeat',

    'borne_by' => 'Borne by',
    'borne_by_consignment' => 'Consignment car: "owners" is deducted from what they are due, by share, and is not a showroom cost.',
    'borne_by_partnership' => 'Partnership car: "owners" is split between the partners and the showroom by shares.',
    'errors' => [
        'amount' => 'The amount must be greater than zero.',
        'vehicle_not_ours' => 'Vehicle :vin is neither in stock nor sold by us.',
        'cannot_cancel_capitalised' => 'Cannot cancel the expense on :vin: its cost moved (sale or left customs) since it was posted.',
    ],
];
