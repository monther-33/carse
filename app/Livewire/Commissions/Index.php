<?php

namespace App\Livewire\Commissions;

use App\Actions\Sales\PayCommissions;
use App\Enums\CommissionStatus;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Cashbox;
use App\Models\Commission;
use App\Models\User;
use App\Support\Money;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Commissions: sales staff see their own; with commissions.view_all + vouchers.approve
 * a user can pay a salesperson's selected accrued commissions.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    #[Url]
    public string $status = 'accrued';

    public ?int $userId = null;

    /** @var list<int> */
    public array $selected = [];

    public ?int $cashbox_id = null;

    public function mount(): void
    {
        $this->authorize('commissions.view');
    }

    private function canPay(): bool
    {
        return auth()->user()->can('commissions.view_all') && auth()->user()->can('vouchers.approve');
    }

    public function pay(PayCommissions $action): void
    {
        abort_unless($this->canPay(), 403);

        $this->validate([
            'userId' => ['required', 'exists:users,id'],
            'selected' => ['required', 'array', 'min:1'],
            'cashbox_id' => ['required', 'in:'.implode(',', Cashbox::query()->visibleTo(auth()->user())->pluck('id')->all())],
        ]);

        $voucher = $this->attempt(fn () => $action->handle(
            User::query()->findOrFail($this->userId),
            array_map('intval', $this->selected),
            Cashbox::query()->findOrFail($this->cashbox_id),
        ), 'selected');

        if ($voucher !== null) {
            $this->selected = [];
            $this->notify(__('commissions.paid_ok', ['number' => $voucher->number]));
        }
    }

    public function render(): View
    {
        $query = Commission::query()
            ->visibleTo(auth()->user())
            ->with(['invoice.party', 'item.vehicle.brand', 'item.vehicle.carModel', 'user', 'voucher'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->userId, fn ($q) => $q->where('user_id', $this->userId));

        return view('livewire.commissions.index', [
            'commissions' => $query->clone()->latest('id')->paginate(30),
            'total' => Money::sum($query->clone()->pluck('amount')->all()),
            'statuses' => CommissionStatus::cases(),
            'users' => auth()->user()->can('commissions.view_all')
                ? User::query()->whereIn('id', Commission::query()->select('user_id'))->orderBy('name')->get()
                : collect(),
            'cashboxes' => Cashbox::query()->visibleTo(auth()->user())->where('is_active', true)->whereHas('currency', fn ($q) => $q->where('is_base', true))->orderBy('name')->get(),
            'canPay' => $this->canPay(),
        ])->title(__('app.nav.commissions'));
    }
}
