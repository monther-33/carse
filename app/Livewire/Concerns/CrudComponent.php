<?php

namespace App\Livewire\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Base for simple setup screens (list + modal form + soft delete). Authorization goes
 * through the model's policy on every action, not just on mount.
 */
abstract class CrudComponent extends Component
{
    use Notifies, WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    /** @return class-string<Model> */
    abstract protected function modelClass(): string;

    /** @return array<string, mixed> */
    abstract protected function defaults(): array;

    /** @return array<string, mixed> Validation rules keyed by "form.field". */
    abstract protected function formRules(): array;

    /** @return list<string> */
    protected function searchColumns(): array
    {
        return ['name'];
    }

    /** @return Builder<covariant Model> */
    protected function query(): Builder
    {
        return $this->modelClass()::query();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', $this->modelClass());
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', $this->modelClass());
        $this->editingId = null;
        $this->form = $this->defaults();
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $model = $this->modelClass()::query()->findOrFail($id);
        $this->authorize('update', $model);

        $this->editingId = $id;
        $this->form = array_intersect_key($model->attributesToArray(), $this->defaults());
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(): void
    {
        $model = $this->editingId ? $this->modelClass()::query()->findOrFail($this->editingId) : null;
        $model ? $this->authorize('update', $model) : $this->authorize('create', $this->modelClass());

        $data = $this->validate($this->formRules())['form'];

        $model ? $model->update($data) : $this->modelClass()::query()->create($data);

        $this->showForm = false;
        $this->notify(__('app.saved'));
    }

    public function delete(int $id): void
    {
        $model = $this->modelClass()::query()->findOrFail($id);
        $this->authorize('delete', $model);

        $model->delete();
        $this->notify(__('app.deleted'));
    }

    /** @return LengthAwarePaginator<int, Model> */
    protected function records(): LengthAwarePaginator
    {
        return $this->query()
            ->when($this->search !== '', function (Builder $q) {
                $q->where(function (Builder $q) {
                    foreach ($this->searchColumns() as $column) {
                        $q->orWhere($column, 'like', '%'.$this->search.'%');
                    }
                });
            })
            ->orderBy('name')
            ->paginate(20);
    }
}
