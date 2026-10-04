<?php

return [
    'reversal_of' => 'Reversal of entry :number',

    'errors' => [
        'unbalanced' => 'Unbalanced entry: debit :debit ≠ credit :credit.',
        'closed_period' => 'Fiscal period :period is closed for posting.',
        'no_period' => 'No fiscal period covers :date.',
        'not_postable' => 'Account :account is a group or inactive account and cannot be posted to.',
        'invalid_line' => 'Invalid journal line: :reason',
        'already_reversed' => 'Entry :number is already cancelled.',
        'missing_mapping' => 'No account is configured for ":role" in settings.',
        'missing_rate' => 'No exchange rate for :currency on or before :date.',
        'min_lines' => 'An entry needs at least two lines.',
        'non_positive_amount' => 'Line amount must be greater than zero.',
        'base_rate_must_be_one' => 'The base currency rate must be 1.',
        'non_positive_rate' => 'Exchange rate must be greater than zero.',
        'cannot_reverse_reversal' => 'A reversal entry cannot be reversed.',
    ],

    'validation' => [
        'parent_required' => 'Select a parent account.',
        'parent_must_be_group' => 'The parent must be a group account.',
        'code_prefix' => 'The account code must start with the parent code (:prefix) and be longer.',
        'system_account_locked' => 'A system account cannot be moved to another parent.',
        'has_postings' => 'An account with postings cannot become a group.',
        'has_children' => 'An account with children cannot become a posting account.',
        'code_locked' => 'The code of a system account or an account with children cannot change.',
        'account_in_use' => 'The account is in use (postings, children, cashbox or settings); deactivate it instead.',
        'cashbox_parent_missing' => 'No parent account is configured for this cashbox type in settings.',
        'period_already_closed' => 'The period is already closed.',
    ],
];
