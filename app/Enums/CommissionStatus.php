<?php

namespace App\Enums;

enum CommissionStatus: string
{
    case Accrued = 'accrued';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return __('enums.commission_status.'.$this->value);
    }
}
