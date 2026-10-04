<?php

namespace App\Livewire\Roles;

use App\Actions\Users\SaveRolePermissions;
use App\Livewire\Concerns\Notifies;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies;

    public ?int $roleId = null;

    /** @var list<string> */
    public array $permissions = [];

    public string $newRole = '';

    public function mount(): void
    {
        $this->authorize('roles.manage');
        $this->select((int) Role::query()->orderBy('id')->value('id'));
    }

    public function select(int $roleId): void
    {
        $this->authorize('roles.manage');

        $role = Role::query()->with('permissions')->findOrFail($roleId);
        $this->roleId = $role->id;
        $this->permissions = $role->permissions->pluck('name')->all();
        $this->resetValidation();
    }

    public function save(SaveRolePermissions $action): void
    {
        $this->authorize('roles.manage');

        $this->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $action->handle(Role::query()->findOrFail($this->roleId), $this->permissions);
        $this->notify(__('app.saved'));
    }

    public function createRole(SaveRolePermissions $action): void
    {
        $this->authorize('roles.manage');

        $this->validate(['newRole' => ['required', 'string', 'max:100', 'unique:roles,name']]);

        $role = $action->create($this->newRole);
        $this->newRole = '';
        $this->select($role->id);
        $this->notify(__('app.saved'));
    }

    public function deleteRole(SaveRolePermissions $action): void
    {
        $this->authorize('roles.manage');

        $action->delete(Role::query()->findOrFail($this->roleId));
        $this->select((int) Role::query()->orderBy('id')->value('id'));
        $this->notify(__('app.deleted'));
    }

    public function render(): View
    {
        $roles = Role::query()->withCount('users')->orderBy('id')->get();

        return view('livewire.roles.index', [
            'roles' => $roles,
            'current' => $roles->firstWhere('id', $this->roleId),
            'modules' => config('permissions.permissions'),
            'isAdmin' => $roles->firstWhere('id', $this->roleId)?->name === SaveRolePermissions::ADMIN_ROLE,
            'isSystem' => array_key_exists((string) $roles->firstWhere('id', $this->roleId)?->name, config('permissions.roles')),
        ])->title(__('app.nav.roles'));
    }
}
