<?php

namespace App\Livewire\Notifications;

use App\Livewire\Concerns\Notifies;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * The user's own notifications. Every user may see theirs; nothing else is shown.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies, WithPagination;

    public function markRead(string $id): void
    {
        auth()->user()->notifications()->whereKey($id)->firstOrFail()->markAsRead();
        $this->dispatch('notifications-changed');
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->dispatch('notifications-changed');
        $this->notify(__('notifications.all_read'));
    }

    public function render(): View
    {
        return view('livewire.notifications.index', [
            'notifications' => auth()->user()->notifications()->latest()->paginate(20),
        ])->title(__('app.nav.notifications'));
    }
}
