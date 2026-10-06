<?php

use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\System\Locks;
use App\Livewire\Users\Index as UsersIndex;
use App\Models\Branch;
use App\Support\PermissionLocks;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->developer = userWithRole('developer');
    $this->admin = userWithRole('admin');
});

test('only the developer holds and reaches the locks screen', function () {
    expect($this->developer->can('system.locks'))->toBeTrue()
        ->and($this->admin->can('system.locks'))->toBeFalse()
        ->and(Role::findByName('admin')->hasPermissionTo('system.locks'))->toBeFalse();

    $this->actingAs($this->admin)->get(route('system.locks'))->assertForbidden();
    $this->actingAs($this->developer)->get(route('system.locks'))->assertOk()->assertSee(__('system.intro_title'));
});

test('a locked permission is refused to the admin everywhere, and the developer keeps it', function () {
    $this->actingAs($this->developer);
    Livewire::test(Locks::class)->set('locked', ['journal.view', 'periods.close'])->call('save')->assertHasNoErrors();

    expect($this->admin->fresh()->can('journal.view'))->toBeFalse()
        ->and($this->admin->fresh()->can('periods.close'))->toBeFalse()
        ->and($this->developer->fresh()->can('journal.view'))->toBeTrue();

    $this->actingAs($this->admin)->get(route('journals.index'))->assertForbidden();
    $this->actingAs($this->admin)->get(route('dashboard'))->assertDontSee(route('journals.index'));
    $this->actingAs($this->developer)->get(route('journals.index'))->assertOk();

    // Unlocking gives it back.
    $this->actingAs($this->developer);
    Livewire::test(Locks::class)->set('locked', [])->call('save');
    expect($this->admin->fresh()->can('journal.view'))->toBeTrue();
    $this->actingAs($this->admin)->get(route('journals.index'))->assertOk();
});

test('locking applies to every role, and the lock itself cannot be locked; changes are audited', function () {
    $accountant = userWithRole('accountant');
    $this->actingAs($this->developer);

    Livewire::test(Locks::class)->set('locked', ['system.locks'])->call('save')->assertHasErrors('locked.0');
    Livewire::test(Locks::class)->set('locked', ['vouchers.approve'])->call('save')->assertHasNoErrors();

    expect($accountant->fresh()->can('vouchers.approve'))->toBeFalse()
        ->and(app(PermissionLocks::class)->locked())->toBe(['vouchers.approve'])
        ->and(Activity::query()->where('event', 'permissions_locked')->latest('id')->first()->properties['attributes'])->toBe(['vouchers.approve']);
});

test('the admin cannot see, edit, deactivate or create developers, nor touch the developer role', function () {
    $this->actingAs($this->admin);

    Livewire::test(UsersIndex::class)->assertDontSee($this->developer->username);
    Livewire::test(UsersIndex::class)->call('edit', $this->developer->id)->assertForbidden();
    Livewire::test(UsersIndex::class)->call('toggleActive', $this->developer->id)->assertForbidden();
    Livewire::test(UsersIndex::class)->call('create')
        ->set('name', 'x')->set('username', 'sneaky')->set('password', 'secret-pass-1')
        ->set('branch_id', Branch::query()->value('id'))->set('roles', ['developer'])
        ->call('save')->assertHasErrors('roles.0');

    Livewire::test(RolesIndex::class)->assertDontSee(__('permissions.roles.developer'))
        ->call('select', Role::findByName('developer')->id)->assertNotFound();

    // The developer sees and manages developer accounts.
    $this->actingAs($this->developer);
    Livewire::test(UsersIndex::class)->assertSee($this->developer->username);
});

test('a role saved from the roles screen never gets developer-only permissions', function () {
    $this->actingAs($this->developer);
    $role = Role::findByName('accountant');

    Livewire::test(RolesIndex::class)->call('select', $role->id)
        ->set('permissions', ['vouchers.view', 'system.locks'])->call('save');

    expect($role->fresh()->permissions->pluck('name')->all())->toBe(['vouchers.view']);
});
