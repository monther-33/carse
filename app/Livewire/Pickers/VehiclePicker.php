<?php

namespace App\Livewire\Pickers;

use App\Enums\VehicleStatus;
use App\Livewire\Concerns\SearchesVehicles;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Vehicle selector: type to search by VIN / plate / brand / model, or open the search modal
 * (filters by brand, model, colour, year, status and price, with a details view):
 * <livewire:pickers.vehicle-picker wire:model="vehicle_id" :statuses="['available']" />
 */
class VehiclePicker extends Component
{
    use SearchesVehicles;

    #[Modelable]
    public ?int $value = null;

    /** @var list<string> VehicleStatus values allowed; empty = vehicles in stock or sold. */
    public array $statuses = [];

    public string $search = '';

    protected function allowedStatuses(): array
    {
        return $this->statuses !== []
            ? $this->statuses
            : array_values(array_map(fn (VehicleStatus $s) => $s->value, array_filter(VehicleStatus::cases(), fn (VehicleStatus $s) => $s->isInStock() || $s === VehicleStatus::Sold)));
    }

    public function choose(int $id): void
    {
        // Only a vehicle this picker may offer can be chosen.
        $this->value = Vehicle::query()->whereIn('status', $this->allowedStatuses())->whereKey($id)->value('id');
        $this->search = '';
        $this->showSearch = false;
        $this->previewId = null;
    }

    public function clear(): void
    {
        $this->value = null;
    }

    public function render(): View
    {
        return view('livewire.pickers.vehicle-picker', [
            'selected' => $this->value ? Vehicle::query()->with(['brand', 'carModel'])->find($this->value) : null,
            'results' => $this->search !== ''
                ? Vehicle::query()->with(['brand', 'carModel'])->whereIn('status', $this->allowedStatuses())->search($this->search)->limit(8)->get()
                : collect(),
            'chooses' => true,
        ] + $this->vehicleSearchData());
    }
}
