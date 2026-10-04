<?php

namespace App\Livewire\References;

use App\Livewire\Concerns\CrudComponent;
use App\Models\Branch;
use App\Models\Location;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Locations extends CrudComponent
{
    protected function modelClass(): string
    {
        return Location::class;
    }

    protected function query(): Builder
    {
        return Location::query()->with('branch');
    }

    protected function defaults(): array
    {
        return ['name' => '', 'branch_id' => auth()->user()->branch_id];
    }

    protected function formRules(): array
    {
        return [
            'form.branch_id' => ['required', 'exists:branches,id'],
            'form.name' => ['required', 'string', 'max:100',
                Rule::unique('locations', 'name')->where('branch_id', $this->form['branch_id'] ?? null)->ignore($this->editingId)],
        ];
    }

    public function render(): View
    {
        return view('livewire.references.locations', [
            'records' => $this->records(),
            'branches' => Branch::query()->where('is_active', true)->orderBy('name')->get(),
        ])->title(__('app.nav.locations'));
    }
}
