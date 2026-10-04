<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A payment taken at the sale. Becomes a receipt voucher when the invoice is posted.
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $cashbox_id
 * @property string $amount
 * @property int|null $voucher_id
 */
class SalesInvoicePayment extends Model
{
    protected $fillable = ['invoice_id', 'cashbox_id', 'amount', 'voucher_id'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:3'];
    }

    /** @return BelongsTo<Cashbox, $this> */
    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }

    /** @return BelongsTo<Voucher, $this> */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
