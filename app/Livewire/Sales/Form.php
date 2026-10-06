<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\PostSalesInvoice;
use App\Actions\Sales\SalesTerms;
use App\Actions\Sales\SaveSalesInvoice;
use App\Enums\PaymentType;
use App\Enums\VehicleStatus;
use App\Livewire\Concerns\AcceptsQuickCreate;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Cashbox;
use App\Models\Color;
use App\Models\Currency;
use App\Models\Guarantor;
use App\Models\Location;
use App\Models\Party;
use App\Models\Reservation;
use App\Models\SalesInvoice;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use App\Services\Installments\InstallmentScheduleService;
use App\Services\Sales\CreditLimitCheck;
use App\Services\Sales\DepositService;
use App\Support\Money;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * The quick sale screen: find the car by VIN/plate/model, pick or create the customer,
 * see totals, what is still due and the installment schedule as you type.
 * A saved draft is also the printable price quotation.
 */
#[Layout('layouts.app')]
class Form extends Component
{
    use AcceptsQuickCreate, HandlesBusinessErrors, Notifies;

    public ?SalesInvoice $invoice = null;

    #[Url(as: 'reservation')]
    public ?int $fromReservation = null;

    public string $date = '';

    public ?int $party_id = null;

    public ?int $salesperson_id = null;

    public ?int $reservation_id = null;

    /** How much of the customer's deposit credit to apply (any part, from any reservation). */
    public string $deposit_applied = '0';

    public string $payment_type = 'cash';

    public ?int $currency_id = null;

    public string $rate = '1';

    public string $discount = '0';

    public string $notes = '';

    /** @var list<array{vehicle_id: int, price: string}> */
    public array $items = [];

    public ?int $pickVehicleId = null;

    /** @var list<array{cashbox_id: int|null, amount: string}> */
    public array $payments = [];

    public bool $hasTradeIn = false;

    /** @var array<string, mixed> */
    public array $tradeIn = [];

    public int $months = 12;

    public string $start_date = '';

    public ?int $guarantor_id = null;

    /** @var array{name: string, phone: string, national_id: string, relation: string} */
    public array $guarantor = ['name' => '', 'phone' => '', 'national_id' => '', 'relation' => ''];

    public function mount(?SalesInvoice $invoice = null): void
    {
        $this->resetTradeIn();

        if ($invoice?->exists) {
            $this->authorize('update', $invoice);
            $this->invoice = $invoice;
            $this->fillFrom($invoice);

            return;
        }

        $this->authorize('create', SalesInvoice::class);
        $this->date = now()->toDateString();
        $this->start_date = now()->addMonth()->toDateString();
        $this->salesperson_id = auth()->id();
        $this->currency_id = app(ExchangeRateService::class)->baseCurrency()->id;
        $this->payments = [['cashbox_id' => null, 'amount' => '']];

        if ($this->fromReservation) {
            $reservation = Reservation::query()->active()->find($this->fromReservation);
            if ($reservation !== null) {
                $this->party_id = $reservation->party_id;
                $this->currency_id = $reservation->currency_id;
                $this->reservation_id = $reservation->id;
                $this->addVehicle($reservation->vehicle_id);
                $this->suggestDeposit();
            }
        }
    }

    private function fillFrom(SalesInvoice $invoice): void
    {
        $invoice->load(['items', 'payments', 'tradeIn.vehicle', 'installmentPlan']);

        $this->date = $invoice->date->toDateString();
        $this->party_id = $invoice->party_id;
        $this->salesperson_id = $invoice->salesperson_id;
        $this->reservation_id = $invoice->reservation_id;
        $this->deposit_applied = (string) $invoice->deposit_applied;
        $this->payment_type = $invoice->payment_type->value;
        $this->currency_id = $invoice->currency_id;
        $this->rate = rtrim(rtrim($invoice->rate, '0'), '.');
        $this->discount = (string) $invoice->discount;
        $this->notes = (string) $invoice->notes;
        $this->items = $invoice->items->map(fn ($i) => ['vehicle_id' => $i->vehicle_id, 'price' => (string) $i->price])->all();
        $this->payments = $invoice->payments->map(fn ($p) => ['cashbox_id' => $p->cashbox_id, 'amount' => (string) $p->amount])->all()
            ?: [['cashbox_id' => null, 'amount' => '']];

        if ($invoice->tradeIn !== null) {
            $this->hasTradeIn = true;
            $this->tradeIn = array_intersect_key($invoice->tradeIn->vehicle->getAttributes(), array_flip(['vin', ...Vehicle::EDITABLE]))
                + ['value' => (string) $invoice->tradeIn->value, 'entry_status' => $invoice->tradeIn->entry_status->value];
        }

        $plan = $invoice->installmentPlan;
        $this->months = $plan->months ?? 12;
        $this->start_date = $plan?->start_date->toDateString() ?? $invoice->date->copy()->addMonth()->toDateString();
        $this->guarantor_id = $plan?->guarantor_id;
    }

    private function resetTradeIn(): void
    {
        $this->tradeIn = [
            'vin' => '', 'brand_id' => null, 'model_id' => null, 'trim' => '', 'year' => now()->year - 5,
            'color_id' => null, 'plate_no' => '', 'mileage' => null, 'condition' => 'used', 'fuel' => 'petrol',
            'transmission' => 'automatic', 'origin' => '', 'location_id' => null, 'asking_price' => '', 'min_price' => '',
            'value' => '', 'entry_status' => VehicleStatus::InPreparation->value,
        ];
    }

    public function updatedPickVehicleId(?int $id): void
    {
        if ($id !== null) {
            $this->addVehicle($id);
        }
        $this->pickVehicleId = null;
    }

    private function addVehicle(int $id): void
    {
        if (in_array($id, array_column($this->items, 'vehicle_id'), true)) {
            return;
        }

        $vehicle = Vehicle::query()->find($id);
        if ($vehicle === null) {
            return;
        }

        $this->items[] = ['vehicle_id' => $vehicle->id, 'price' => $vehicle->asking_price !== null ? (string) $vehicle->asking_price : ''];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedPartyId(): void
    {
        $this->reservation_id = null;
        $this->guarantor_id = null;
        $this->suggestDeposit();
    }

    /** Default to applying all of the customer's deposit credit; the user may lower it. */
    private function suggestDeposit(): void
    {
        $this->deposit_applied = $this->party_id && $this->currency_id
            ? (string) app(DepositService::class)->availableCredit($this->party_id, (int) $this->currency_id)
            : '0';
    }

    public function updatedReservationId(?int $id): void
    {
        $reservation = $id ? Reservation::query()->find($id) : null;
        if ($reservation !== null) {
            $this->currency_id = $reservation->currency_id;
            $this->addVehicle($reservation->vehicle_id);
        }
        $this->suggestDeposit();
    }

    public function updatedCurrencyId(): void
    {
        $rates = app(ExchangeRateService::class);
        $this->rate = '1';
        if (! $rates->isBase((int) $this->currency_id)) {
            $this->attempt(fn () => $this->rate = (string) $rates->rateFor((int) $this->currency_id, CarbonImmutable::parse($this->date ?: now())), 'rate');
        }
        $this->payments = [['cashbox_id' => null, 'amount' => '']];
        $this->suggestDeposit();
    }

    public function addPayment(): void
    {
        $this->payments[] = ['cashbox_id' => null, 'amount' => ''];
    }

    public function removePayment(int $index): void
    {
        unset($this->payments[$index]);
        $this->payments = array_values($this->payments);
    }

    /** Fill the single payment line with what is due (cash / transfer sales). */
    public function payInFull(): void
    {
        $due = $this->terms()->due;
        $this->payments = [['cashbox_id' => $this->payments[0]['cashbox_id'] ?? null, 'amount' => (string) $due]];
    }

    public function save(SaveSalesInvoice $save, PostSalesInvoice $post, Settings $settings): void
    {
        $this->invoice ? $this->authorize('update', $this->invoice) : $this->authorize('create', SalesInvoice::class);

        $isInstallment = $this->payment_type === PaymentType::Installment->value;
        // The salesperson states where the money went; it is posted when the sale is approved.
        $cashboxIds = Cashbox::query()->where('is_active', true)->where('currency_id', $this->currency_id)->pluck('id')->all();

        $this->validate([
            'date' => ['required', 'date'],
            'party_id' => ['required', 'exists:parties,id'],
            'salesperson_id' => ['required', 'exists:users,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'payment_type' => ['required', Rule::enum(PaymentType::class)],
            'currency_id' => ['required', 'exists:currencies,id'],
            'rate' => ['required', 'numeric', 'gt:0', 'decimal:0,6'],
            'discount' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'deposit_applied' => ['required', 'numeric', 'min:0', 'decimal:0,3'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.vehicle_id' => ['required', 'exists:vehicles,id'],
            'items.*.price' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'payments.*.amount' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
            'payments.*.cashbox_id' => ['nullable', 'required_with:payments.*.amount', Rule::in($cashboxIds)],
            'months' => [$isInstallment ? 'required' : 'nullable', 'integer', 'between:1,120'],
            'start_date' => [$isInstallment ? 'required' : 'nullable', 'date'],
            'guarantor_id' => ['nullable', 'exists:guarantors,id'],
            'guarantor.name' => [$isInstallment && ! $this->guarantor_id ? 'required' : 'nullable', 'string', 'max:255'],
            'tradeIn.vin' => [$this->hasTradeIn ? 'required' : 'nullable', 'string', 'max:30'],
            'tradeIn.brand_id' => [$this->hasTradeIn ? 'required' : 'nullable', 'exists:brands,id'],
            'tradeIn.model_id' => [$this->hasTradeIn ? 'required' : 'nullable', 'exists:car_models,id'],
            'tradeIn.year' => [$this->hasTradeIn ? 'required' : 'nullable', 'integer', 'between:1950,'.(now()->year + 1)],
            'tradeIn.value' => [$this->hasTradeIn ? 'required' : 'nullable', 'numeric', 'gt:0', 'decimal:0,3'],
            'tradeIn.entry_status' => ['required', Rule::in([VehicleStatus::InPreparation->value, VehicleStatus::Available->value])],
        ]);

        if (! auth()->user()->can('sales.view_all')) {
            $this->salesperson_id = auth()->id();
        }

        $payments = array_values(array_filter($this->payments, fn ($p) => is_numeric($p['amount']) && Money::of($p['amount'])->isPositive()));

        $data = [
            'date' => $this->date,
            'party_id' => $this->party_id,
            'salesperson_id' => $this->salesperson_id,
            'reservation_id' => $this->reservation_id,
            'deposit_applied' => $this->deposit_applied,
            'payment_type' => $this->payment_type,
            'currency_id' => $this->currency_id,
            'rate' => $this->rate,
            'discount' => $this->discount,
            'notes' => $this->notes ?: null,
            'items' => $this->items,
            'payments' => $payments,
            'trade_in' => $this->hasTradeIn ? array_map(fn ($v) => $v === '' ? null : $v, $this->tradeIn) : null,
            'installment' => $isInstallment ? [
                'down_payment' => (string) Money::sum(array_column($payments, 'amount')),
                'months' => $this->months,
                'start_date' => $this->start_date,
                'guarantor_id' => $this->guarantor_id,
                'guarantor' => $this->guarantor,
            ] : null,
        ];

        $invoice = $this->attempt(fn () => $save->handle($data, $this->invoice));
        if ($invoice === null) {
            return;
        }

        if (! $settings->bool('documents.require_approval', true) && auth()->user()->can('approve', $invoice)) {
            $this->attempt(fn () => $post->handle($invoice));
        }

        $this->notify(__('app.saved'));
        $this->redirectRoute('sales.show', $invoice, navigate: true);
    }

    /** Display-only figures; the Actions recompute and validate everything on save. */
    private function terms(): SalesTerms
    {
        $num = fn ($v) => is_numeric($v) ? Money::of((string) $v) : Money::zero();
        $payments = array_map(fn ($p) => $num($p['amount']), $this->payments);

        return new SalesTerms(
            PaymentType::from($this->payment_type),
            Money::sum(array_map(fn ($i) => $num($i['price']), $this->items)),
            $num($this->discount),
            $this->hasTradeIn ? $num($this->tradeIn['value'] ?? '') : Money::zero(),
            $num($this->deposit_applied),
            $payments,
            Money::sum($payments),
            $this->months,
        );
    }

    public function render(InstallmentScheduleService $schedule, DepositService $deposits, CreditLimitCheck $credit): View
    {
        $terms = $this->terms();
        $party = $this->party_id ? Party::query()->find($this->party_id) : null;
        $rate = is_numeric($this->rate) && (float) $this->rate > 0 ? (string) $this->rate : '1';
        $vehicles = Vehicle::query()->with(['brand', 'carModel'])->findMany(array_column($this->items, 'vehicle_id'))->keyBy('id');

        return view('livewire.sales.form', [
            'terms' => $terms,
            'vehicles' => $vehicles,
            'schedule' => $this->payment_type === PaymentType::Installment->value && $this->start_date
                ? $schedule->build($terms->financed(), max(1, $this->months), CarbonImmutable::parse($this->start_date))
                : [],
            'reservations' => $this->party_id ? Reservation::query()->active()->where('party_id', $this->party_id)->with('vehicle')->get() : collect(),
            'guarantors' => $this->party_id ? Guarantor::query()->where('party_id', $this->party_id)->get() : collect(),
            'cashboxes' => Cashbox::query()->where('is_active', true)->where('currency_id', $this->currency_id)->orderBy('name')->get(),
            'currencies' => Currency::query()->active()->orderByDesc('is_base')->get(),
            'salespeople' => auth()->user()->can('sales.view_all') ? User::query()->where('is_active', true)->orderBy('name')->get() : collect(),
            'paymentTypes' => PaymentType::cases(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'tradeInModels' => CarModel::query()->where('brand_id', $this->tradeIn['brand_id'] ?? 0)->orderBy('name')->get(),
            'colors' => Color::query()->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'canViewCost' => auth()->user()->can('vehicles.view_cost'),
            'creditWarning' => $party ? $credit->warning($party, Money::toBase($terms->due->minus($terms->paid), $rate)) : null,
            'availableDeposit' => $this->party_id ? $deposits->availableCredit($this->party_id, (int) $this->currency_id) : Money::zero(),
        ])->title($this->invoice ? __('sales.edit', ['ref' => $this->invoice->displayNumber()]) : __('sales.new'));
    }
}
