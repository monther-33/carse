<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\BackupProblem;
use Illuminate\Support\Facades\Notification;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\CleanupHasFailed;
use Spatie\Backup\Events\UnhealthyBackupWasFound;

/**
 * Tells the users who manage backups (under the bell) when a backup or cleanup fails or
 * the monitor finds the backups unhealthy (too old, too large).
 */
class NotifyBackupProblems
{
    public function failed(BackupHasFailed $event): void
    {
        $this->send('failed', $event->exception->getMessage());
    }

    public function cleanupFailed(CleanupHasFailed $event): void
    {
        $this->send('cleanup_failed', $event->exception->getMessage());
    }

    public function unhealthy(UnhealthyBackupWasFound $event): void
    {
        $failure = $event->backupDestinationStatus->getHealthCheckFailure();

        $this->send('unhealthy', $failure?->exception()->getMessage() ?? '');
    }

    private function send(string $kind, string $message): void
    {
        $admins = User::query()->where('is_active', true)->get()
            ->filter(fn (User $user) => $user->can('backups.manage'));

        Notification::send($admins, new BackupProblem($kind, $message));
    }
}
