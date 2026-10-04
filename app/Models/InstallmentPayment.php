<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $installment_id
 * @property int $voucher_id
 * @property string $amount
 */
class InstallmentPayment extends Model
{
    protected $fillable = ['installment_id', 'voucher_id', 'amount'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:3'];
    }

    /** @return BelongsTo<Installment, $this> */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    /** @return BelongsTo<Voucher, $this> */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
