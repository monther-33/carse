<?php

namespace App\Policies;

class BrandPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'references.manage';
    }
}
