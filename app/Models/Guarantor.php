<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\GuarantorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Guarantor of a customer's installment plan.
 *
 * @property int $id
 * @property int $party_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $national_id
 * @property string|null $relation
 * @property string|null $address
 */
class Guarantor extends Model
{
    /** @use HasFactory<GuarantorFactory> */
    use Auditable, HasFactory, HasUserstamps;

    protected $fillable = ['party_id', 'name', 'phone', 'national_id', 'relation', 'address'];

    /** @return BelongsTo<Party, $this> */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
