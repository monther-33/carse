<?php

namespace App\Policies;

class CurrencyPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'currencies.manage';
    }
}
