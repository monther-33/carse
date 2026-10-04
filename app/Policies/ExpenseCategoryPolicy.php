<?php

namespace App\Policies;

/**
 * Categories decide which ledger account an expense hits, so they belong to whoever
 * manages the chart of accounts.
 */
class ExpenseCategoryPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'accounts.manage';
    }
}
