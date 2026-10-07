<?php

namespace App\Actions\Vouchers;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\VoucherType;
use App\Exceptions\BusinessRuleException;
use App\Models\Account;
use App\Models\Cashbox;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Creates or updates a DRAFT receipt / payment / transfer voucher.
 * The currency is always the cashbox currency. Receipts and payments need a counter
 * account; a party is required when that account is a party control account.
 */
class SaveVoucher
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ExchangeRateService $rates,
        private readonly AccountResolver $accounts,
    ) {}

    /**
     * @param  array{type: string, date: string, cashbox_id: int, amount: mixed, description: string,
     *               party_id?: int|null, to_cashbox_id?: int|null, account_id?: int|null, rate?: mixed,
     *               reference_type?: string|null, reference_id?: int|null, notes?: string|null}  $data
     */
    public function handle(array $data, ?Voucher $voucher = null, ?int $branchId = null): Voucher
    {
        return DB::transaction(function () use ($data, $voucher, $branchId) {
            if ($voucher !== null) {
                $voucher = $this->lockInStatus($voucher, DocumentStatus::Draft);
            }

            $type = VoucherType::from($data['type']);
            $cashbox = Cashbox::query()->findOrFail($data['cashbox_id']);
            $amount = Money::of((string) $data['amount']);
            $date = CarbonImmutable::parse($data['date']);

            if (! $amount->isPositive()) {
                throw BusinessRuleException::make('vouchers.errors.amount');
            }

            $rate = $this->rates->isBase($cashbox->currency_id)
                ? Money::rate(1)
                : (empty($data['rate']) ? $this->rates->rateFor($cashbox->currency_id, $date) : Money::rate((string) $data['rate']));

            $partyId = $data['party_id'] ?? null;
            $accountId = $data['account_id'] ?? null;
            $toCashboxId = null;

            if ($type === VoucherType::Transfer) {
                $to = Cashbox::query()->findOrFail($data['to_cashbox_id'] ?? 0);
                if ($to->id === $cashbox->id) {
                    throw BusinessRuleException::make('vouchers.errors.same_cashbox');
                }
                if ($to->currency_id !== $cashbox->currency_id) {
                    throw BusinessRuleException::make('vouchers.errors.transfer_currency');
                }
                $toCashboxId = $to->id;
                $partyId = null;
                $accountId = null;
            } elseif (in_array($type, [VoucherType::Receipt, VoucherType::Payment], true)) {
                $account = Account::query()->find($accountId);
                if ($account === null || ! $account->isPostable()) {
                    throw BusinessRuleException::make('vouchers.errors.account');
                }
                if ($partyId === null && in_array($account->id, $this->accounts->partyAccountIds(), true)) {
                    throw BusinessRuleException::make('vouchers.errors.party_required');
                }
                if ($account->id === $this->accounts->idFor(AccountRole::OwnersPayable) && ! $this->rates->isBase($cashbox->currency_id)) {
                    throw BusinessRuleException::make('ownership.errors.base_cashbox');
                }
            } else {
                throw BusinessRuleException::make('vouchers.errors.type');
            }

            $voucher ??= new Voucher([
                'status' => DocumentStatus::Draft,
                'branch_id' => $branchId ?? Auth::user()->branch_id ?? $cashbox->branch_id,
            ]);

            $voucher->fill([
                'type' => $type,
                'date' => $date->toDateString(),
                'party_id' => $partyId,
                'cashbox_id' => $cashbox->id,
                'to_cashbox_id' => $toCashboxId,
                'account_id' => $accountId,
                'amount' => (string) $amount,
                'currency_id' => $cashbox->currency_id,
                'rate' => (string) $rate,
                'amount_base' => (string) Money::toBase($amount, $rate),
                'description' => $data['description'],
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
            $voucher->save();

            return $voucher;
        });
    }
}
