<?php

namespace App\Enums;

enum EarningMode: string
{
    case NetPrice = 'net_price';
    case Percent = 'percent';
    case Fixed = 'fixed';
    case None = 'none';      // no commission: the whole price goes to the owners

    public function label(): string
    {
        return __('enums.earning_mode.'.$this->value);
    }
}
