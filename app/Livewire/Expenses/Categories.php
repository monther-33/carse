<?php

namespace App\Livewire\Expenses;

use App\Livewire\Concerns\CrudComponent;
use App\Models\Account;
use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Categories extends CrudComponent
{
    protected function modelClass(): string
    {
        return ExpenseCategory::class;
    }

    protected function query(): Builder
    {
        return ExpenseCategory::query()->with('account');
    }

    protected function defaults(): array
    {
        return ['name' => '', 'account_id' => null, 'is_active' => true];
    }

    protected function formRules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:100', Rule::unique('expense_categories', 'name')->ignore($this->editingId)],
            'form.account_id' => ['required', Rule::exists('accounts', 'id')->where('is_group', false)],
            'form.is_active' => ['boolean'],
        ];
    }

    public function render(): View
    {
        return view('livewire.expenses.categories', [
            'records' => $this->records(),
            'accounts' => Account::query()->postable()->orderBy('code')->get(),
        ])->title(__('app.nav.expense_categories'));
    }
}
