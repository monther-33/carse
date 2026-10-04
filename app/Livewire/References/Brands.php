<?php

namespace App\Livewire\References;

use App\Livewire\Concerns\CrudComponent;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

/**
 * Brands with their car models managed in a side panel.
 */
#[Layout('layouts.app')]
class Brands extends CrudComponent
{
    public ?int $brandId = null;

    public string $modelName = '';

    public ?int $editingModelId = null;

    protected function modelClass(): string
    {
        return Brand::class;
    }

    protected function query(): Builder
    {
        return Brand::query()->withCount('models');
    }

    protected function defaults(): array
    {
        return ['name' => ''];
    }

    protected function formRules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:100', Rule::unique('brands', 'name')->ignore($this->editingId)],
        ];
    }

    public function selectBrand(int $id): void
    {
        $this->brandId = $id;
        $this->reset(['modelName', 'editingModelId']);
        $this->resetValidation();
    }

    public function editModel(int $id): void
    {
        $model = CarModel::query()->findOrFail($id);
        $this->authorize('update', $model);

        $this->editingModelId = $model->id;
        $this->modelName = $model->name;
    }

    public function saveModel(): void
    {
        $model = $this->editingModelId ? CarModel::query()->findOrFail($this->editingModelId) : null;
        $model ? $this->authorize('update', $model) : $this->authorize('create', CarModel::class);

        $this->validate([
            'brandId' => ['required', 'exists:brands,id'],
            'modelName' => ['required', 'string', 'max:100',
                Rule::unique('car_models', 'name')->where('brand_id', $this->brandId)->ignore($this->editingModelId)],
        ]);

        $model
            ? $model->update(['name' => $this->modelName])
            : CarModel::query()->create(['brand_id' => $this->brandId, 'name' => $this->modelName]);

        $this->reset(['modelName', 'editingModelId']);
        $this->notify(__('app.saved'));
    }

    public function deleteModel(int $id): void
    {
        $model = CarModel::query()->findOrFail($id);
        $this->authorize('delete', $model);

        $model->delete();
        $this->notify(__('app.deleted'));
    }

    public function render(): View
    {
        return view('livewire.references.brands', [
            'records' => $this->records(),
            'brand' => $this->brandId ? Brand::query()->find($this->brandId) : null,
            'models' => $this->brandId ? CarModel::query()->where('brand_id', $this->brandId)->orderBy('name')->get() : collect(),
        ])->title(__('app.nav.brands'));
    }
}
