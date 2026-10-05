<?php

namespace App\Policies;

use App\Policies\Concerns\DocumentPolicy;

/**
 * Opening stock is an accounting entry: it follows the manual journal permissions.
 */
class OpeningStockPolicy extends DocumentPolicy
{
    protected function module(): string
    {
        return 'journal';
    }
}
