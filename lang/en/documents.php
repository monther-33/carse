<?php

return [
    'number' => 'Number',
    'entry' => 'Journal entry',
    'amount' => 'Amount',
    'rate' => 'Exchange rate',
    'rate_hint' => 'LYD per unit. Leave empty to use the rate of the day.',
    'cashbox' => 'Cashbox',
    'subtotal' => 'Subtotal',
    'discount' => 'Discount',
    'total' => 'Total',
    'paid' => 'Paid',
    'created_by' => 'Created by',
    'approved_by' => 'Approved by',
    'save_draft' => 'Save',
    'approve' => 'Approve',
    'confirm_approve' => 'Approve this document? Its entry will be posted and it can no longer be edited.',
    'posted_ok' => 'Approved and posted.',
    'cancel_document' => 'Cancel',
    'cancel_reason' => 'Reason',
    'confirm_cancel' => 'Confirm cancellation (reversing entry)',
    'cancelled_ok' => 'Cancelled with a reversing entry.',
    'confirm_delete_draft' => 'Delete this draft permanently?',
    'cancellation_of' => 'Cancellation of :number',
    'cancelled_info' => 'Cancelled by :user on :date — reason: :reason',

    'errors' => [
        'wrong_status' => 'Not possible: the document is ":status".',
        'not_draft' => 'Only drafts can be edited.',
        'not_posted' => 'The document is not posted.',
        'cashbox_currency' => 'The cashbox currency must match the document currency.',
    ],
];
