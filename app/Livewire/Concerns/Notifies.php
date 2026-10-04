<?php

namespace App\Livewire\Concerns;

/**
 * Toast messages rendered by <x-ui.toasts />.
 */
trait Notifies
{
    protected function notify(string $message): void
    {
        $this->dispatch('notify', message: $message, type: 'success');
    }

    protected function notifyError(string $message): void
    {
        $this->dispatch('notify', message: $message, type: 'error');
    }
}
