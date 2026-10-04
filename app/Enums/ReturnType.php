<?php

namespace App\Enums;

enum ReturnType: string
{
    case Purchase = 'purchase';
    case Sale = 'sale';

    public function label(): string
    {
        return __('enums.return_type.'.$this->value);
    }
}
