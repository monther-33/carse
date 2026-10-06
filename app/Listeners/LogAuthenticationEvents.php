<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;

/**
 * Writes logins, logouts and failed login attempts to the audit log (log name "auth").
 */
class LogAuthenticationEvents
{
    public function login(Login $event): void
    {
        $this->log('login', $event->user instanceof Model ? $event->user : null);
    }

    public function logout(Logout $event): void
    {
        $this->log('logout', $event->user instanceof Model ? $event->user : null);
    }

    public function failed(Failed $event): void
    {
        $this->log('login_failed', $event->user instanceof Model ? $event->user : null, [
            'username' => $event->credentials['username'] ?? null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    private function log(string $event, ?Model $user, array $extra = []): void
    {
        $logger = activity('auth')
            ->event($event)
            ->withProperties($extra + ['ip' => request()->ip(), 'user_agent' => request()->userAgent()]);

        if ($user !== null) {
            $logger->performedOn($user)->causedBy($user);
        }

        $logger->log($event);
    }
}
