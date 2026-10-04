<?php

namespace App\Models;

use App\Enums\FuelType;
use App\Enums\Transmission;
use App\Enums\VehicleCondition;
use App\Enums\VehicleStatus;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * A unique stock unit tracked by VIN. Status and cost columns change only through
 * App\Services\Vehicles\VehicleStateMachine and the document Actions.
 *
 * @property int $id
 * @property int $branch_id
 * @property string $vin
 * @property string|null $plate_no
 * @property int $brand_id
 * @property int $model_id
 * @property string|null $trim
 * @property int $year
 * @property int|null $color_id
 * @property int|null $mileage
 * @property FuelType|null $fuel
 * @property Transmission|null $transmission
 * @property VehicleCondition $condition
 * @property string|null $origin
 * @property int|null $location_id
 * @property VehicleStatus $status
 * @property string $purchase_cost
 * @property string $extra_cost
 * @property string $total_cost
 * @property string|null $asking_price
 * @property string|null $min_price
 * @property int|null $purchase_invoice_id
 * @property int|null $sale_invoice_id
 * @property Carbon|null $received_at
 * @property Carbon|null $sold_at
 * @property string|null $notes
 */
class Vehicle extends Model implements HasMedia
{
    /** @use HasFactory<VehicleFactory> */
    use Auditable, HasFactory, HasUserstamps, InteractsWithMedia;

    /** Columns a user may edit directly on the vehicle card. */
    public const EDITABLE = [
        'plate_no', 'brand_id', 'model_id', 'trim', 'year', 'color_id', 'mileage', 'fuel',
        'transmission', 'condition', 'origin', 'location_id', 'asking_price', 'min_price', 'notes',
    ];

    protected $fillable = [
        'branch_id', 'vin', ...self::EDITABLE,
        'status', 'purchase_cost', 'extra_cost', 'total_cost',
        'purchase_invoice_id', 'sale_invoice_id', 'received_at', 'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => VehicleStatus::class,
            'condition' => VehicleCondition::class,
            'fuel' => FuelType::class,
            'transmission' => Transmission::class,
            'year' => 'integer',
            'mileage' => 'integer',
            'purchase_cost' => 'decimal:3',
            'extra_cost' => 'decimal:3',
            'total_cost' => 'decimal:3',
            'asking_price' => 'decimal:3',
            'min_price' => 'decimal:3',
            'received_at' => 'date',
            'sold_at' => 'date',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
        $this->addMediaCollection('documents');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $conversion = $this->addMediaConversion('thumb')->performOnCollections('photos')->nonQueued();
        $conversion->width(320)->height(240);
    }

    /** @return BelongsTo<Brand, $this> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

    /** @return BelongsTo<CarModel, $this> */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id')->withTrashed();
    }

    /** @return BelongsTo<Color, $this> */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }

    /** @return BelongsTo<Location, $this> */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class)->withTrashed();
    }

    /** @return BelongsTo<PurchaseInvoice, $this> */
    public function purchaseInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    /** @return HasMany<VehicleStatusLog, $this> */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(VehicleStatusLog::class)->latest('id');
    }

    /** @return HasMany<VehicleCost, $this> */
    public function costs(): HasMany
    {
        return $this->hasMany(VehicleCost::class)->latest('id');
    }

    /** "Toyota Camry 2022" */
    public function title(): string
    {
        return trim($this->brand->name.' '.$this->carModel->name.' '.($this->trim ?? '').' '.$this->year);
    }

    /** Days since the vehicle entered stock (stock ageing). */
    public function daysInStock(): ?int
    {
        return $this->received_at === null ? null : (int) $this->received_at->diffInDays(now()->startOfDay());
    }

    /** @param Builder<Vehicle> $query */
    public function scopeInStock(Builder $query): void
    {
        $query->whereIn('status', array_filter(VehicleStatus::cases(), fn (VehicleStatus $s) => $s->isInStock()));
    }

    /** @param Builder<Vehicle> $query */
    public function scopeSearch(Builder $query, string $term): void
    {
        $query->where(fn (Builder $q) => $q
            ->where('vin', 'like', "%{$term}%")
            ->orWhere('plate_no', 'like', "%{$term}%")
            ->orWhereHas('carModel', fn (Builder $m) => $m->where('name', 'like', "%{$term}%"))
            ->orWhereHas('brand', fn (Builder $b) => $b->where('name', 'like', "%{$term}%")));
    }
}
