<?php

namespace App\Livewire\Reservations;

use App\Actions\Reservations\CreateReservation;
use App\Actions\Reservations\EndReservation;
use App\Actions\Reservations\ManageReservation;
use App\Actions\Reservations\SettleDeposit;
use App\Enums\ReservationStatus;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Cashbox;
use App\Models\Reservation;
use App\Services\Sales\DepositService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Reservations with flexible deposits: add to the deposit, extend, move to another car,
 * and on cancellation (or any time after) refund and/or forfeit any part of the deposit;
 * what is left stays as the customer's credit for a later sale.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    #[Url]
    public string $status = 'active';

    public bool $showForm = false;

    /** @var array<string, mixed> */
    public array $form = [];

    /** extend | vehicle | top_up | cancel | settle */
    public ?string $action = null;

    public ?int $targetId = null;

    /** @var array<string, mixed> */
    public array $input = [];

    public function mount(): void
    {
        $this->authorize('viewAny', Reservation::class);
    }

    private function activeCashboxRule(): Exists
    {
        return Rule::exists('cashboxes', 'id')->where('is_active', true);
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
            'form.cashbox_id' => ['required', $this->activeCashboxRule()],
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

    /** Open one of the per-reservation dialogs. */
    public function open(string $action, int $id, SettleDeposit $settle): void
    {
        $reservation = Reservation::query()->findOrFail($id);
        $this->authorize(match ($action) {
            'cancel' => 'cancel',
            'settle' => 'settle',
            default => 'manage',
        }, $reservation);

        $this->action = $action;
        $this->targetId = $id;
        $this->input = [
            'expires_at' => $reservation->expires_at->copy()->addDays(7)->toDateString(),
            'vehicle_id' => null,
            'amount' => '',
            'cashbox_id' => null,
            'refund' => '',
            'forfeit' => '',
            'reason' => '',
            'limit' => (string) $settle->limit($reservation),
        ];
        $this->resetValidation();
    }

    public function closeDialog(): void
    {
        $this->action = null;
    }

    public function submit(ManageReservation $manage, EndReservation $end, SettleDeposit $settle): void
    {
        $reservation = Reservation::query()->findOrFail($this->targetId);
        $amountRules = ['nullable', 'numeric', 'min:0', 'decimal:0,3'];

        $done = match ($this->action) {
            'extend' => $this->run('manage', $reservation, ['input.expires_at' => ['required', 'date', 'after_or_equal:today']],
                fn () => $manage->extend($reservation, $this->input['expires_at'])),
            'vehicle' => $this->run('manage', $reservation, ['input.vehicle_id' => ['required', 'exists:vehicles,id']],
                fn () => $manage->changeVehicle($reservation, (int) $this->input['vehicle_id'])),
            'top_up' => $this->run('manage', $reservation, [
                'input.amount' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
                'input.cashbox_id' => ['required', $this->activeCashboxRule()],
            ], fn () => $manage->topUp($reservation, (int) $this->input['cashbox_id'], $this->input['amount'])),
            'cancel' => $this->run('cancel', $reservation, [
                'input.reason' => ['required', 'string', 'max:255'],
                'input.refund' => $amountRules, 'input.forfeit' => $amountRules,
                'input.cashbox_id' => ['nullable', $this->activeCashboxRule()],
            ], fn () => $end->cancel($reservation, $this->input['reason'], $this->input['refund'], $this->input['cashbox_id'] ?: null, $this->input['forfeit'])),
            'settle' => $this->run('settle', $reservation, [
                'input.reason' => ['nullable', 'string', 'max:255'],
                'input.refund' => $amountRules, 'input.forfeit' => $amountRules,
                'input.cashbox_id' => ['nullable', $this->activeCashboxRule()],
            ], fn () => $settle->handle($reservation, $this->input['refund'], $this->input['cashbox_id'] ?: null, $this->input['forfeit'], (string) $this->input['reason'])),
            default => false,
        };

        if ($done) {
            $this->action = null;
            $this->notify(__('app.saved'));
        }
    }

    /**
     * @param  array<string, mixed>  $rules
     */
    private function run(string $ability, Reservation $reservation, array $rules, callable $callback): bool
    {
        $this->authorize($ability, $reservation);
        $this->validate($rules);

        return $this->attempt($callback, 'input') !== null;
    }

    public function render(DepositService $deposits): View
    {
        $reservations = Reservation::query()
            ->with(['vehicle.brand', 'vehicle.carModel', 'party', 'salesperson', 'voucher', 'currency'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->latest('date')->latest('id')
            ->paginate(20);

        return view('livewire.reservations.index', [
            'reservations' => $reservations,
            'remaining' => $reservations->getCollection()->mapWithKeys(fn (Reservation $r) => [$r->id => $deposits->remaining($r)]),
            'statuses' => ReservationStatus::cases(),
            'cashboxes' => Cashbox::query()->where('is_active', true)->with('currency')->orderBy('name')->get(),
            'target' => $this->targetId ? Reservation::query()->with(['vehicle', 'currency'])->find($this->targetId) : null,
        ])->title(__('app.nav.reservations'));
    }
}
