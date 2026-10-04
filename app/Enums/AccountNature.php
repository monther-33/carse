<?php

namespace App\Enums;

enum AccountNature: string
{
    case Debit = 'debit';
    case Credit = 'credit';

    public function label(): string
    {
        return __('enums.account_nature.'.$this->value);
    }
}
