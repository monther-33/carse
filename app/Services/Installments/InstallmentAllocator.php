<?php

namespace App\Services\Installments;

use App\Enums\InstallmentStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use App\Models\InstallmentPlan;
use App\Models\Voucher;
use App\Support\Money;
use Brick\Math\BigDecimal;

/**
 * Spreads an installment collection (a receipt voucher referencing the plan) over the
 * open installments, oldest due date first, and undoes it when the voucher is cancelled.
 */
class InstallmentAllocator
{
    public function allocate(Voucher $voucher): void
    {
        $plan = $voucher->reference;
        if (! $plan instanceof InstallmentPlan) {
            return;
        }

        if ($plan->invoice->currency_id !== $voucher->currency_id) {
            throw BusinessRuleException::make('installments.errors.currency');
        }

        $remaining = Money::of($voucher->amount);
        $installments = Installment::query()
            ->where('plan_id', $plan->id)
            ->open()
            ->orderBy('due_date')->orderBy('sequence')
            ->lockForUpdate()
            ->get();

        foreach ($installments as $installment) {
            if (! $remaining->isPositive()) {
                break;
            }

            $applied = $remaining->isGreaterThan($installment->remaining()) ? $installment->remaining() : $remaining;
            $this->apply($installment, $applied);
            InstallmentPayment::query()->create([
                'installment_id' => $installment->id,
                'voucher_id' => $voucher->id,
                'amount' => (string) $applied,
            ]);
            $remaining = $remaining->minus($applied);
        }

        if ($remaining->isPositive()) {
            throw BusinessRuleException::make('installments.errors.overpayment', ['amount' => Money::format($remaining)]);
        }
    }

    public function release(Voucher $voucher): void
    {
        $payments = InstallmentPayment::query()->where('voucher_id', $voucher->id)->get();

        foreach ($payments as $payment) {
            $installment = Installment::query()->lockForUpdate()->findOrFail($payment->installment_id);
            $this->apply($installment, Money::of($payment->amount)->negated());
            $payment->delete();
        }
    }

    private function apply(Installment $installment, BigDecimal $amount): void
    {
        $paid = Money::of($installment->paid_amount)->plus($amount);

        $installment->update([
            'paid_amount' => (string) $paid,
            'status' => match (true) {
                $paid->isZero() => InstallmentStatus::Pending,
                $paid->isGreaterThanOrEqualTo($installment->amount) => InstallmentStatus::Paid,
                default => InstallmentStatus::Partial,
            },
        ]);
    }
}
