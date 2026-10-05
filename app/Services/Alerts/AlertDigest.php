<?php

namespace App\Services\Alerts;

use App\Enums\InstallmentStatus;
use App\Models\Installment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Support\Money;
use App\Support\Settings;
use Carbon\CarbonImmutable;

/**
 * What a user should be warned about today (spec phase 5): installments due this week and
 * overdue, reservations ending within three days, and vehicles in stock past the stale
 * thresholds. Each section is included only when the user may see it and it is not empty.
 */
class AlertDigest
{
    public const RESERVATION_DAYS = 3;

    public const INSTALLMENT_DAYS = 7;

    public function __construct(private readonly Settings $settings) {}

    /**
     * @return list<array{key: string, count: int, amount?: string, days?: int, route: string}>
     */
    public function for(User $user, ?CarbonImmutable $today = null): array
    {
        $today ??= CarbonImmutable::today();
        $sections = [];

        if ($user->canAny(['vouchers.view', 'sales.view_all', 'reports.financial'])) {
            $open = fn () => Installment::query()
                ->whereIn('status', [InstallmentStatus::Pending, InstallmentStatus::Partial])
                ->whereHas('plan.invoice', fn ($q) => $q->posted());

            $overdue = $open()->whereDate('due_date', '<', $today)->get(['amount', 'paid_amount']);
            $week = $open()->whereBetween('due_date', [$today->toDateString(), $today->addDays(self::INSTALLMENT_DAYS)->toDateString()])->get(['amount', 'paid_amount']);

            if ($overdue->isNotEmpty()) {
                $sections[] = ['key' => 'installments_overdue', 'count' => $overdue->count(), 'amount' => (string) Money::sum($overdue->map->remaining()->all()), 'route' => 'installments.index'];
            }
            if ($week->isNotEmpty()) {
                $sections[] = ['key' => 'installments_week', 'count' => $week->count(), 'amount' => (string) Money::sum($week->map->remaining()->all()), 'days' => self::INSTALLMENT_DAYS, 'route' => 'installments.index'];
            }
        }

        if ($user->can('reservations.view')) {
            $expiring = Reservation::query()->active()
                ->whereDate('expires_at', '<=', $today->addDays(self::RESERVATION_DAYS))
                ->count();

            if ($expiring > 0) {
                $sections[] = ['key' => 'reservations_expiring', 'count' => $expiring, 'days' => self::RESERVATION_DAYS, 'route' => 'reservations.index'];
            }
        }

        if ($user->canAny(['reports.inventory', 'vehicles.update'])) {
            $warning = $this->settings->int('inventory.stale_days_warning', 60);
            $critical = $this->settings->int('inventory.stale_days_critical', 90);
            $stale = fn () => Vehicle::query()->inStock()->whereNotNull('received_at');

            $criticalCount = $stale()->whereDate('received_at', '<', $today->subDays($critical))->count();
            $warningCount = $stale()->whereDate('received_at', '<', $today->subDays($warning))->count() - $criticalCount;

            if ($criticalCount > 0) {
                $sections[] = ['key' => 'stale_critical', 'count' => $criticalCount, 'days' => $critical, 'route' => 'vehicles.index'];
            }
            if ($warningCount > 0) {
                $sections[] = ['key' => 'stale_warning', 'count' => $warningCount, 'days' => $warning, 'route' => 'vehicles.index'];
            }
        }

        return $sections;
    }
}
