<?php

namespace App\Livewire\Pickers;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Searchable vehicle selector by VIN / plate / brand / model:
 * <livewire:pickers.vehicle-picker wire:model="vehicle_id" :statuses="['available']" />
 */
class VehiclePicker extends Component
{
    #[Modelable]
    public ?int $value = null;

    /** @var list<string> VehicleStatus values allowed; empty = vehicles in stock or sold. */
    public array $statuses = [];

    public string $search = '';

    public function choose(int $id): void
    {
        $this->value = $id;
        $this->search = '';
    }

    public function clear(): void
    {
        $this->value = null;
    }

    public function render(): View
    {
        $statuses = $this->statuses !== []
            ? $this->statuses
            : array_map(fn (VehicleStatus $s) => $s->value, array_filter(VehicleStatus::cases(), fn (VehicleStatus $s) => $s->isInStock() || $s === VehicleStatus::Sold));

        return view('livewire.pickers.vehicle-picker', [
            'selected' => $this->value ? Vehicle::query()->with(['brand', 'carModel'])->find($this->value) : null,
            'results' => $this->search !== ''
                ? Vehicle::query()->with(['brand', 'carModel'])->whereIn('status', $statuses)->search($this->search)->limit(8)->get()
                : collect(),
        ]);
    }
}
