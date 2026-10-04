<?php

namespace App\Services\Sales;

use App\Support\Money;
use App\Support\Settings;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

/**
 * Salesperson commission per sold vehicle, from settings:
 * sales.commission_type = percent (of the net sale price in LYD) | fixed (LYD per vehicle).
 */
class CommissionCalculator
{
    public function __construct(private readonly Settings $settings) {}

    public function forVehicle(BigDecimal $netBase): BigDecimal
    {
        $value = Money::of((string) $this->settings->get('sales.commission_value', '0'));

        if ($this->settings->get('sales.commission_type', 'percent') === 'fixed') {
            return $value;
        }

        return $netBase->multipliedBy($value)->dividedBy(100, Money::SCALE, RoundingMode::HalfUp);
    }
}
