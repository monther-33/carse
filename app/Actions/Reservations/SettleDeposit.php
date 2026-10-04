<?php

namespace App\Actions\Reservations;

use App\Enums\AccountRole;
use App\Enums\ReservationStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Reservation;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Sales\DepositService;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\DB;

/**
 * Settles all or part of a reservation's deposit, at cancellation or any time later:
 *  - refund:  payment voucher, Dr customer deposits / Cr cashbox;
 *  - forfeit: Dr customer deposits / Cr forfeited deposits (revenue account from settings);
 *  - whatever is neither refunded nor forfeited stays as the customer's deposit credit,
 *    usable on any later sale.
 * The total can never exceed what is left of this deposit nor the customer's credit.
 */
class SettleDeposit
{
    public function __construct(
        private readonly DepositService $deposits,
        private readonly DepositVouchers $vouchers,
        private readonly PostingService $posting,
        private readonly AccountResolver $accounts,
    ) {}

    public function handle(Reservation $reservation, mixed $refund = '0', ?int $cashboxId = null, mixed $forfeit = '0', string $reason = ''): Reservation
    {
        return DB::transaction(function () use ($reservation, $refund, $cashboxId, $forfeit, $reason) {
            $reservation = Reservation::query()->lockForUpdate()->findOrFail($reservation->id);
            $refund = Money::of((string) ($refund ?: '0'));
            $forfeit = Money::of((string) ($forfeit ?: '0'));

            if ($reservation->status === ReservationStatus::Converted) {
                throw BusinessRuleException::make('reservations.errors.converted');
            }
            if ($refund->isNegative() || $forfeit->isNegative() || ! $refund->plus($forfeit)->isPositive()) {
                throw BusinessRuleException::make('reservations.errors.settle_amount');
            }

            $limit = $this->limit($reservation);
            if ($refund->plus($forfeit)->isGreaterThan($limit)) {
                throw BusinessRuleException::make('reservations.errors.settle_exceeds', ['available' => Money::format($limit)]);
            }

            if ($refund->isPositive()) {
                if ($cashboxId === null) {
                    throw BusinessRuleException::make('reservations.errors.refund_cashbox');
                }
                $this->vouchers->refund($reservation, $cashboxId, $refund,
                    __('reservations.refund_description', ['number' => $reservation->number]).($reason ? ' — '.$reason : ''));
            }

            if ($forfeit->isPositive()) {
                $this->forfeit($reservation, $forfeit, $reason);
            }

            return $reservation->refresh();
        });
    }

    /** Most that can still be settled: this deposit's remainder, capped by the customer's credit. */
    public function limit(Reservation $reservation): BigDecimal
    {
        $remaining = $this->deposits->remaining($reservation);
        $credit = $this->deposits->availableCredit($reservation->party_id, $reservation->currency_id);

        return $remaining->isLessThan($credit) ? $remaining : $credit;
    }

    private function forfeit(Reservation $reservation, BigDecimal $amount, string $reason): void
    {
        $rate = $this->deposits->carryingRate($reservation->party_id, $reservation->currency_id);

        $this->posting->post(
            JournalBuilder::make(now(), __('reservations.forfeit_description', ['number' => $reservation->number]).($reason ? ' — '.$reason : ''))
                ->source($reservation)
                ->branch($reservation->branch_id)
                ->debit($this->accounts->idFor(AccountRole::CustomerDeposits), $amount, $reservation->currency_id, $rate, partyId: $reservation->party_id)
                ->credit($this->accounts->idFor(AccountRole::ForfeitedDeposits), Money::toBase($amount, $rate))
        );

        $reservation->update(['forfeited_amount' => (string) Money::of($reservation->forfeited_amount)->plus($amount)]);
    }
}
