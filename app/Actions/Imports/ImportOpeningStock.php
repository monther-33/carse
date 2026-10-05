<?php

namespace App\Actions\Imports;

use App\Actions\OpeningStock\PostOpeningStock;
use App\Enums\DocumentStatus;
use App\Enums\FuelType;
use App\Enums\Transmission;
use App\Enums\VehicleCondition;
use App\Enums\VehicleStatus;
use App\Imports\CellParser;
use App\Imports\ImportPreview;
use App\Imports\InvalidCell;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Location;
use App\Models\OpeningStock;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\Vehicles\DraftVehicleResolver;
use App\Support\Money;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Vehicles in stock at go-live → a draft "opening stock" document (vehicles pending on it).
 * Approving it posts Dr inventory / in-transit, Cr opening balances, per vehicle.
 * Unknown brands, models, colours and locations are created on import (listed in the preview).
 */
class ImportOpeningStock extends Importer
{
    private const NEW_REFERENCES = [
        'brand' => 'imports.summary.new_brand',
        'model' => 'imports.summary.new_model',
        'color' => 'imports.summary.new_color',
        'location' => 'imports.summary.new_location',
    ];

    public function __construct(
        private readonly DraftVehicleResolver $resolver,
        private readonly PostOpeningStock $post,
        private readonly Settings $settings,
    ) {}

    public static function kind(): string
    {
        return 'vehicles';
    }

    public function columns(): array
    {
        return [
            'vin' => true, 'brand' => true, 'model' => true, 'year' => true, 'cost' => true,
            'status' => false, 'received_at' => false, 'condition' => false, 'trim' => false, 'color' => false,
            'mileage' => false, 'fuel' => false, 'transmission' => false, 'origin' => false, 'plate_no' => false,
            'location' => false, 'asking_price' => false, 'min_price' => false, 'notes' => false,
        ];
    }

    public function previewColumns(): array
    {
        return ['vin', 'vehicle', 'status', 'received_at', 'cost', 'asking_price'];
    }

    public function allows(User $user): bool
    {
        return $user->can('imports.run') && $user->can('create', OpeningStock::class);
    }

    public function isFinancial(): bool
    {
        return true;
    }

    protected function plan(array $rows, array $options): array
    {
        $preview = new ImportPreview;
        $date = CarbonImmutable::parse($options['date'] ?? 'today')->startOfDay();
        $items = [];
        $seen = [];
        $total = Money::zero();

        $brands = self::byLowerName(Brand::query()->pluck('id', 'name'));
        $models = CarModel::query()->get(['id', 'brand_id', 'name'])->mapWithKeys(fn ($m) => [$m->brand_id.'|'.mb_strtolower($m->name) => $m->id]);
        $colors = self::byLowerName(Color::query()->pluck('id', 'name'));
        $locations = self::byLowerName(Location::query()->pluck('id', 'name'));
        $new = ['brand' => [], 'model' => [], 'color' => [], 'location' => []];

        foreach ($rows as $number => $row) {
            try {
                $item = $this->readRow($row, $date);
            } catch (InvalidCell $e) {
                $preview->error($number, $e->getMessage());

                continue;
            }

            if (isset($seen[$item['vin']])) {
                $preview->error($number, __('imports.errors.duplicate_row', ['row' => $seen[$item['vin']]]));

                continue;
            }
            $seen[$item['vin']] = $number;

            $existing = Vehicle::query()->where('vin', $item['vin'])->first(['id', 'status']);
            if ($existing !== null && ($existing->status->isInStock() || $existing->status === VehicleStatus::Pending)) {
                $preview->error($number, __('imports.errors.vin_exists', ['vin' => $item['vin'], 'status' => $existing->status->label()]));

                continue;
            }

            $brandKey = mb_strtolower($item['brand']);
            if (! isset($brands[$brandKey])) {
                $new['brand'][$brandKey] = $item['brand'];
            }
            if (! isset($brands[$brandKey]) || ! isset($models[$brands[$brandKey].'|'.mb_strtolower($item['model'])])) {
                $new['model'][$brandKey.'|'.mb_strtolower($item['model'])] = $item['brand'].' '.$item['model'];
            }
            if ($item['color'] !== null && ! isset($colors[mb_strtolower($item['color'])])) {
                $new['color'][mb_strtolower($item['color'])] = $item['color'];
            }
            if ($item['location'] !== null && ! isset($locations[mb_strtolower($item['location'])])) {
                $new['location'][mb_strtolower($item['location'])] = $item['location'];
            }

            $total = $total->plus($item['cost']);
            $items[] = $item;
            $preview->rows[] = [
                'row' => $number,
                'vin' => $item['vin'],
                'vehicle' => trim("{$item['brand']} {$item['model']} {$item['year']}"),
                'status' => $item['status']->label(),
                'received_at' => $item['received_at']->toDateString(),
                'cost' => Money::format($item['cost']),
                'asking_price' => $item['asking_price'] !== null ? Money::format($item['asking_price']) : null,
            ];
        }

        $preview->summary[] = __('imports.summary.vehicles', ['count' => count($items), 'total' => Money::format($total)]);
        foreach ($new as $type => $names) {
            if ($names !== []) {
                $preview->summary[] = __(self::NEW_REFERENCES[$type], ['names' => implode('، ', $names)]);
            }
        }

        return [$preview, ['items' => $items]];
    }

    /**
     * @param  Collection<array-key, mixed>  $ids  name => id
     * @return Collection<string, mixed>
     */
    private static function byLowerName(Collection $ids): Collection
    {
        return $ids->mapWithKeys(fn ($id, $name) => [mb_strtolower((string) $name) => $id]);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function readRow(array $row, CarbonImmutable $date): array
    {
        $required = fn (string $column, mixed $value) => $value ?? throw new InvalidCell(__('imports.errors.required', ['column' => CellParser::label($column)]));

        $vin = strtoupper(str_replace(' ', '', (string) CellParser::text($row['vin'] ?? null)));
        if ($vin === '') {
            throw new InvalidCell(__('imports.errors.required', ['column' => CellParser::label('vin')]));
        }
        if (! preg_match('/^[A-Z0-9\-]{5,30}$/', $vin)) {
            throw new InvalidCell(__('imports.errors.vin'));
        }

        $cost = $required('cost', CellParser::amount($row['cost'] ?? null, 'cost'));
        if (! $cost->isPositive()) {
            throw new InvalidCell(__('imports.errors.positive', ['column' => CellParser::label('cost')]));
        }

        $received = CellParser::date($row['received_at'] ?? null, 'received_at') ?? $date;
        if ($received->isAfter($date)) {
            throw new InvalidCell(__('imports.errors.received_after'));
        }

        $asking = CellParser::amount($row['asking_price'] ?? null, 'asking_price');
        $min = CellParser::amount($row['min_price'] ?? null, 'min_price');
        if ($asking !== null && $min !== null && $min->isGreaterThan($asking)) {
            throw new InvalidCell(__('imports.errors.min_price'));
        }

        $item = [
            'vin' => $vin,
            'brand' => $required('brand', CellParser::text($row['brand'] ?? null)),
            'model' => $required('model', CellParser::text($row['model'] ?? null)),
            'year' => $required('year', CellParser::integer($row['year'] ?? null, 'year', 1950, (int) date('Y') + 1)),
            'cost' => $cost,
            'status' => CellParser::enum(VehicleStatus::class, $row['status'] ?? null, 'status', 'enums.vehicle_status', VehicleStatus::entryStatuses()) ?? VehicleStatus::Available,
            'received_at' => $received,
            'condition' => CellParser::enum(VehicleCondition::class, $row['condition'] ?? null, 'condition', 'enums.vehicle_condition') ?? VehicleCondition::Used,
            'trim' => CellParser::text($row['trim'] ?? null),
            'color' => CellParser::text($row['color'] ?? null),
            'mileage' => CellParser::integer($row['mileage'] ?? null, 'mileage', 0, 5_000_000),
            'fuel' => CellParser::enum(FuelType::class, $row['fuel'] ?? null, 'fuel', 'enums.fuel_type'),
            'transmission' => CellParser::enum(Transmission::class, $row['transmission'] ?? null, 'transmission', 'enums.transmission'),
            'origin' => CellParser::text($row['origin'] ?? null),
            'plate_no' => CellParser::text($row['plate_no'] ?? null),
            'location' => CellParser::text($row['location'] ?? null),
            'asking_price' => $asking,
            'min_price' => $min,
            'notes' => CellParser::text($row['notes'] ?? null),
        ];

        foreach (['brand' => 255, 'model' => 255, 'trim' => 100, 'origin' => 100, 'plate_no' => 30, 'color' => 255, 'location' => 255] as $key => $max) {
            if (mb_strlen((string) $item[$key]) > $max) {
                throw new InvalidCell(__('imports.errors.too_long'));
            }
        }

        return $item;
    }

    protected function write(array $plan, array $options): string
    {
        $user = Auth::user();
        $branchId = $user->branch_id ?? (int) Branch::query()->value('id');

        $stock = OpeningStock::query()->create([
            'branch_id' => $branchId,
            'status' => DocumentStatus::Draft,
            'date' => $options['date'],
            'description' => ($options['description'] ?? null) ?: __('imports.opening_stock_description'),
        ]);

        foreach ($plan['items'] as $item) {
            $brand = $this->reference(Brand::class, ['name' => $item['brand']]);
            $model = $this->reference(CarModel::class, ['brand_id' => $brand->getKey(), 'name' => $item['model']]);
            $color = $item['color'] !== null ? $this->reference(Color::class, ['name' => $item['color']]) : null;
            $location = $item['location'] !== null ? $this->reference(Location::class, ['branch_id' => $branchId, 'name' => $item['location']]) : null;

            $vehicle = $this->resolver->resolve([
                'vin' => $item['vin'],
                'brand_id' => $brand->getKey(),
                'model_id' => $model->getKey(),
                'year' => $item['year'],
                'condition' => $item['condition'],
                'trim' => $item['trim'],
                'color_id' => $color?->getKey(),
                'mileage' => $item['mileage'],
                'fuel' => $item['fuel'],
                'transmission' => $item['transmission'],
                'origin' => $item['origin'],
                'plate_no' => $item['plate_no'],
                'location_id' => $location?->getKey(),
                'asking_price' => $item['asking_price'] !== null ? (string) $item['asking_price'] : null,
                'min_price' => $item['min_price'] !== null ? (string) $item['min_price'] : null,
                'notes' => $item['notes'],
            ], $branchId, fn () => false);

            $stock->items()->create([
                'vehicle_id' => $vehicle->id,
                'entry_status' => $item['status'],
                'cost' => (string) $item['cost'],
                'received_at' => $item['received_at']->toDateString(),
            ]);
        }

        if (! $this->settings->bool('documents.require_approval', true) && $user->can('approve', $stock)) {
            $posted = $this->post->handle($stock);

            return __('imports.done.vehicles_posted', ['count' => count($plan['items']), 'number' => $posted->number]);
        }

        return __('imports.done.vehicles', ['count' => count($plan['items'])]);
    }

    /**
     * Finds a reference row (brand, model, colour, location) by its attributes, restoring it
     * if it was soft-deleted, or creates it.
     *
     * @param  class-string<Brand|CarModel|Color|Location>  $class
     * @param  array<string, mixed>  $attributes
     */
    private function reference(string $class, array $attributes): Brand|CarModel|Color|Location
    {
        $model = $class::query()->withTrashed()->firstOrCreate($attributes);
        if ($model->trashed()) {
            $model->restore();
        }

        return $model;
    }
}
