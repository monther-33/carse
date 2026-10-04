<?php

namespace App\Policies;

class LocationPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'references.manage';
    }
}
