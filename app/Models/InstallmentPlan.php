<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Collections reference the plan (vouchers.reference = installment_plan) and are spread
 * over its installments oldest first.
 *
 * @property int $id
 * @property int $sales_invoice_id
 * @property int|null $guarantor_id
 * @property string $down_payment
 * @property string $financed_amount
 * @property int $months
 * @property string $monthly_amount
 * @property Carbon $start_date
 */
class InstallmentPlan extends Model
{
    protected $fillable = ['sales_invoice_id', 'guarantor_id', 'down_payment', 'financed_amount', 'months', 'monthly_amount', 'start_date'];

    protected function casts(): array
    {
        return [
            'down_payment' => 'decimal:3',
            'financed_amount' => 'decimal:3',
            'monthly_amount' => 'decimal:3',
            'months' => 'integer',
            'start_date' => 'date',
        ];
    }

    /** @return BelongsTo<SalesInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    /** @return BelongsTo<Guarantor, $this> */
    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(Guarantor::class);
    }

    /** @return HasMany<Installment, $this> */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class, 'plan_id')->orderBy('sequence');
    }
}
