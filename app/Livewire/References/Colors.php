<?php

namespace App\Livewire\References;

use App\Livewire\Concerns\CrudComponent;
use App\Models\Color;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Colors extends CrudComponent
{
    protected function modelClass(): string
    {
        return Color::class;
    }

    protected function defaults(): array
    {
        return ['name' => '', 'hex' => '#FFFFFF'];
    }

    protected function formRules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:100', Rule::unique('colors', 'name')->ignore($this->editingId)],
            'form.hex' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function render(): View
    {
        return view('livewire.references.colors', ['records' => $this->records()])
            ->title(__('app.nav.colors'));
    }
}
