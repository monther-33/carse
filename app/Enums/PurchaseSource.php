<?php

namespace App\Enums;

enum PurchaseSource: string
{
    case Supplier = 'supplier';
    case Auction = 'auction';
    case Individual = 'individual';
    case TradeIn = 'trade_in';

    public function label(): string
    {
        return __('enums.purchase_source.'.$this->value);
    }
}
