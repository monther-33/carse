<?php

namespace App\Livewire\Concerns;

use App\Enums\VehicleStatus;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Vehicle;
use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * The vehicle search modal (filters, results, details) shared by the vehicle picker and the
 * topbar finder. The host decides which statuses may be found (allowedStatuses) and what
 * "choose" does. View: livewire.pickers.partials.vehicle-search.
 */
trait SearchesVehicles
{
    public bool $showSearch = false;

    /** @var array{q: string, brand_id: string, model_id: string, color_id: string, year_from: string, year_to: string, status: string, price_max: string} */
    public array $filter = ['q' => '', 'brand_id' => '', 'model_id' => '', 'color_id' => '', 'year_from' => '', 'year_to' => '', 'status' => '', 'price_max' => ''];

    public int $limit = 20;

    public ?int $previewId = null;

    /** @return list<string> VehicleStatus values this host may find */
    abstract protected function allowedStatuses(): array;

    /** Opens the modal, starting from the text typed in the host's quick search, if any. */
    public function openSearch(): void
    {
        $this->filter = array_map(fn () => '', $this->filter);
        $this->filter['q'] = property_exists($this, 'search') ? trim((string) $this->search) : '';
        $this->limit = 20;
        $this->previewId = null;
        $this->showSearch = true;
    }

    public function updatedFilter(mixed $value, string $key): void
    {
        $this->limit = 20;
        if ($key === 'brand_id') {
            $this->filter['model_id'] = '';
        }
    }

    public function resetFilters(): void
    {
        $this->filter = array_map(fn () => '', $this->filter);
        $this->limit = 20;
    }

    public function loadMore(): void
    {
        $this->limit += 20;
    }

    public function preview(int $id): void
    {
        $this->previewId = $this->findableQuery()->whereKey($id)->value('id');
        $this->showSearch = true;
    }

    public function closePreview(): void
    {
        $this->previewId = null;
    }

    /** @return Builder<Vehicle> */
    private function findableQuery()
    {
        return Vehicle::query()->whereIn('status', $this->allowedStatuses());
    }

    /**
     * @return array<string, mixed> data for the search modal partial
     */
    protected function vehicleSearchData(): array
    {
        if (! $this->showSearch) {
            return ['vs' => null];
        }

        $f = $this->filter;
        $query = $this->findableQuery()
            ->with(['brand', 'carModel', 'color'])
            ->when($f['q'] !== '', fn ($q) => $q->search(trim($f['q'])))
            ->when($f['brand_id'] !== '', fn ($q) => $q->where('brand_id', $f['brand_id']))
            ->when($f['model_id'] !== '', fn ($q) => $q->where('model_id', $f['model_id']))
            ->when($f['color_id'] !== '', fn ($q) => $q->where('color_id', $f['color_id']))
            ->when(is_numeric($f['year_from']), fn ($q) => $q->where('year', '>=', (int) $f['year_from']))
            ->when(is_numeric($f['year_to']), fn ($q) => $q->where('year', '<=', (int) $f['year_to']))
            ->when($f['status'] !== '' && in_array($f['status'], $this->allowedStatuses(), true), fn ($q) => $q->where('status', $f['status']))
            ->when(is_numeric($f['price_max']), fn ($q) => $q->where('asking_price', '<=', (string) Money::of((string) $f['price_max'])))
            ->orderByDesc('received_at')->orderByDesc('id');

        $results = $query->limit($this->limit + 1)->get();

        return ['vs' => [
            'results' => $results->take($this->limit),
            'hasMore' => $results->count() > $this->limit,
            'brands' => Brand::query()->orderBy('name')->get(['id', 'name']),
            'models' => $f['brand_id'] !== '' ? CarModel::query()->where('brand_id', $f['brand_id'])->orderBy('name')->get(['id', 'name']) : new Collection,
            'colors' => Color::query()->orderBy('name')->get(['id', 'name']),
            'statuses' => array_map(fn (string $s) => VehicleStatus::from($s), $this->allowedStatuses()),
            'canSeeCost' => auth()->user()->can('vehicles.view_cost'),
            'preview' => $this->previewId
                ? Vehicle::query()->with(['brand', 'carModel', 'color', 'location', 'media'])->find($this->previewId)
                : null,
        ]];
    }
}
