<?php

namespace App\Services\Sales;

use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\VoucherType;
use App\Models\Reservation;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\PartyBalanceService;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Deposit figures, always computed from the ledger and vouchers (never stored):
 *  - received:  posted receipts on customer deposits referencing the reservation;
 *  - refunded:  payment vouchers (not cancelled) on customer deposits referencing it;
 *  - forfeited: reservations.forfeited_amount;
 *  - a customer's available deposit credit: their credit balance on customer deposits,
 *    whatever reservation it came from — usable on any later sale.
 */
class DepositService
{
    public function __construct(
        private readonly AccountResolver $accounts,
        private readonly PartyBalanceService $balances,
    ) {}

    public function received(Reservation $reservation): BigDecimal
    {
        return $this->sum($reservation, VoucherType::Receipt, [DocumentStatus::Posted]);
    }

    /** Includes refunds still awaiting approval, so the same money is never promised twice. */
    public function refunded(Reservation $reservation): BigDecimal
    {
        return $this->sum($reservation, VoucherType::Payment, [DocumentStatus::Draft, DocumentStatus::Posted]);
    }

    /** What is left of this reservation's deposit to refund, forfeit or apply. */
    public function remaining(Reservation $reservation): BigDecimal
    {
        $left = $this->received($reservation)
            ->minus($this->refunded($reservation))
            ->minus(Money::of($reservation->forfeited_amount));

        return $left->isNegative() ? Money::zero() : $left;
    }

    /** The customer's unused deposit credit in a currency (positive amount). */
    public function availableCredit(int $partyId, int $currencyId): BigDecimal
    {
        $amount = $this->balances->balance($partyId, $this->accounts->idFor(AccountRole::CustomerDeposits), $currencyId)['amount'];
        $credit = $amount->negated();

        return $credit->isPositive() ? $credit : Money::zero();
    }

    /** Rate at which the customer's deposit credit is carried (1 for the base currency). */
    public function carryingRate(int $partyId, int $currencyId): BigDecimal
    {
        return $this->balances->carryingRate($partyId, $this->accounts->idFor(AccountRole::CustomerDeposits), $currencyId)
            ?? Money::rate(1);
    }

    /**
     * @param  list<DocumentStatus>  $statuses
     */
    private function sum(Reservation $reservation, VoucherType $type, array $statuses): BigDecimal
    {
        $amounts = Voucher::query()
            ->whereMorphedTo('reference', $reservation)
            ->where('type', $type)
            ->where('account_id', $this->accounts->idFor(AccountRole::CustomerDeposits))
            ->whereIn('status', $statuses)
            ->pluck('amount')
            ->all();

        return Money::sum($amounts);
    }
}
