<?php

namespace App\Enums;

enum Transmission: string
{
    case Automatic = 'automatic';
    case Manual = 'manual';

    public function label(): string
    {
        return __('enums.transmission.'.$this->value);
    }
}
