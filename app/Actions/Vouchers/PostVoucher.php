<?php

namespace App\Actions\Vouchers;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Enums\VoucherType;
use App\Models\Cashbox;
use App\Models\InstallmentPlan;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Accounting\PostingService;
use App\Services\Accounting\SettlementLines;
use App\Services\Installments\InstallmentAllocator;
use App\Services\Numbering\SequenceService;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * Posts a draft voucher:
 *  - receipt:  Dr cashbox  / Cr counter account (party settlement with FX difference)
 *  - payment:  Dr counter account (party settlement with FX difference) / Cr cashbox
 *  - transfer: Dr receiving cashbox / Cr sending cashbox (same currency)
 */
class PostVoucher
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly PostingService $posting,
        private readonly SequenceService $sequences,
        private readonly AccountResolver $accounts,
        private readonly SettlementLines $settlement,
        private readonly InstallmentAllocator $installments,
    ) {}

    public function handle(Voucher $voucher): Voucher
    {
        return DB::transaction(function () use ($voucher) {
            $voucher = $this->lockInStatus($voucher, DocumentStatus::Draft);
            $cashbox = Cashbox::query()->findOrFail($voucher->cashbox_id);

            $number = $this->sequences->next(SequenceType::forVoucher($voucher->type), $voucher->date);
            $builder = JournalBuilder::make($voucher->date, $number.' — '.$voucher->description)
                ->source($voucher)
                ->branch($voucher->branch_id);

            $amount = Money::of($voucher->amount);
            $rate = Money::rate($voucher->rate);

            match ($voucher->type) {
                VoucherType::Receipt => $this->receipt($builder, $voucher, $cashbox, $amount, $rate),
                VoucherType::Payment => $this->payment($builder, $voucher, $cashbox, $amount, $rate),
                VoucherType::Transfer => $builder
                    ->debit(Cashbox::query()->findOrFail($voucher->to_cashbox_id)->account_id, $amount, $voucher->currency_id, $rate)
                    ->credit($cashbox->account_id, $amount, $voucher->currency_id, $rate),
                VoucherType::Journal => throw new LogicException('Journal vouchers are posted by the manual journal screen.'),
            };

            $entry = $this->posting->post($builder);
            $this->markPosted($voucher, $number, $entry);

            // A collection on an installment plan is spread over its installments, oldest first.
            $this->installments->allocate($voucher);

            return $voucher;
        });
    }

    private function receipt(JournalBuilder $builder, Voucher $voucher, Cashbox $cashbox, BigDecimal $amount, BigDecimal $rate): void
    {
        $builder->debit($cashbox->account_id, $amount, $voucher->currency_id, $rate);
        $this->counter($builder, $voucher, $amount, $rate, partyIsDebit: false);
    }

    private function payment(JournalBuilder $builder, Voucher $voucher, Cashbox $cashbox, BigDecimal $amount, BigDecimal $rate): void
    {
        $this->counter($builder, $voucher, $amount, $rate, partyIsDebit: true);
        $builder->credit($cashbox->account_id, $amount, $voucher->currency_id, $rate);
    }

    private function counter(JournalBuilder $builder, Voucher $voucher, BigDecimal $amount, BigDecimal $rate, bool $partyIsDebit): void
    {
        $accountId = (int) $voucher->account_id;

        if ($voucher->party_id !== null && in_array($accountId, $this->accounts->partyAccountIds(), true)) {
            $this->settlement->add(
                $builder, $partyIsDebit, $accountId, $voucher->party_id, $voucher->currency_id,
                $amount, $rate, $this->invoiceRate($voucher), $voucher->description,
            );

            return;
        }

        $builder->{$partyIsDebit ? 'debit' : 'credit'}($accountId, $amount, $voucher->currency_id, $rate, partyId: $voucher->party_id);
    }

    /**
     * A voucher that references an invoice in the same currency settles at that invoice's rate.
     */
    private function invoiceRate(Voucher $voucher): ?BigDecimal
    {
        $invoice = $voucher->reference;

        if ($invoice instanceof InstallmentPlan) {
            $invoice = $invoice->invoice;
        }

        if (($invoice instanceof PurchaseInvoice || $invoice instanceof SalesInvoice) && $invoice->currency_id === $voucher->currency_id) {
            return Money::rate($invoice->rate);
        }

        return null;
    }
}
