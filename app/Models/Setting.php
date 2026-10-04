<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    use Auditable;

    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saving(function (Setting $setting) {
            $setting->updated_by = Auth::id();
        });
    }
}
