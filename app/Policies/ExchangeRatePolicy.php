<?php

namespace App\Policies;

class ExchangeRatePolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'exchange_rates.manage';
    }
}
