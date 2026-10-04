<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BranchPolicy extends ManagedByPermission
{
    protected function permission(): string
    {
        return 'branches.manage';
    }

    /**
     * Branches own users, cashboxes and documents: deactivate instead.
     */
    public function delete(User $user, Model $model): bool
    {
        return false;
    }
}
