<?php

namespace App\Enums;

/**
 * active → converted (sold) | expired | cancelled. The deposit stays in customer deposits until applied to a sale or refunded.
 */
enum ReservationStatus: string
{
    case Active = 'active';
    case Converted = 'converted';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return __('enums.reservation_status.'.$this->value);
    }
}
