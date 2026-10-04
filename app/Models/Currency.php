<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\CurrencyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    /** @use HasFactory<CurrencyFactory> */
    use Auditable, HasFactory, HasUserstamps, SoftDeletes;

    protected $fillable = ['code', 'name', 'symbol', 'decimals', 'is_base', 'is_active'];

    protected function casts(): array
    {
        return [
            'decimals' => 'integer',
            'is_base' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<ExchangeRate, $this> */
    public function rates(): HasMany
    {
        return $this->hasMany(ExchangeRate::class);
    }

    /** @param Builder<Currency> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
