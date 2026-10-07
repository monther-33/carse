<?php

namespace App\Livewire\Consignments;

use App\Actions\Ownership\ReturnToOwner;
use App\Enums\OwnershipKind;
use App\Enums\OwnershipStatus;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\VehicleOwnership;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Cars with owners: consignment cars (received from their owners) and partnership cars
 * (bought with partners), with their owners, agreement and status. A consignment car
 * still in the showroom can be handed back to its owners.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    #[Url]
    public string $status = 'active';

    #[Url]
    public string $kind = '';

    #[Url]
    public string $q = '';

    public ?int $returnId = null;

    public string $reason = '';

    public function mount(): void
    {
        $this->authorize('consignments.view');
    }

    public function updating(string $name): void
    {
        if (in_array($name, ['status', 'kind', 'q'], true)) {
            $this->resetPage();
        }
    }

    public function openReturn(int $id): void
    {
        $this->authorize('consignments.manage');
        $this->returnId = $id;
        $this->reason = '';
        $this->resetValidation();
    }

    public function closeReturn(): void
    {
        $this->returnId = null;
    }

    public function returnToOwner(ReturnToOwner $action): void
    {
        $this->authorize('consignments.manage');
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        $ownership = VehicleOwnership::query()->findOrFail((int) $this->returnId);
        if ($this->attempt(fn () => $action->handle($ownership, $this->reason), 'reason') !== null) {
            $this->returnId = null;
            $this->notify(__('ownership.returned_ok'));
        }
    }

    public function render(): View
    {
        $ownerships = VehicleOwnership::query()
            ->with(['vehicle.brand', 'vehicle.carModel', 'owners.party'])
            ->when($this->status !== '', fn (Builder $q) => $q->where('status', $this->status))
            ->when($this->kind !== '', fn (Builder $q) => $q->where('kind', $this->kind))
            ->when(trim($this->q) !== '', function (Builder $q) {
                $term = trim($this->q);
                $q->where(fn (Builder $w) => $w
                    ->where('number', 'like', "%{$term}%")
                    ->orWhereHas('vehicle', fn (Builder $v) => $v->search($term))
                    ->orWhereHas('owners.party', fn (Builder $p) => $p->where('name', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%")));
            })
            ->latest('received_at')->latest('id')
            ->paginate(20);

        return view('livewire.consignments.index', [
            'ownerships' => $ownerships,
            'statuses' => OwnershipStatus::cases(),
            'kinds' => OwnershipKind::cases(),
            'returning' => $this->returnId ? VehicleOwnership::query()->with('vehicle')->find($this->returnId) : null,
        ])->title(__('app.nav.consignments'));
    }
}
