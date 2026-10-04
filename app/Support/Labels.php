<?php

namespace App\Support;

use Illuminate\Support\Facades\Lang;

/**
 * Display names for roles and permissions; custom roles created from the UI show their own name.
 */
final class Labels
{
    public static function role(string $name): string
    {
        $key = 'permissions.roles.'.$name;

        return Lang::has($key) ? __($key) : $name;
    }

    public static function permission(string $name): string
    {
        [$module, $action] = explode('.', $name, 2);
        $key = 'permissions.actions.'.$module.'.'.$action;

        return Lang::has($key) ? __($key) : $name;
    }

    public static function module(string $module): string
    {
        $key = 'permissions.modules.'.$module;

        return Lang::has($key) ? __($key) : $module;
    }
}
