<?php

namespace App\Reports;

use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\VehicleStatus;
use App\Models\Cashbox;
use App\Models\Installment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Support\Features;
use App\Support\Money;
use App\Support\Settings;
use Brick\Math\BigDecimal;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard figures. Every widget returns null when the user may not see it.
 */
class DashboardMetrics
{
    public function __construct(private readonly Settings $settings) {}

    /**
     * Sales count, revenue and (with view_cost) profit since a date. Sales staff see their own.
     *
     * @return array{count: int, revenue: BigDecimal, profit: BigDecimal|null}|null
     */
    public function sales(User $user, CarbonImmutable $from): ?array
    {
        if (! $user->canAny(['sales.view', 'sales.view_all', 'reports.sales'])) {
            return null;
        }

        $row = DB::table('sales_invoice_items as i')
            ->join('sales_invoices as s', 's.id', '=', 'i.invoice_id')
            ->where('s.status', DocumentStatus::Posted->value)
            ->whereNull('i.return_id')
            ->where('s.date', '>=', $from->toDateString())
            ->when(! $user->canAny(['sales.view_all', 'reports.sales']), fn ($q) => $q->where('s.salesperson_id', $user->id))
            ->selectRaw('COUNT(*) AS n, COALESCE(SUM(i.net_base), 0) AS revenue, COALESCE(SUM(i.net_base - i.cost_snapshot - i.commission), 0) AS profit')
            ->first();

        return [
            'count' => (int) $row->n,
            'revenue' => Money::of((string) $row->revenue),
            'profit' => $user->can('vehicles.view_cost') ? Money::of((string) $row->profit) : null,
        ];
    }

    /**
     * Balance of each cashbox the user may see, in the cashbox currency.
     *
     * @return Collection<int, array{name: string, currency: string, balance: BigDecimal}>|null
     */
    public function cashboxes(User $user): ?Collection
    {
        if (! $user->can('cashboxes.view')) {
            return null;
        }

        $cashboxes = Cashbox::query()->visibleTo($user)->where('is_active', true)->with('currency')->orderBy('name')->get();
        $balances = DB::table('journal_lines')->whereIn('account_id', $cashboxes->pluck('account_id'))
            ->groupBy('account_id')->selectRaw('account_id, SUM(debit - credit) AS b')->pluck('b', 'account_id');

        return $cashboxes->map(fn (Cashbox $c) => [
            'name' => $c->name,
            'currency' => $c->currency->code,
            'balance' => Money::of((string) ($balances[$c->account_id] ?? '0')),
        ]);
    }

    /**
     * @return array{count: int, asking: BigDecimal, cost: BigDecimal|null, in_stock: int}|null
     */
    public function stock(User $user): ?array
    {
        if (! $user->can('vehicles.view')) {
            return null;
        }

        $available = Vehicle::query()->where('status', VehicleStatus::Available)->get(['asking_price', 'total_cost']);

        return [
            'count' => $available->count(),
            'asking' => Money::sum($available->pluck('asking_price')->filter()->all()),
            'cost' => $user->can('vehicles.view_cost') ? Money::sum($available->pluck('total_cost')->all()) : null,
            'in_stock' => Vehicle::query()->inStock()->count(),
        ];
    }

    /**
     * @return array{week_count: int, week_amount: BigDecimal, overdue_count: int, overdue_amount: BigDecimal}|null
     */
    public function installments(User $user): ?array
    {
        if (! $user->canAny(['vouchers.view', 'sales.view_all', 'reports.financial']) || ! app(Features::class)->enabled(Features::INSTALLMENTS)) {
            return null;
        }

        $open = fn () => Installment::query()
            ->whereIn('status', [InstallmentStatus::Pending, InstallmentStatus::Partial])
            ->whereHas('plan.invoice', fn ($q) => $q->posted());

        $sum = fn ($query) => Money::sum($query->get(['amount', 'paid_amount'])->map(fn ($i) => $i->remaining())->all());
        $week = $open()->whereBetween('due_date', [today()->toDateString(), today()->addDays(7)->toDateString()]);
        $overdue = $open()->whereDate('due_date', '<', today());

        return [
            'week_count' => $week->clone()->count(),
            'week_amount' => $sum($week),
            'overdue_count' => $overdue->clone()->count(),
            'overdue_amount' => $sum($overdue),
        ];
    }

    /**
     * In-stock vehicles older than the stale-stock warning, oldest first.
     *
     * @return Collection<int, Vehicle>|null
     */
    public function staleVehicles(User $user, int $limit = 8): ?Collection
    {
        if (! $user->can('vehicles.view')) {
            return null;
        }

        $days = $this->settings->int('inventory.stale_days_warning', 60);

        return Vehicle::query()->with(['brand', 'carModel'])->inStock()
            ->whereDate('received_at', '<', today()->subDays($days))
            ->orderBy('received_at')->limit($limit)->get();
    }

    /**
     * Active reservations ending within three days.
     *
     * @return Collection<int, Reservation>|null
     */
    public function expiringReservations(User $user): ?Collection
    {
        if (! $user->can('reservations.view') || ! app(Features::class)->enabled(Features::RESERVATIONS)) {
            return null;
        }

        return Reservation::query()->active()->with(['vehicle.brand', 'vehicle.carModel', 'party'])
            ->whereDate('expires_at', '<=', today()->addDays(3))
            ->orderBy('expires_at')->get();
    }
}
