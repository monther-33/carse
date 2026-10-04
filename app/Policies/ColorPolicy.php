<?php

namespace App\Policies;

class ColorPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'references.manage';
    }
}
