<?php

namespace App\Models;

use App\Enums\CashboxType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\CashboxFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $name
 * @property CashboxType $type
 * @property int $currency_id
 * @property int $account_id
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property bool $is_active
 */
class Cashbox extends Model
{
    /** @use HasFactory<CashboxFactory> */
    use Auditable, HasFactory, HasUserstamps;

    protected $fillable = ['branch_id', 'name', 'type', 'currency_id', 'account_id', 'bank_name', 'account_number', 'is_active'];

    protected function casts(): array
    {
        return [
            'type' => CashboxType::class,
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Branch, $this> */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** @return BelongsTo<Currency, $this> */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /** @return BelongsTo<Account, $this> */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Users without cashboxes.view_all only see the cashboxes assigned to them.
     *
     * @param  Builder<Cashbox>  $query
     */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if ($user->can('cashboxes.view_all')) {
            return;
        }

        $query->whereHas('users', fn (Builder $q) => $q->whereKey($user->getKey()));
    }
}
