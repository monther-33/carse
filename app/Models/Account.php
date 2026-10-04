<?php

namespace App\Models;

use App\Enums\AccountNature;
use App\Enums\AccountType;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasUserstamps;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Chart of accounts node. Only leaf accounts (is_group = false) accept postings.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $parent_id
 * @property AccountType $type
 * @property AccountNature $nature
 * @property bool $is_group
 * @property bool $is_system
 * @property bool $is_active
 */
class Account extends Model
{
    /** @use HasFactory<AccountFactory> */
    use Auditable, HasFactory, HasUserstamps;

    protected $fillable = ['code', 'name', 'parent_id', 'type', 'nature', 'is_group', 'is_system', 'is_active'];

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
            'nature' => AccountNature::class,
            'is_group' => 'boolean',
            'is_system' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Account, $this> */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    /** @return HasMany<Account, $this> */
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id')->orderBy('code');
    }

    /** @return HasMany<JournalLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    /** @param Builder<Account> $query */
    public function scopePostable(Builder $query): void
    {
        $query->where('is_group', false)->where('is_active', true);
    }

    public function isPostable(): bool
    {
        return ! $this->is_group && $this->is_active;
    }

    public function label(): string
    {
        return $this->code.' - '.$this->name;
    }
}
