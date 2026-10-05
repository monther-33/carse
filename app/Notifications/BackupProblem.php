<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * A failed or unhealthy backup, shown under the bell to whoever manages backups.
 */
class BackupProblem extends Notification
{
    public const KINDS = [
        'failed' => 'notifications.backup.failed',
        'unhealthy' => 'notifications.backup.unhealthy',
        'cleanup_failed' => 'notifications.backup.cleanup_failed',
    ];

    public function __construct(
        public readonly string $kind,
        public readonly string $message,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array{kind: string, message: string} */
    public function toArray(object $notifiable): array
    {
        return ['kind' => $this->kind, 'message' => mb_substr($this->message, 0, 500)];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<array{text: string, url: string, color: string}>
     */
    public static function lines(array $data): array
    {
        $key = self::KINDS[$data['kind'] ?? ''] ?? self::KINDS['failed'];

        return [[
            'text' => __($key, ['message' => $data['message'] ?? '']),
            'url' => route('backups.index'),
            'color' => 'red',
        ]];
    }
}
