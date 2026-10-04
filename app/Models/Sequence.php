<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Only App\Services\Numbering\SequenceService touches this table.
 */
class Sequence extends Model
{
    protected $fillable = ['type', 'prefix', 'year', 'next_number'];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'next_number' => 'integer',
        ];
    }
}
