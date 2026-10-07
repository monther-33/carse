<?php

namespace App\Enums;

enum CostBearer: string
{
    case Showroom = 'showroom';
    case Owners = 'owners';

    public function label(): string
    {
        return __('enums.cost_bearer.'.$this->value);
    }
}
