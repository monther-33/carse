<?php

namespace App\Livewire\Pickers;

use App\Enums\VehicleStatus;
use App\Livewire\Concerns\SearchesVehicles;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Topbar "find a vehicle": the vehicle search modal over every vehicle except drafts, opening
 * the vehicle card. Shown to users who may view vehicles.
 */
class VehicleFinder extends Component
{
    use SearchesVehicles;

    protected function allowedStatuses(): array
    {
        abort_unless(auth()->user()->can('vehicles.view'), 403);

        return array_values(array_map(fn (VehicleStatus $s) => $s->value, array_filter(VehicleStatus::cases(), fn (VehicleStatus $s) => $s !== VehicleStatus::Pending)));
    }

    public function render(): View
    {
        return view('livewire.pickers.vehicle-finder', ['chooses' => false] + $this->vehicleSearchData());
    }
}
