<?php

namespace App\Actions\Sales;

use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\AccountRole;
use App\Enums\CommissionStatus;
use App\Enums\VoucherType;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Commission;
use App\Models\User;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Pays a salesperson's accrued commissions with one posted payment voucher
 * (Dr accrued commissions / Cr cashbox) and marks them paid.
 */
class PayCommissions
{
    public function __construct(
        private readonly SaveVoucher $saveVoucher,
        private readonly PostVoucher $postVoucher,
        private readonly AccountResolver $accounts,
        private readonly ExchangeRateService $rates,
    ) {}

    /**
     * @param  list<int>  $commissionIds
     */
    public function handle(User $salesperson, array $commissionIds, Cashbox $cashbox, ?string $date = null): Voucher
    {
        return DB::transaction(function () use ($salesperson, $commissionIds, $cashbox, $date) {
            $commissions = Commission::query()
                ->whereKey($commissionIds)
                ->where('user_id', $salesperson->id)
                ->where('status', CommissionStatus::Accrued)
                ->lockForUpdate()
                ->get();

            if ($commissions->isEmpty() || $commissions->count() !== count(array_unique($commissionIds))) {
                throw BusinessRuleException::make('commissions.errors.selection');
            }
            if (! $this->rates->isBase($cashbox->currency_id)) {
                throw BusinessRuleException::make('commissions.errors.currency');
            }

            $voucher = $this->saveVoucher->handle([
                'type' => VoucherType::Payment->value,
                'date' => $date ?? now()->toDateString(),
                'cashbox_id' => $cashbox->id,
                'account_id' => $this->accounts->idFor(AccountRole::AccruedCommissions),
                'amount' => (string) Money::sum($commissions->pluck('amount')->all()),
                'description' => __('commissions.payment_description', ['name' => $salesperson->name]),
            ]);
            $this->postVoucher->handle($voucher);

            Commission::query()->whereKey($commissions->pluck('id'))->update([
                'status' => CommissionStatus::Paid,
                'voucher_id' => $voucher->id,
            ]);

            return $voucher;
        });
    }
}
