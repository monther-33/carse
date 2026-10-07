<?php

namespace App\Livewire\Vehicles;

use App\Actions\Vehicles\UpdateVehicle;
use App\Enums\FuelType;
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
use App\Models\Vehicle;
use App\Services\Vehicles\VehicleStateMachine;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Vehicle card: details, photos and documents, manual status steps before "available",
 * status history and (with vehicles.view_cost) cost breakdown.
 */
#[Layout('layouts.app')]
class Show extends Component
{
    use AcceptsQuickCreate, HandlesBusinessErrors, Notifies, WithFileUploads;

    public Vehicle $vehicle;

    public bool $showEdit = false;

    /** @var array<string, mixed> */
    public array $form = [];

    /** @var list<TemporaryUploadedFile> */
    public array $photos = [];

    /** @var list<TemporaryUploadedFile> */
    public array $documents = [];

    public string $statusNote = '';

    public function mount(Vehicle $vehicle): void
    {
        $this->authorize('view', $vehicle);
        $this->vehicle = $vehicle;
    }

    public function edit(): void
    {
        $this->authorize('update', $this->vehicle);
        $this->form = array_map(
            fn ($value) => $value instanceof \BackedEnum ? $value->value : $value,
            $this->vehicle->only(Vehicle::EDITABLE),
        );
        $this->resetValidation();
        $this->showEdit = true;
    }

    public function save(UpdateVehicle $action): void
    {
        $this->authorize('update', $this->vehicle);

        $data = $this->validate([
            'form.plate_no' => ['nullable', 'string', 'max:30'],
            'form.brand_id' => ['required', 'exists:brands,id'],
            'form.model_id' => ['required', Rule::exists('car_models', 'id')->where('brand_id', $this->form['brand_id'] ?? 0)],
            'form.trim' => ['nullable', 'string', 'max:100'],
            'form.year' => ['required', 'integer', 'between:1950,'.(now()->year + 1)],
            'form.color_id' => ['nullable', 'exists:colors,id'],
            'form.mileage' => ['nullable', 'integer', 'min:0'],
            'form.fuel' => ['nullable', Rule::enum(FuelType::class)],
            'form.transmission' => ['nullable', Rule::enum(Transmission::class)],
            'form.condition' => ['required', Rule::enum(VehicleCondition::class)],
            'form.origin' => ['nullable', 'string', 'max:100'],
            'form.location_id' => ['nullable', 'exists:locations,id'],
            'form.asking_price' => ['nullable', 'numeric', 'min:0', 'decimal:0,3'],
            'form.min_price' => ['nullable', 'numeric', 'min:0', 'decimal:0,3', 'lte:form.asking_price'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
        ])['form'];

        $data = array_map(fn ($v) => $v === '' ? null : $v, $data);
        $this->vehicle = $action->handle($this->vehicle, $data);
        $this->showEdit = false;
        $this->notify(__('app.saved'));
    }

    public function moveTo(string $status, VehicleStateMachine $machine): void
    {
        $this->authorize('update', $this->vehicle);

        $this->attempt(function () use ($status, $machine) {
            $machine->transition($this->vehicle, VehicleStatus::from($status), note: $this->statusNote ?: null, manual: true);
            $this->statusNote = '';
            $this->notify(__('vehicles.status_changed'));
        }, 'status');
    }

    public function uploadPhotos(): void
    {
        $this->authorize('update', $this->vehicle);
        $this->validate(['photos' => ['array', 'max:20'], 'photos.*' => ['image', 'max:8192']]);

        foreach ($this->photos as $photo) {
            $this->vehicle->addMedia($photo->getRealPath())->usingFileName($photo->hashName())->toMediaCollection('photos');
        }
        $this->photos = [];
        $this->notify(__('app.saved'));
    }

    public function uploadDocuments(): void
    {
        $this->authorize('update', $this->vehicle);
        $this->validate(['documents' => ['array', 'max:10'], 'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240']]);

        foreach ($this->documents as $document) {
            $this->vehicle->addMedia($document->getRealPath())
                ->usingName(pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($document->hashName())
                ->toMediaCollection('documents');
        }
        $this->documents = [];
        $this->notify(__('app.saved'));
    }

    /** Move a photo one step earlier (-1) or later (+1) in the gallery order. */
    public function movePhoto(int $mediaId, int $direction): void
    {
        $this->authorize('update', $this->vehicle);

        $ids = $this->vehicle->getMedia('photos')->pluck('id')->all();
        $index = array_search($mediaId, $ids, true);
        $swap = $index === false ? false : $index + $direction;

        if ($index !== false && isset($ids[$swap])) {
            [$ids[$index], $ids[$swap]] = [$ids[$swap], $ids[$index]];
            Media::setNewOrder($ids);
        }
    }

    public function deleteMedia(int $mediaId): void
    {
        $this->authorize('update', $this->vehicle);

        $this->vehicle->media()->whereKey($mediaId)->firstOrFail()->delete();
        $this->notify(__('app.deleted'));
    }

    public function render(): View
    {
        $this->vehicle->load(['brand', 'carModel', 'color', 'location', 'purchaseInvoice.party', 'media', 'ownership.owners.party', 'ownership.dues']);

        return view('livewire.vehicles.show', [
            'canViewCost' => auth()->user()->can('viewCost', Vehicle::class),
            'canUpdate' => auth()->user()->can('update', $this->vehicle),
            'logs' => $this->vehicle->statusLogs()->with('user')->get(),
            'costs' => auth()->user()->can('viewCost', Vehicle::class) ? $this->vehicle->costs()->with('expense')->get() : collect(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'models' => CarModel::query()->where('brand_id', $this->form['brand_id'] ?? $this->vehicle->brand_id)->orderBy('name')->get(),
            'colors' => Color::query()->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
        ])->title($this->vehicle->title());
    }
}
