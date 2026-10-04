<?php

namespace App\Actions\Reservations;

use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\AccountRole;
use App\Enums\VoucherType;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Reservation;
use App\Models\Voucher;
use App\Services\Accounting\AccountResolver;
use App\Support\Settings;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\Auth;

/**
 * Receipt (deposit in) and payment (deposit refund) vouchers on "customer deposits",
 * referencing the reservation. Posted at once unless approval separation is on and the
 * user cannot approve vouchers — then they wait as drafts for the treasurer/accountant.
 */
class DepositVouchers
{
    public function __construct(
        private readonly SaveVoucher $save,
        private readonly PostVoucher $post,
        private readonly AccountResolver $accounts,
        private readonly Settings $settings,
    ) {}

    public function receive(Reservation $reservation, int $cashboxId, BigDecimal $amount, mixed $rate, string $description): Voucher
    {
        return $this->make(VoucherType::Receipt, $reservation, $cashboxId, $amount, $rate, $description);
    }

    public function refund(Reservation $reservation, int $cashboxId, BigDecimal $amount, string $description): Voucher
    {
        return $this->make(VoucherType::Payment, $reservation, $cashboxId, $amount, null, $description);
    }

    private function make(VoucherType $type, Reservation $reservation, int $cashboxId, BigDecimal $amount, mixed $rate, string $description): Voucher
    {
        if (Cashbox::query()->findOrFail($cashboxId)->currency_id !== $reservation->currency_id) {
            throw BusinessRuleException::make('reservations.errors.currency');
        }

        $voucher = $this->save->handle([
            'type' => $type->value,
            'date' => now()->toDateString(),
            'party_id' => $reservation->party_id,
            'cashbox_id' => $cashboxId,
            'account_id' => $this->accounts->idFor(AccountRole::CustomerDeposits),
            'amount' => (string) $amount,
            'rate' => $rate,
            'description' => $description,
            'reference_type' => $reservation->getMorphClass(),
            'reference_id' => $reservation->id,
        ]);

        $mustWait = $this->settings->bool('documents.require_approval', true) && ! Auth::user()?->can('vouchers.approve');

        return $mustWait ? $voucher : $this->post->handle($voucher);
    }
}
