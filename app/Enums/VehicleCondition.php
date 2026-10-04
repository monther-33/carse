<?php

namespace App\Enums;

enum VehicleCondition: string
{
    case New = 'new';
    case Used = 'used';

    public function label(): string
    {
        return __('enums.vehicle_condition.'.$this->value);
    }
}
