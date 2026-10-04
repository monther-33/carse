<?php

namespace App\Models;

use App\Enums\InstallmentStatus;
use App\Models\Concerns\Auditable;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $plan_id
 * @property int $sequence
 * @property Carbon $due_date
 * @property string $amount
 * @property string $paid_amount
 * @property InstallmentStatus $status
 */
class Installment extends Model
{
    use Auditable;

    protected $fillable = ['plan_id', 'sequence', 'due_date', 'amount', 'paid_amount', 'status'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:3',
            'paid_amount' => 'decimal:3',
            'status' => InstallmentStatus::class,
        ];
    }

    public function remaining(): BigDecimal
    {
        return Money::of($this->amount)->minus(Money::of($this->paid_amount));
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, [InstallmentStatus::Pending, InstallmentStatus::Partial], true)
            && $this->due_date->isBefore(today());
    }

    /** @param Builder<Installment> $query */
    public function scopeOpen(Builder $query): void
    {
        $query->whereIn('status', [InstallmentStatus::Pending, InstallmentStatus::Partial]);
    }

    /** @return BelongsTo<InstallmentPlan, $this> */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(InstallmentPlan::class, 'plan_id');
    }

    /** @return HasMany<InstallmentPayment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(InstallmentPayment::class);
    }
}
