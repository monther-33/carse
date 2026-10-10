<?php

namespace App\Models;

use App\Enums\PartyType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasOpaqueRouteKey;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\PartyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Customer and/or supplier. No sub-ledger accounts: balances are journal lines on the
 * control accounts (receivables, payables, deposits) tagged with party_id.
 *
 * @property int $id
 * @property int $branch_id
 * @property PartyType $type
 * @property string $name
 * @property string|null $phone
 * @property string|null $phone2
 * @property string|null $national_id
 * @property string|null $address
 * @property string $credit_limit
 * @property string|null $notes
 * @property bool $is_active
 */
class Party extends Model implements HasMedia
{
    /** @use HasFactory<PartyFactory> */
    use Auditable, HasFactory, HasOpaqueRouteKey, HasUserstamps, InteractsWithMedia, SoftDeletes;

    protected $fillable = ['branch_id', 'type', 'name', 'phone', 'phone2', 'national_id', 'address', 'credit_limit', 'notes', 'is_active'];

    protected function casts(): array
    {
        return [
            'type' => PartyType::class,
            'credit_limit' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('id_card')->singleFile();
    }

    /** @return HasMany<Guarantor, $this> */
    public function guarantors(): HasMany
    {
        return $this->hasMany(Guarantor::class);
    }

    /** @param Builder<Party> $query */
    public function scopeCustomers(Builder $query): void
    {
        $query->whereIn('type', [PartyType::Customer, PartyType::Both]);
    }

    /** @param Builder<Party> $query */
    public function scopeSuppliers(Builder $query): void
    {
        $query->whereIn('type', [PartyType::Supplier, PartyType::Both]);
    }

    /** @param Builder<Party> $query */
    public function scopeSearch(Builder $query, string $term): void
    {
        $query->where(fn (Builder $q) => $q
            ->where('name', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->orWhere('phone2', 'like', "%{$term}%")
            ->orWhere('national_id', 'like', "%{$term}%"));
    }
}
