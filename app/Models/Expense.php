<?php

namespace App\Models;

use App\Enums\CostBearer;
use App\Enums\DocumentStatus;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Models\Concerns\IsDocument;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Paid from one cashbox in its currency. With vehicle_id it is capitalised on the vehicle.
 *
 * @property int $id
 * @property int $branch_id
 * @property string|null $number
 * @property Carbon $date
 * @property int $category_id
 * @property int $cashbox_id
 * @property string $amount
 * @property int $currency_id
 * @property string $rate
 * @property string $amount_base
 * @property int|null $vehicle_id
 * @property string $description
 * @property int|null $recurs_every_months
 * @property Carbon|null $next_due_date
 * @property DocumentStatus $status
 * @property int|null $journal_entry_id
 */
class Expense extends Model implements HasMedia
{
    /** @use HasFactory<ExpenseFactory> */
    use Auditable, HasFactory, HasUserstamps, InteractsWithMedia, IsDocument;

    protected $fillable = [
        'branch_id', 'number', 'date', 'category_id', 'cashbox_id', 'amount', 'currency_id', 'rate', 'amount_base',
        'vehicle_id', 'description', 'recurs_every_months', 'next_due_date', 'status', 'journal_entry_id', 'notes',
        'approved_by', 'approved_at', 'cancelled_by', 'cancelled_at', 'cancel_reason', 'borne_by'];

    protected function casts(): array
    {
        return [
            'borne_by' => CostBearer::class,
            'date' => 'date',
            'next_due_date' => 'date',
            'amount' => 'decimal:3',
            'rate' => 'decimal:6',
            'amount_base' => 'decimal:3',
            'recurs_every_months' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('receipt')->singleFile();
    }

    /** @return BelongsTo<ExpenseCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class)->withTrashed();
    }

    /** @return BelongsTo<Cashbox, $this> */
    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
