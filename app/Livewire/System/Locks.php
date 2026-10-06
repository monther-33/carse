<?php

namespace App\Livewire\System;

use App\Livewire\Concerns\Notifies;
use App\Support\PermissionLocks;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Developer only: lock permissions away from everyone else, the admin included.
 */
#[Layout('layouts.app')]
class Locks extends Component
{
    use Notifies;

    /** @var list<string> */
    public array $locked = [];

    public function mount(PermissionLocks $locks): void
    {
        $this->authorize('system.locks');
        $this->locked = $locks->locked();
    }

    public function save(PermissionLocks $locks): void
    {
        $this->authorize('system.locks');
        $this->validate([
            'locked' => ['array'],
            'locked.*' => ['string', Rule::in(PermissionLocks::lockable())],
        ]);

        $locks->set($this->locked);
        $this->locked = $locks->locked();
        $this->notify(__('system.saved', ['count' => count($this->locked)]));
    }

    public function render(): View
    {
        $modules = [];
        foreach (config('permissions.permissions') as $module => $actions) {
            $lockable = array_values(array_intersect(array_map(fn ($a) => "{$module}.{$a}", $actions), PermissionLocks::lockable()));
            if ($lockable !== []) {
                $modules[$module] = $lockable;
            }
        }

        return view('livewire.system.locks', ['modules' => $modules])->title(__('app.nav.system_locks'));
    }
}
