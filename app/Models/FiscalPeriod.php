<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Carbon\CarbonInterface;
use Database\Factories\FiscalPeriodFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FiscalPeriod extends Model
{
    /** @use HasFactory<FiscalPeriodFactory> */
    use Auditable, HasFactory, HasUserstamps;

    protected $fillable = ['name', 'start_date', 'end_date', 'is_closed', 'closed_at', 'closed_by'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_closed' => 'boolean',
            'closed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /** @param Builder<FiscalPeriod> $query */
    public function scopeContaining(Builder $query, CarbonInterface $date): void
    {
        $day = $date->toDateString();
        $query->where('start_date', '<=', $day)->where('end_date', '>=', $day);
    }
}
