<?php

namespace App\Actions\Ownership;

use App\Exceptions\BusinessRuleException;
use App\Models\Party;
use Brick\Math\BigDecimal;

/**
 * Checks the owners of a vehicle: at least one, each an active party listed once, each share
 * positive, and the shares adding up to $total percent (100 for consignment; 100 less the
 * showroom's share for a partnership).
 */
class OwnerShares
{
    /**
     * @param  array<int, mixed>  $owners  list<{party_id, share}>
     * @return list<array{party_id: int, share: string}>
     */
    public static function validate(array $owners, string $total = '100'): array
    {
        $clean = [];
        $sum = BigDecimal::zero();

        foreach ($owners as $owner) {
            $partyId = (int) ($owner['party_id'] ?? 0);
            $share = BigDecimal::of((string) ($owner['share'] ?? '0'))->toScale(4);

            if ($partyId === 0 || ! $share->isPositive()) {
                throw BusinessRuleException::make('ownership.errors.owner_row');
            }
            if (isset($clean[$partyId])) {
                throw BusinessRuleException::make('ownership.errors.owner_twice');
            }

            $clean[$partyId] = ['party_id' => $partyId, 'share' => (string) $share];
            $sum = $sum->plus($share);
        }

        if ($clean === []) {
            throw BusinessRuleException::make('ownership.errors.no_owners');
        }
        if (Party::query()->whereIn('id', array_keys($clean))->where('is_active', true)->count() !== count($clean)) {
            throw BusinessRuleException::make('ownership.errors.owner_inactive');
        }
        if (! $sum->isEqualTo(BigDecimal::of($total))) {
            throw BusinessRuleException::make('ownership.errors.shares_total', ['total' => BigDecimal::of($total)->stripTrailingZeros()]);
        }

        return array_values($clean);
    }
}
