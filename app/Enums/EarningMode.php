<?php

namespace App\Enums;

enum EarningMode: string
{
    case NetPrice = 'net_price';
    case Percent = 'percent';
    case Fixed = 'fixed';

    public function label(): string
    {
        return __('enums.earning_mode.'.$this->value);
    }
}
