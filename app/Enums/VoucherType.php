<?php

namespace App\Enums;

enum VoucherType: string
{
    case Receipt = 'receipt';
    case Payment = 'payment';
    case Transfer = 'transfer';
    case Journal = 'journal';

    public function label(): string
    {
        return __('enums.voucher_type.'.$this->value);
    }
}
