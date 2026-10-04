<?php

namespace App\Actions\Sales;

use App\Enums\PaymentType;
use App\Exceptions\BusinessRuleException;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * The amounts of a sale and the payment rules per payment type (spec 4.3). Shared by
 * saving the draft and posting it, so both apply exactly the same checks.
 *
 *  total = subtotal − discount                    (revenue)
 *  due   = total − trade-in value − deposit applied (what the customer still has to settle)
 *
 *  cash / transfer : payments taken now = due
 *  credit / mixed  : payments taken now ≤ due, the rest stays receivable
 *  installment     : payments taken now = down payment, due − down payment is scheduled
 */
final class SalesTerms
{
    public readonly BigDecimal $total;

    public readonly BigDecimal $due;

    public readonly BigDecimal $paid;

    /**
     * @param  list<BigDecimal>  $payments
     */
    public function __construct(
        public readonly PaymentType $type,
        public readonly BigDecimal $subtotal,
        public readonly BigDecimal $discount,
        public readonly BigDecimal $tradeIn,
        public readonly BigDecimal $deposit,
        array $payments,
        public readonly ?BigDecimal $downPayment = null,
        public readonly ?int $months = null,
    ) {
        $this->total = $subtotal->minus($discount);
        $this->due = $this->total->minus($tradeIn)->minus($deposit);
        $this->paid = Money::sum($payments);
    }

    public function financed(): BigDecimal
    {
        return $this->type === PaymentType::Installment
            ? $this->due->minus($this->downPayment ?? Money::zero())
            : Money::zero();
    }

    public function validate(): void
    {
        if (! $this->total->isPositive()) {
            throw BusinessRuleException::make('sales.errors.total');
        }
        if ($this->due->isNegative()) {
            throw BusinessRuleException::make('sales.errors.credits_exceed_total');
        }

        match ($this->type) {
            PaymentType::Cash, PaymentType::Transfer => $this->paid->isEqualTo($this->due)
                ?: throw BusinessRuleException::make('sales.errors.full_payment', ['due' => Money::format($this->due)]),
            PaymentType::Credit, PaymentType::Mixed => $this->paid->isGreaterThan($this->due)
                ? throw BusinessRuleException::make('sales.errors.overpaid', ['due' => Money::format($this->due)])
                : true,
            PaymentType::Installment => $this->validateInstallment(),
        };
    }

    private function validateInstallment(): bool
    {
        if ($this->months === null || $this->months < 1 || $this->months > 120) {
            throw BusinessRuleException::make('sales.errors.months');
        }
        if (! $this->paid->isEqualTo($this->downPayment ?? Money::zero())) {
            throw BusinessRuleException::make('sales.errors.down_payment_mismatch');
        }
        if (! $this->financed()->isPositive()) {
            throw BusinessRuleException::make('sales.errors.nothing_to_finance');
        }

        return true;
    }
}
