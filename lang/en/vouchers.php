<?php

return [
    'new_receipt' => 'Receipt',
    'new_payment' => 'Payment',
    'new_transfer' => 'Cashbox transfer',
    'purpose' => 'Purpose',
    'party' => 'Party',
    'party_or_account' => 'Party / account',
    'from_cashbox' => 'From cashbox',
    'to_cashbox' => 'To cashbox',
    'for_invoice' => 'For invoice',
    'for_invoice_hint' => 'Settled at the invoice rate; any difference goes to currency differences.',

    'purposes' => [
        'customer' => 'Collection from customer',
        'deposit' => 'Customer deposit',
        'supplier_refund' => 'Refund from supplier',
        'supplier' => 'Payment to supplier',
        'customer_refund' => 'Refund to customer',
        'deposit_refund' => 'Deposit refund',
        'commissions' => 'Commission payout',
        'other' => 'Other (choose account)',
    ],

    'errors' => [
        'amount' => 'The amount must be greater than zero.',
        'same_cashbox' => 'The receiving cashbox must differ from the sending one.',
        'transfer_currency' => 'Transfers between cashboxes of different currencies are not supported.',
        'account' => 'Choose a posting account.',
        'party_required' => 'This account requires a party.',
        'type' => 'Unsupported voucher type here.',
        'cashbox_not_allowed' => 'You are not allowed to use this cashbox.',
    ],
];
