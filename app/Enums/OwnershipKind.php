<?php

namespace App\Enums;

enum OwnershipKind: string
{
    case Consignment = 'consignment';
    case Partnership = 'partnership';

    public function label(): string
    {
        return __('enums.ownership_kind.'.$this->value);
    }
}
