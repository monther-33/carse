<?php

namespace App\Models\Concerns;

use App\Support\UrlToken;
use Illuminate\Database\Eloquent\Model;

/**
 * Links show an encrypted code instead of the record id (/sales/x7Kp2Q, not /sales/12).
 * route('sales.show', $invoice) builds it; route model binding reads it back. A code that was
 * altered, or belongs to another kind of record, finds nothing (404).
 *
 * @mixin Model
 */
trait HasOpaqueRouteKey
{
    public function getRouteKey(): mixed
    {
        return UrlToken::encodeId(static::class, (int) $this->getKey());
    }

    /**
     * @param  mixed  $value
     * @param  string|null  $field
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null) {
            return parent::resolveRouteBinding($value, $field);
        }

        $id = UrlToken::decodeId(static::class, (string) $value);

        return $id === null ? null : $this->newQuery()->whereKey($id)->first();
    }

    /** The record a link code points to, for codes read from a query string. */
    public static function findByRouteKey(?string $code): ?static
    {
        $id = $code === null ? null : UrlToken::decodeId(static::class, $code);

        return $id === null ? null : static::query()->find($id);
    }
}
