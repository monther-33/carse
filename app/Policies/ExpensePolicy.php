<?php

namespace App\Policies;

use App\Policies\Concerns\DocumentPolicy;

/**
 * Cashbox restrictions (a treasurer only uses their cashboxes) are enforced by the
 * screens through Cashbox::scopeVisibleTo() and CashboxPolicy::view.
 */
class ExpensePolicy extends DocumentPolicy
{
    protected function module(): string
    {
        return 'expenses';
    }
}
