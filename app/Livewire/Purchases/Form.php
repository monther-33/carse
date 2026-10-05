<?php

namespace App\Livewire\Purchases;

use App\Actions\Purchases\PostPurchaseInvoice;
use App\Actions\Purchases\SavePurchaseInvoice;
use App\Enums\FuelType;
use App\Enums\PurchaseSource;
use App\Enums\Transmission;
use App\Enums\VehicleCondition;
use App\Enums\VehicleStatus;
use App\Livewire\Concerns\AcceptsQuickCreate;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Cashbox;
use App\Models\Color;
use App\Models\Currency;
use App\Models\Location;
use App\Models\PurchaseInvoice;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Create / edit a draft purchase invoice. When approval separation is disabled in
 * settings, saving also posts it (for a user who may approve).
 */
#[Layout('layouts.app')]
class Form extends Component
{
    use AcceptsQuickCreate, HandlesBusinessErrors, Notifies;

    public ?PurchaseInvoice $invoice = null;

    public string $date = '';

    public ?int $party_id = null;

    public string $source = 'supplier';

    public ?int $currency_id = null;

    public string $rate = '1';

    public string $discount = '0';

    public string $paid = '0';

    public ?int $cashbox_id = null;

    public string $notes = '';

    /** @var list<array<string, mixed>> */
    public array $items = [];

    public function mount(?PurchaseInvoice $invoice = null): void
    {
        if ($invoice?->exists) {
            $this->authorize('update', $invoice);
            $this->invoice = $invoice;
            $this->fillFrom($invoice);

            return;
        }

        $this->authorize('create', PurchaseInvoice::class);
        $this->date = now()->toDateString();
        $this->currency_id = app(ExchangeRateService::class)->baseCurrency()->id;
        $this->addItem();
    }

    private function fillFrom(PurchaseInvoice $invoice): void
    {
        $invoice->load('items.vehicle');
        $this->date = $invoice->date->toDateString();
        $this->party_id = $invoice->party_id;
        $this->source = $invoice->source->value;
        $this->currency_id = $invoice->currency_id;
        $this->rate = rtrim(rtrim($invoice->rate, '0'), '.');
        $this->discount = (string) $invoice->discount;
        $this->paid = (string) $invoice->paid;
        $this->cashbox_id = $invoice->cashbox_id;
        $this->notes = (string) $invoice->notes;

        // Raw attributes: enums and decimals as their stored string values, ready for the form.
        $this->items = $invoice->items->map(fn ($item) => array_merge(
            array_intersect_key($item->vehicle->getAttributes(), array_flip(['vin', ...Vehicle::EDITABLE])),
            [
                'entry_status' => $item->entry_status->value,
                'price' => (string) $item->price,
            ],
        ))->all();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'vin' => '', 'brand_id' => null, 'model_id' => null, 'trim' => '', 'year' => now()->year,
            'color_id' => null, 'plate_no' => '', 'mileage' => null, 'condition' => VehicleCondition::Used->value,
            'fuel' => FuelType::Petrol->value, 'transmission' => Transmission::Automatic->value, 'origin' => '',
            'location_id' => null, 'entry_status' => VehicleStatus::Available->value,
            'price' => '', 'asking_price' => '', 'min_price' => '', 'notes' => null,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedCurrencyId(): void
    {
        $rates = app(ExchangeRateService::class);
        if ($rates->isBase((int) $this->currency_id)) {
            $this->rate = '1';

            return;
        }
        $this->attempt(fn () => $this->rate = (string) $rates->rateFor((int) $this->currency_id, CarbonImmutable::parse($this->date ?: now())), 'rate');
    }

    public function save(SavePurchaseInvoice $save, PostPurchaseInvoice $post, Settings $settings): void
    {
        $this->invoice ? $this->authorize('update', $this->invoice) : $this->authorize('create', PurchaseInvoice::class);

        $data = $this->validate([
            'date' => ['required', 'date'],
            'party_id' => ['required', 'exists:parties,id'],
            'source' => ['required', Rule::enum(PurchaseSource::class)],
            'currency_id' => ['required', 'exists:currencies,id'],
            'rate' => ['required', 'numeric', 'gt:0', 'decimal:0,6'],
            'discount' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'paid' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'cashbox_id' => [Rule::requiredIf(fn () => Money::of($this->paid ?: '0')->isPositive()), 'nullable', 'exists:cashboxes,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.vin' => ['required', 'string', 'max:30', 'distinct:ignore_case'],
            'items.*.brand_id' => ['required', 'exists:brands,id'],
            'items.*.model_id' => ['required', 'exists:car_models,id'],
            'items.*.trim' => ['nullable', 'string', 'max:100'],
            'items.*.year' => ['required', 'integer', 'between:1950,'.(now()->year + 1)],
            'items.*.color_id' => ['nullable', 'exists:colors,id'],
            'items.*.plate_no' => ['nullable', 'string', 'max:30'],
            'items.*.mileage' => ['nullable', 'integer', 'min:0'],
            'items.*.condition' => ['required', Rule::enum(VehicleCondition::class)],
            'items.*.fuel' => ['nullable', Rule::enum(FuelType::class)],
            'items.*.transmission' => ['nullable', Rule::enum(Transmission::class)],
            'items.*.origin' => ['nullable', 'string', 'max:100'],
            'items.*.location_id' => ['nullable', 'exists:locations,id'],
            'items.*.entry_status' => ['required', Rule::in(array_map(fn ($s) => $s->value, VehicleStatus::entryStatuses()))],
            'items.*.price' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'items.*.asking_price' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
            'items.*.min_price' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
        ]);

        if ($data['cashbox_id'] && ! auth()->user()->can('view', Cashbox::query()->findOrFail($data['cashbox_id']))) {
            $this->addError('cashbox_id', __('vouchers.errors.cashbox_not_allowed'));

            return;
        }

        $data['items'] = array_map(fn (array $item) => array_map(fn ($v) => $v === '' ? null : $v, $item), $data['items']);

        $invoice = $this->attempt(fn () => $save->handle($data, $this->invoice));
        if ($invoice === null) {
            return;
        }

        if (! $settings->bool('documents.require_approval', true) && auth()->user()->can('approve', $invoice)) {
            if ($this->attempt(fn () => $post->handle($invoice)) === null) {
                $this->invoice = $invoice;

                return;
            }
        }

        $this->notify(__('app.saved'));
        $this->redirectRoute('purchases.show', $invoice, navigate: true);
    }

    public function render(): View
    {
        $prices = array_map(fn ($item) => Money::of(is_numeric($item['price'] ?? '') ? (string) $item['price'] : '0'), $this->items);
        $subtotal = Money::sum($prices);
        $discount = Money::of(is_numeric($this->discount) ? $this->discount : '0');

        $brandIds = array_filter(array_column($this->items, 'brand_id'));

        return view('livewire.purchases.form', [
            'subtotal' => $subtotal,
            'total' => $subtotal->minus($discount),
            'currencies' => Currency::query()->active()->orderByDesc('is_base')->get(),
            'cashboxes' => Cashbox::query()->visibleTo(auth()->user())->where('is_active', true)->where('currency_id', $this->currency_id)->orderBy('name')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'modelsByBrand' => CarModel::query()->whereIn('brand_id', $brandIds)->orderBy('name')->get()->groupBy('brand_id'),
            'colors' => Color::query()->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'sources' => PurchaseSource::cases(),
            'entryStatuses' => VehicleStatus::entryStatuses(),
        ])->title($this->invoice ? __('purchases.edit', ['ref' => $this->invoice->displayNumber()]) : __('purchases.new'));
    }
}
