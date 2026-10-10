<?php

namespace App\Livewire\Vehicles;

use App\Enums\VehicleStatus;
use App\Models\Brand;
use App\Models\Vehicle;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Stock list with ageing. Cost columns render only for vehicles.view_cost.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    /** "stock" = every in-stock status, otherwise a VehicleStatus value or "" for all. */
    #[Url]
    public string $status = 'stock';

    #[Url]
    public string $brand = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Vehicle::class);
    }

    public function updating(string $name): void
    {
        if (in_array($name, ['search', 'status', 'brand'], true)) {
            $this->resetPage();
        }
    }

    public function render(Settings $settings): View
    {
        $vehicles = Vehicle::query()
            ->with(['brand', 'carModel', 'color', 'location', 'ownership'])
            ->where('status', '!=', VehicleStatus::Pending)
            ->when($this->status === 'stock', fn ($q) => $q->inStock())
            ->when(! in_array($this->status, ['stock', ''], true), fn ($q) => $q->where('status', $this->status))
            ->when($this->brand !== '', fn ($q) => $q->where('brand_id', $this->brand))
            ->when($this->search !== '', fn ($q) => $q->search($this->search))
            ->orderBy('received_at')
            ->paginate(25);

        return view('livewire.vehicles.index', [
            'vehicles' => $vehicles,
            'statuses' => array_filter(VehicleStatus::cases(), fn (VehicleStatus $s) => $s !== VehicleStatus::Pending),
            'brands' => Brand::query()->orderBy('name')->get(),
            'canViewCost' => auth()->user()->can('viewCost', Vehicle::class),
            'staleWarning' => $settings->int('inventory.stale_days_warning', 60),
            'staleCritical' => $settings->int('inventory.stale_days_critical', 90),
        ])->title(__('app.nav.vehicles'));
    }
}
