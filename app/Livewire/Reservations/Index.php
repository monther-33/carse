<?php

namespace App\Livewire\Reservations;

use App\Actions\Reservations\CreateReservation;
use App\Actions\Reservations\EndReservation;
use App\Enums\ReservationStatus;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Cashbox;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    #[Url]
    public string $status = 'active';

    public bool $showForm = false;

    /** @var array<string, mixed> */
    public array $form = [];

    public bool $showCancel = false;

    public ?int $cancelId = null;

    public string $reason = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Reservation::class);
    }

    public function create(): void
    {
        $this->authorize('create', Reservation::class);
        $this->form = ['vehicle_id' => null, 'party_id' => null, 'cashbox_id' => null, 'deposit' => '', 'rate' => '',
            'expires_at' => now()->addDays(7)->toDateString(), 'notes' => ''];
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(CreateReservation $action): void
    {
        $this->authorize('create', Reservation::class);

        $data = $this->validate([
            'form.vehicle_id' => ['required', 'exists:vehicles,id'],
            'form.party_id' => ['required', 'exists:parties,id'],
            'form.cashbox_id' => ['required', Rule::exists('cashboxes', 'id')->where('is_active', true)],
            'form.deposit' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'form.rate' => ['nullable', 'numeric', 'gt:0', 'decimal:0,6'],
            'form.expires_at' => ['required', 'date', 'after_or_equal:today'],
            'form.notes' => ['nullable', 'string', 'max:1000'],
        ])['form'];

        if ($this->attempt(fn () => $action->handle($data), 'form.vehicle_id') !== null) {
            $this->showForm = false;
            $this->notify(__('reservations.created'));
        }
    }

    public function openCancel(int $id): void
    {
        $this->authorize('cancel', Reservation::query()->findOrFail($id));
        $this->cancelId = $id;
        $this->reset('reason');
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function cancel(EndReservation $action): void
    {
        $reservation = Reservation::query()->findOrFail($this->cancelId);
        $this->authorize('cancel', $reservation);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($this->attempt(fn () => $action->cancel($reservation, $this->reason), 'reason') !== null) {
            $this->showCancel = false;
            $this->notify(__('reservations.cancelled'));
        }
    }

    public function render(): View
    {
        return view('livewire.reservations.index', [
            'reservations' => Reservation::query()
                ->with(['vehicle.brand', 'vehicle.carModel', 'party', 'salesperson', 'voucher', 'currency'])
                ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
                ->latest('date')->latest('id')
                ->paginate(20),
            'statuses' => ReservationStatus::cases(),
            'cashboxes' => Cashbox::query()->where('is_active', true)->with('currency')->orderBy('name')->get(),
        ])->title(__('app.nav.reservations'));
    }
}
