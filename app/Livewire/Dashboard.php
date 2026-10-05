<?php

namespace App\Livewire;

use App\Reports\DashboardMetrics;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render(DashboardMetrics $metrics, Settings $settings): View
    {
        $user = auth()->user();

        return view('livewire.dashboard', [
            'today' => $metrics->sales($user, CarbonImmutable::today()),
            'month' => $metrics->sales($user, CarbonImmutable::today()->startOfMonth()),
            'cashboxes' => $metrics->cashboxes($user),
            'stock' => $metrics->stock($user),
            'installments' => $metrics->installments($user),
            'stale' => $metrics->staleVehicles($user),
            'staleDays' => $settings->int('inventory.stale_days_warning', 60),
            'reservations' => $metrics->expiringReservations($user),
        ])->title(__('app.nav.dashboard'));
    }
}
