<?php

namespace App\Actions\Alerts;

use App\Models\User;
use App\Notifications\DailyAlerts;
use App\Services\Alerts\AlertDigest;

/**
 * Sends each active user today's digest. An unread digest from an earlier run is replaced,
 * so running the command twice does not pile up alerts under the bell.
 */
class SendDailyAlerts
{
    public function __construct(private readonly AlertDigest $digest) {}

    /** @return int the number of users notified */
    public function handle(): int
    {
        $sent = 0;

        User::query()->where('is_active', true)->with('roles.permissions', 'permissions')->each(function (User $user) use (&$sent) {
            $sections = $this->digest->for($user);

            $user->unreadNotifications()->where('type', DailyAlerts::class)->delete();

            if ($sections !== []) {
                $user->notify(new DailyAlerts($sections));
                $sent++;
            }
        });

        return $sent;
    }
}
