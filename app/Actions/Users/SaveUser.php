<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

class SaveUser
{
    /**
     * @param  array{name: string, email: string, password?: string|null, branch_id: int, max_discount: string, is_active: bool, roles: list<string>, cashbox_ids: list<int>}  $data
     */
    public function handle(array $data, ?User $user = null): User
    {
        return DB::transaction(function () use ($data, $user) {
            // Accounts are created by the admin, so the email counts as verified.
            $user ??= (new User)->forceFill(['email_verified_at' => now()]);

            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'branch_id' => $data['branch_id'],
                'max_discount' => (string) Money::of($data['max_discount']),
                'is_active' => $data['is_active'],
            ]);

            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }

            $user->save();
            $user->syncRoles($data['roles']);
            $user->cashboxes()->sync($data['cashbox_ids']);

            activity('User')->performedOn($user)->event('roles_synced')
                ->withProperties(['roles' => $data['roles'], 'cashboxes' => $data['cashbox_ids']])
                ->log('roles_synced');

            return $user;
        });
    }

    public function toggleActive(User $user): User
    {
        $user->update(['is_active' => ! $user->is_active]);

        return $user;
    }
}
