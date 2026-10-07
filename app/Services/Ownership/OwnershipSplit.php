<?php

namespace App\Services\Ownership;

use App\Enums\EarningMode;
use App\Models\VehicleOwnership;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

/**
 * Splits money of a vehicle that has owners (LYD, three decimals, nothing lost to rounding):
 *  - a sale price into the showroom's part and each owner's part;
 *  - an amount (expense, purchase cost) between the showroom and the owners by shares.
 *
 * Consignment, per the car's agreement:
 *   net_price  owners get the agreed net price, the showroom keeps the rest (may be negative:
 *              sold below the net price; a warning, the showroom bears it);
 *   percent    the showroom keeps the percentage of the price, the owners get the rest;
 *   fixed      the showroom keeps the fixed amount, the owners get the rest.
 * Partnership: everything by shares (showroom share first).
 */
class OwnershipSplit
{
    /**
     * @return array{showroom: BigDecimal, owners: array<int, BigDecimal>} owners keyed by party id
     */
    public function sale(VehicleOwnership $ownership, BigDecimal $netBase): array
    {
        $ownership->loadMissing('owners');
        $net = Money::of($netBase);

        if (! $ownership->isConsignment()) {
            return $this->byShares($ownership, $net);
        }

        $ownersTotal = match ($ownership->earning_mode) {
            EarningMode::NetPrice => Money::of($ownership->earning_amount),
            EarningMode::Percent => $net->minus($net->multipliedBy(Money::rate($ownership->earning_percent ?? '0'))->dividedBy(100, Money::SCALE, RoundingMode::HalfUp)),
            EarningMode::Fixed => $net->minus(Money::of($ownership->earning_amount)),
            null => $net,
        };

        $shares = Money::allocate($ownersTotal, $ownership->owners->pluck('share')->all());

        return [
            'showroom' => $net->minus($ownersTotal),
            'owners' => array_combine($ownership->owners->pluck('party_id')->all(), $shares),
        ];
    }

    /**
     * An amount split by ownership shares (the showroom's share first).
     *
     * @return array{showroom: BigDecimal, owners: array<int, BigDecimal>}
     */
    public function byShares(VehicleOwnership $ownership, BigDecimal $amount): array
    {
        $ownership->loadMissing('owners');
        $parts = Money::allocate($amount, [Money::rate($ownership->showroom_share), ...$ownership->owners->pluck('share')->all()]);

        return [
            'showroom' => array_shift($parts),
            'owners' => array_combine($ownership->owners->pluck('party_id')->all(), $parts),
        ];
    }

    /**
     * An amount charged to the owners only (consignment expenses), split by their shares.
     *
     * @return array<int, BigDecimal>
     */
    public function amongOwners(VehicleOwnership $ownership, BigDecimal $amount): array
    {
        $ownership->loadMissing('owners');

        return array_combine(
            $ownership->owners->pluck('party_id')->all(),
            Money::allocate($amount, $ownership->owners->pluck('share')->all()),
        );
    }
}
