<?php

namespace App\Policies\Concerns;

use App\Enums\DocumentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Standard rules for approvable documents, keyed by a permission module:
 * view / create (also edit and delete drafts) / approve (drafts) / cancel (posted).
 */
abstract class DocumentPolicy
{
    abstract protected function module(): string;

    public function viewAny(User $user): bool
    {
        return $user->can($this->module().'.view');
    }

    public function view(User $user, Model $document): bool
    {
        return $user->can($this->module().'.view');
    }

    public function create(User $user): bool
    {
        return $user->can($this->module().'.create');
    }

    public function update(User $user, Model $document): bool
    {
        return $document->getAttribute('status') === DocumentStatus::Draft && $user->can($this->module().'.create');
    }

    public function delete(User $user, Model $document): bool
    {
        return $this->update($user, $document);
    }

    public function approve(User $user, Model $document): bool
    {
        return $document->getAttribute('status') === DocumentStatus::Draft && $user->can($this->module().'.approve');
    }

    public function cancel(User $user, Model $document): bool
    {
        return $document->getAttribute('status') === DocumentStatus::Posted && $user->can($this->module().'.cancel');
    }
}
