<?php

namespace App\Policies;

class CarModelPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'references.manage';
    }
}
