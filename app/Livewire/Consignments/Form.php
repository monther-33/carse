<?php

namespace App\Livewire\Consignments;

use App\Actions\Ownership\ReceiveConsignment;
use App\Enums\EarningMode;
use App\Enums\FuelType;
use App\Enums\PayoutTiming;
use App\Enums\Transmission;
use App\Enums\VehicleCondition;
use App\Enums\VehicleStatus;
use App\Livewire\Concerns\AcceptsQuickCreate;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Location;
use App\Support\Features;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Receives a consignment car: the vehicle, its owners with their shares, and the agreement
 * (how the showroom earns and when the owners are paid). No journal entry is made.
 */
#[Layout('layouts.app')]
class Form extends Component
{
    use AcceptsQuickCreate, HandlesBusinessErrors, Notifies;

    /** @var array<string, mixed> vin + Vehicle::EDITABLE + entry_status */
    public array $vehicle = [];

    /** @var list<array{party_id: int|null, share: string}> */
    public array $owners = [];

    public string $received_at = '';

    public string $earning_mode = 'percent';

    public string $earning_amount = '';

    public string $earning_percent = '';

    public string $payout = 'on_sale';

    public string $notes = '';

    public function mount(): void
    {
        $this->authorize('consignments.manage');

        $this->received_at = now()->toDateString();
        $this->vehicle = [
            'vin' => '', 'brand_id' => null, 'model_id' => null, 'trim' => '', 'year' => now()->year,
            'color_id' => null, 'plate_no' => '', 'mileage' => null, 'condition' => VehicleCondition::Used->value,
            'fuel' => FuelType::Petrol->value, 'transmission' => Transmission::Automatic->value, 'origin' => '',
            'location_id' => null, 'entry_status' => VehicleStatus::Available->value,
            'asking_price' => '', 'min_price' => '', 'notes' => null,
        ];
        $this->owners = [['party_id' => null, 'share' => '100']];
    }

    public function addOwner(): void
    {
        $this->owners[] = ['party_id' => null, 'share' => ''];
    }

    public function removeOwner(int $row): void
    {
        unset($this->owners[$row]);
        $this->owners = array_values($this->owners);
    }

    public function save(ReceiveConsignment $receive, Features $features): void
    {
        $this->authorize('consignments.manage');

        $data = $this->validate([
            'received_at' => ['required', 'date'],
            'earning_mode' => ['required', Rule::enum(EarningMode::class)],
            'earning_amount' => [Rule::requiredIf(in_array($this->earning_mode, [EarningMode::NetPrice->value, EarningMode::Fixed->value], true)), 'nullable', 'numeric', 'gt:0', 'decimal:0,3'],
            'earning_percent' => [Rule::requiredIf($this->earning_mode === EarningMode::Percent->value), 'nullable', 'numeric', 'gt:0', 'lt:100', 'decimal:0,4'],
            'payout' => ['required', Rule::enum(PayoutTiming::class)],
            'notes' => ['nullable', 'string', 'max:2000'],
            'owners' => ['required', 'array', 'min:1'],
            'owners.*.party_id' => ['required', 'distinct', 'exists:parties,id'],
            'owners.*.share' => ['required', 'numeric', 'gt:0', 'max:100', 'decimal:0,4'],
            'vehicle.vin' => ['required', 'string', 'max:30'],
            'vehicle.brand_id' => ['required', 'exists:brands,id'],
            'vehicle.model_id' => ['required', 'exists:car_models,id'],
            'vehicle.trim' => ['nullable', 'string', 'max:100'],
            'vehicle.year' => ['required', 'integer', 'between:1950,'.(now()->year + 1)],
            'vehicle.color_id' => ['nullable', 'exists:colors,id'],
            'vehicle.plate_no' => ['nullable', 'string', 'max:30'],
            'vehicle.mileage' => ['nullable', 'integer', 'min:0'],
            'vehicle.condition' => ['required', Rule::enum(VehicleCondition::class)],
            'vehicle.fuel' => ['nullable', Rule::enum(FuelType::class)],
            'vehicle.transmission' => ['nullable', Rule::enum(Transmission::class)],
            'vehicle.origin' => ['nullable', 'string', 'max:100'],
            'vehicle.location_id' => ['nullable', 'exists:locations,id'],
            'vehicle.entry_status' => ['required', Rule::in(array_map(fn ($s) => $s->value, VehicleStatus::entryStatuses()))],
            'vehicle.asking_price' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
            'vehicle.min_price' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
        ]);

        $vehicle = array_map(fn ($v) => $v === '' ? null : $v, $this->vehicle);
        $ownership = $this->attempt(function () use ($receive, $features, $data, $vehicle) {
            $features->ensure(Features::CONSIGNMENT);

            return $receive->handle(array_merge($vehicle, [
                'received_at' => $data['received_at'],
                'earning_mode' => $data['earning_mode'],
                'earning_amount' => in_array($data['earning_mode'], ['net_price', 'fixed'], true) ? $data['earning_amount'] : null,
                'earning_percent' => $data['earning_mode'] === 'percent' ? $data['earning_percent'] : null,
                'payout' => $data['payout'],
                'notes' => $data['notes'] ?: null,
                'owners' => $data['owners'],
            ]));
        });
        if ($ownership === null) {
            return;
        }

        $this->notify(__('ownership.received_ok', ['number' => $ownership->number]));
        $this->redirectRoute('consignments.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.consignments.form', [
            'brands' => Brand::query()->orderBy('name')->get(),
            'brandModels' => CarModel::query()->where('brand_id', $this->vehicle['brand_id'] ?? 0)->orderBy('name')->get(),
            'colors' => Color::query()->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'entryStatuses' => VehicleStatus::entryStatuses(),
            'modes' => EarningMode::cases(),
            'payouts' => PayoutTiming::cases(),
            'sharesTotal' => array_sum(array_map(fn ($o) => is_numeric($o['share']) ? (float) $o['share'] : 0, $this->owners)),
        ])->title(__('app.nav_actions.new_consignment'));
    }
}
