<?php

namespace App\Livewire\System;

use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\TrashItem;
use App\Models\User;
use App\Services\Trash\RecycleBin;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Developer only: the recycle bin. One line per delete operation (batch), restorable as a whole.
 */
#[Layout('layouts.app')]
class Trash extends Component
{
    use HandlesBusinessErrors, Notifies, WithPagination;

    #[Url]
    public string $show = 'pending';

    #[Url]
    public string $q = '';

    public function mount(): void
    {
        $this->authorize('system.trash');
    }

    public function updating(string $name): void
    {
        if (in_array($name, ['show', 'q'], true)) {
            $this->resetPage();
        }
    }

    public function restore(string $batch, RecycleBin $bin): void
    {
        $this->authorize('system.trash');

        $count = $this->attempt(fn () => $bin->restore($batch));
        if ($count !== null) {
            $this->notify(__('trash.restored_ok', ['count' => $count]));
        }
    }

    public function render(): View
    {
        $batches = TrashItem::query()
            ->select('batch', DB::raw('MIN(label) AS label'), DB::raw('COUNT(*) AS rows_count'), DB::raw('MIN(deleted_at) AS deleted_at'),
                DB::raw('MIN(deleted_by) AS deleted_by'), DB::raw('MAX(restored_at) AS restored_at'), DB::raw('MAX(restored_by) AS restored_by'))
            ->when($this->show === 'pending', fn ($q) => $q->whereNull('restored_at'))
            ->when(trim($this->q) !== '', fn ($q) => $q->where('label', 'like', '%'.trim($this->q).'%'))
            ->groupBy('batch')
            ->orderByDesc(DB::raw('MIN(deleted_at)'))
            ->paginate(25);

        $userIds = collect($batches->items())->flatMap(fn ($b) => [$b->deleted_by, $b->restored_by])->filter()->unique();

        return view('livewire.system.trash', [
            'batches' => $batches,
            'users' => User::query()->whereIn('id', $userIds)->pluck('name', 'id'),
        ])->title(__('app.nav.trash'));
    }
}
