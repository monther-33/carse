<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\ReturnType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use App\Models\Concerns\IsDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Purchase or sales return of one vehicle (table `returns`; "Return" is a PHP keyword).
 *
 * @property int $id
 * @property int $branch_id
 * @property ReturnType $type
 * @property string|null $number
 * @property Carbon $date
 * @property string $invoice_type
 * @property int $invoice_id
 * @property int $vehicle_id
 * @property string $amount
 * @property string $amount_base
 * @property string $reason
 * @property DocumentStatus $status
 * @property int|null $journal_entry_id
 */
class ReturnDocument extends Model
{
    use Auditable, HasUserstamps, IsDocument;

    protected $table = 'returns';

    protected $fillable = [
        'branch_id', 'type', 'number', 'date', 'invoice_type', 'invoice_id', 'vehicle_id',
        'amount', 'amount_base', 'reason', 'status', 'journal_entry_id', 'notes', 'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReturnType::class,
            'date' => 'date',
            'amount' => 'decimal:3',
            'amount_base' => 'decimal:3',
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function invoice(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
