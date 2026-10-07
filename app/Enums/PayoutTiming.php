<?php

namespace App\Enums;

enum PayoutTiming: string
{
    case OnSale = 'on_sale';
    case OnCollection = 'on_collection';

    public function label(): string
    {
        return __('enums.payout_timing.'.$this->value);
    }
}
