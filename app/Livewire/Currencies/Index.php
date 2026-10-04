<?php

namespace App\Livewire\Currencies;

use App\Livewire\Concerns\Notifies;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Services\Currency\ExchangeRateService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Currencies (currencies.manage) and their daily exchange rates (exchange_rates.manage).
 * The base currency is fixed by the seeder and cannot be changed here.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies;

    public ?int $selectedId = null;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $code = '';

    public string $name = '';

    public string $symbol = '';

    public int $decimals = 2;

    public bool $is_active = true;

    public string $rate_date = '';

    public string $rate = '';

    public function mount(): void
    {
        abort_unless(auth()->user()->canAny(['currencies.manage', 'exchange_rates.manage']), 403);

        $this->selectedId = Currency::query()->where('is_base', false)->orderBy('code')->value('id');
        $this->rate_date = now()->toDateString();
    }

    public function select(int $id): void
    {
        $this->selectedId = $id;
        $this->resetValidation();
    }

    public function create(): void
    {
        $this->authorize('create', Currency::class);
        $this->reset(['editingId', 'code', 'name', 'symbol', 'decimals', 'is_active']);
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $currency = Currency::query()->findOrFail($id);
        $this->authorize('update', $currency);

        $this->resetValidation();
        $this->editingId = $currency->id;
        $this->code = $currency->code;
        $this->name = $currency->name;
        $this->symbol = $currency->symbol;
        $this->decimals = $currency->decimals;
        $this->is_active = $currency->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $currency = $this->editingId ? Currency::query()->findOrFail($this->editingId) : null;
        $currency ? $this->authorize('update', $currency) : $this->authorize('create', Currency::class);

        $data = $this->validate([
            'code' => ['required', 'string', 'size:3', 'alpha', Rule::unique('currencies', 'code')->ignore($this->editingId)],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:10'],
            'decimals' => ['required', 'integer', 'between:0,3'],
            'is_active' => ['boolean'],
        ]);
        $data['code'] = strtoupper($data['code']);

        if ($currency?->is_base) {
            // The base currency's code and decimals are part of every posted amount.
            unset($data['code'], $data['decimals']);
            $data['is_active'] = true;
        }

        $currency ? $currency->update($data) : Currency::query()->create($data + ['is_base' => false]);

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function saveRate(ExchangeRateService $rates): void
    {
        $this->authorize('create', ExchangeRate::class);

        $this->validate([
            'selectedId' => ['required', 'exists:currencies,id'],
            'rate_date' => ['required', 'date'],
            'rate' => ['required', 'numeric', 'gt:0', 'decimal:0,6'],
        ]);

        $currency = Currency::query()->findOrFail($this->selectedId);
        if ($currency->is_base) {
            $this->addError('rate', __('app.currencies.base_rate_fixed'));

            return;
        }

        $rates->setRate($currency, CarbonImmutable::parse($this->rate_date), $this->rate);

        $this->rate = '';
        $this->notify(__('app.saved'));
    }

    public function render(): View
    {
        $currencies = Currency::query()->orderByDesc('is_base')->orderBy('code')->get();

        return view('livewire.currencies.index', [
            'currencies' => $currencies,
            'selected' => $currencies->firstWhere('id', $this->selectedId),
            'rates' => $this->selectedId
                ? ExchangeRate::query()->with('creator')->where('currency_id', $this->selectedId)->orderByDesc('date')->limit(30)->get()
                : collect(),
        ])->title(__('app.nav.currencies'));
    }
}
