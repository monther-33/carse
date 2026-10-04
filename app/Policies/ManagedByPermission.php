<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Policy for setup tables where one "<module>.manage" permission covers every action.
 */
abstract class ManagedByPermission
{
    abstract protected function permission(): string;

    public function viewAny(User $user): bool
    {
        return $user->can($this->permission());
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can($this->permission());
    }

    public function create(User $user): bool
    {
        return $user->can($this->permission());
    }

    public function update(User $user, Model $model): bool
    {
        return $user->can($this->permission());
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->can($this->permission());
    }
}
