<?php

namespace App\Services\Ownership;

use App\Models\Vehicle;
use App\Models\VehicleOwnership;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Selling a consignment car below what was agreed with its owners: a WARNING only (owner's
 * decision), the showroom bears the difference. Below the net price (net_price), or below
 * the fixed commission (fixed), the showroom's part of the sale is negative.
 */
class NetPriceCheck
{
    public function __construct(private readonly OwnershipSplit $split) {}

    /**
     * @param  list<array{vehicle: Vehicle, net_base: BigDecimal, commission?: string|null}>  $lines  each car with its net sale price in LYD
     *                                                                                                and the commission chosen on the sale (null: as agreed)
     * @return list<array{vin: string, short: BigDecimal}>
     */
    public function warnings(array $lines): array
    {
        $warnings = [];

        foreach ($lines as $line) {
            ['vehicle' => $vehicle, 'net_base' => $net] = $line;
            if ($vehicle->ownership_id === null || ($line['commission'] ?? null) !== null) {
                continue;
            }
            $ownership = VehicleOwnership::query()->with('owners')->find($vehicle->ownership_id);
            if ($ownership === null || ! $ownership->isConsignment()) {
                continue;
            }

            $showroom = $this->split->sale($ownership, $net)['showroom'];
            if ($showroom->isNegative()) {
                $warnings[] = ['vin' => $vehicle->vin, 'short' => Money::of($showroom->negated())];
            }
        }

        return $warnings;
    }
}
