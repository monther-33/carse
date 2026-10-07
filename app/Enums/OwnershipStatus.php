<?php

namespace App\Enums;

enum OwnershipStatus: string
{
    case Active = 'active';
    case Sold = 'sold';
    case Returned = 'returned';
    case Closed = 'closed';

    public function label(): string
    {
        return __('enums.ownership_status.'.$this->value);
    }
}
