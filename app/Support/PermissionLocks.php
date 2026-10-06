<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

/**
 * Permissions the developer has locked away from everyone else, the admin included
 * (owner's request). A locked permission is refused by the Gate for every user who is not
 * the developer, so its menu items, buttons, routes and Livewire actions all disappear.
 *
 * Stored as a JSON list in settings ("system.locked_permissions"); the developer-only
 * permissions themselves can never be locked.
 */
class PermissionLocks
{
    public const KEY = 'system.locked_permissions';

    public const DEVELOPER_ROLE = 'developer';

    /** @var list<string>|null */
    private ?array $locked = null;

    public function __construct(private readonly Settings $settings) {}

    /** @return list<string> */
    public function locked(): array
    {
        if ($this->locked === null) {
            $value = json_decode((string) $this->settings->get(self::KEY, '[]'), true);
            $this->locked = array_values(array_filter(is_array($value) ? $value : [], 'is_string'));
        }

        return $this->locked;
    }

    public function isLocked(string $permission): bool
    {
        return in_array($permission, $this->locked(), true);
    }

    /**
     * Every permission that may be locked: the whole catalogue except the developer-only ones.
     *
     * @return list<string>
     */
    public static function lockable(): array
    {
        $all = [];
        foreach (config('permissions.permissions') as $module => $actions) {
            foreach ($actions as $action) {
                $all[] = "{$module}.{$action}";
            }
        }

        return array_values(array_diff($all, config('permissions.developer_only')));
    }

    /**
     * @param  list<string>  $permissions
     */
    public function set(array $permissions): void
    {
        $before = $this->locked();
        $locked = array_values(array_intersect(self::lockable(), $permissions));

        $this->settings->set([self::KEY => json_encode($locked)]);
        $this->locked = $locked;
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        activity('System')->causedBy(Auth::user())->event('permissions_locked')
            ->withProperties(['old' => $before, 'attributes' => $locked])
            ->log('permissions_locked');
    }

    public static function isDeveloper(User $user): bool
    {
        return $user->isDeveloper();
    }
}
