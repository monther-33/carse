<?php

namespace App\Support;

use App\Enums\AccountRole;
use App\Enums\CommissionStatus;
use App\Enums\InstallmentStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Commission;
use App\Models\Installment;
use App\Models\Reservation;
use App\Services\Accounting\AccountResolver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Optional parts of the system the showroom can switch off (owner's request), with what goes
 * with each one. A disabled feature disappears from menus, forms, dashboard, alerts and
 * reports, and the server refuses it; its data is kept and comes back when switched on.
 *
 * A feature cannot be switched off while it still has open business (blockers()).
 */
class Features
{
    public const RESERVATIONS = 'reservations';   // reservations and deposits

    public const INSTALLMENTS = 'installments';   // installment sales, guarantors, schedules, collections

    public const TRADE_IN = 'trade_in';           // the customer's car taken in part payment

    public const COMMISSIONS = 'commissions';     // salesperson commissions

    public const IMPORTS = 'imports';             // Excel import of opening data

    public const ALL = [self::RESERVATIONS, self::INSTALLMENTS, self::TRADE_IN, self::COMMISSIONS, self::IMPORTS];

    public function __construct(
        private readonly Settings $settings,
        private readonly AccountResolver $accounts,
    ) {}

    public function enabled(string $feature): bool
    {
        return $this->settings->bool('features.'.$feature, true);
    }

    /** Throws when a disabled feature is used (server-side guard behind the hidden UI). */
    public function ensure(string $feature): void
    {
        if (! $this->enabled($feature)) {
            throw BusinessRuleException::make('features.errors.disabled', ['feature' => __('features.names.'.$feature)]);
        }
    }

    /**
     * Why the feature cannot be switched off now, or null when it can.
     */
    public function blocker(string $feature): ?string
    {
        return match ($feature) {
            self::RESERVATIONS => $this->reservationsBlocker(),
            self::INSTALLMENTS => ($n = Installment::query()->whereIn('status', [InstallmentStatus::Pending, InstallmentStatus::Partial])
                ->whereHas('plan.invoice', fn ($q) => $q->posted())->count()) > 0
                    ? __('features.blockers.installments', ['count' => $n]) : null,
            self::COMMISSIONS => ($n = Commission::query()->where('status', CommissionStatus::Accrued)->count()) > 0
                ? __('features.blockers.commissions', ['count' => $n]) : null,
            default => null,
        };
    }

    private function reservationsBlocker(): ?string
    {
        if (($n = Reservation::query()->active()->count()) > 0) {
            return __('features.blockers.reservations', ['count' => $n]);
        }

        $deposits = Money::of((string) DB::table('journal_lines')
            ->where('account_id', $this->accounts->idFor(AccountRole::CustomerDeposits))
            ->sum(DB::raw('credit_base - debit_base')));

        return $deposits->isZero() ? null : __('features.blockers.deposits', ['amount' => Money::format($deposits)]);
    }

    public function set(string $feature, bool $on): void
    {
        abort_unless(in_array($feature, self::ALL, true), 404);

        if (! $on && ($reason = $this->blocker($feature)) !== null) {
            throw BusinessRuleException::make('features.errors.blocked', ['reason' => $reason]);
        }

        $this->settings->set(['features.'.$feature => $on ? '1' : '0']);

        activity('System')->causedBy(Auth::user())->event($on ? 'feature_enabled' : 'feature_disabled')
            ->withProperties(['feature' => $feature])->log($on ? 'feature_enabled' : 'feature_disabled');
    }
}
