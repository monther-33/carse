<?php

namespace App\Enums;

/**
 * How a sale is settled. cash/transfer: paid in full now; credit: on account; installment: down payment + schedule; mixed: several payments, remainder on account.
 */
enum PaymentType: string
{
    case Cash = 'cash';
    case Credit = 'credit';
    case Installment = 'installment';
    case Transfer = 'transfer';
    case Mixed = 'mixed';

    public function label(): string
    {
        return __('enums.payment_type.'.$this->value);
    }
}
