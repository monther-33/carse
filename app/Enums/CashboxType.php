<?php

namespace App\Enums;

enum CashboxType: string
{
    case Cash = 'cash';
    case Bank = 'bank';

    public function label(): string
    {
        return __('enums.cashbox_type.'.$this->value);
    }
}
