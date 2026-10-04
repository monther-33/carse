<?php

namespace App\Enums;

enum FuelType: string
{
    case Petrol = 'petrol';
    case Diesel = 'diesel';
    case Hybrid = 'hybrid';
    case Electric = 'electric';
    case Gas = 'gas';

    public function label(): string
    {
        return __('enums.fuel_type.'.$this->value);
    }
}
