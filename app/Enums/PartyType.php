<?php

namespace App\Enums;

enum PartyType: string
{
    case Customer = 'customer';
    case Supplier = 'supplier';
    case Both = 'both';

    public function label(): string
    {
        return __('enums.party_type.'.$this->value);
    }
}
