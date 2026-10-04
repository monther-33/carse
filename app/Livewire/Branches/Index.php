<?php

namespace App\Livewire\Branches;

use App\Livewire\Concerns\CrudComponent;
use App\Models\Branch;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends CrudComponent
{
    protected function modelClass(): string
    {
        return Branch::class;
    }

    protected function defaults(): array
    {
        return ['code' => '', 'name' => '', 'phone' => '', 'address' => '', 'is_active' => true];
    }

    protected function searchColumns(): array
    {
        return ['name', 'code'];
    }

    protected function formRules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:20', 'alpha_dash', Rule::unique('branches', 'code')->ignore($this->editingId)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.address' => ['nullable', 'string', 'max:255'],
            'form.is_active' => ['boolean'],
        ];
    }

    /**
     * Branches own users, cashboxes and documents; they are deactivated, never deleted.
     */
    public function delete(int $id): void
    {
        abort(403);
    }

    public function render(): View
    {
        return view('livewire.branches.index', ['records' => $this->records()])
            ->title(__('app.nav.branches'));
    }
}
