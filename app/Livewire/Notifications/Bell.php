<?php

namespace App\Livewire\Notifications;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Topbar bell: unread count and the latest notifications. Refreshes every few minutes.
 */
class Bell extends Component
{
    public function render(): View
    {
        $user = auth()->user();

        return view('livewire.notifications.bell', [
            'unread' => $user->unreadNotifications()->count(),
            'latest' => $user->unreadNotifications()->latest()->limit(5)->get(),
        ]);
    }
}
