<?php

namespace App\Enums;

enum InstallmentStatus: string
{
    case Pending = 'pending';
    case Partial = 'partial';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return __('enums.installment_status.'.$this->value);
    }
}
