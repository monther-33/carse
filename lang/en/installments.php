<?php

return [
    'overdue' => 'Overdue',
    'remaining' => 'Remaining',
    'collect' => 'Collect',
    'collect_hint' => 'A receipt voucher on the installment plan, spread over the oldest installments first.',
    'collected' => 'Collected and spread over the installments.',
    'collection_pending' => 'Receipt saved for approval; it is spread over the installments once approved.',
    'collection_description' => 'Installment collection for sales invoice :number',
    'search_placeholder' => 'Search by customer or invoice number',

    'filters' => [
        'overdue' => 'Overdue',
        'week' => 'Due this week',
        'open' => 'All unpaid',
        'paid' => 'Paid',
    ],

    'errors' => [
        'overpayment' => 'The amount exceeds the remaining installments by :amount.',
        'currency' => 'The collection currency must match the sales invoice currency.',
    ],
];
