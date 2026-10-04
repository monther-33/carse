<?php

use App\Models\User;
use Livewire\Volt\Volt;
use Spatie\Activitylog\Models\Activity;

test('login screen can be rendered', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSeeVolt('pages.auth.login');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
    expect(Activity::query()->where('log_name', 'auth')->where('event', 'login')->where('causer_id', $user->id)->exists())->toBeTrue();
});

test('users can not authenticate with invalid password and the attempt is audited', function () {
    $user = User::factory()->create();

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password')
        ->call('login')
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();

    $failed = Activity::query()->where('log_name', 'auth')->where('event', 'login_failed')->latest('id')->first();
    expect($failed)->not->toBeNull()
        ->and($failed->properties['email'])->toBe($user->email);
});

test('inactive users cannot log in', function () {
    $user = User::factory()->inactive()->create();

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.email' => __('app.auth.inactive')]);

    $this->assertGuest();
});

test('a user deactivated mid-session is logged out on the next request', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $user->update(['is_active' => false]);

    $this->get('/dashboard')->assertRedirect(route('login'));
    $this->assertGuest();
});

test('dashboard renders the RTL layout with the sidebar', function () {
    $this->actingAs(User::factory()->role('admin')->create());

    $this->get('/dashboard')
        ->assertOk()
        ->assertSee('dir="rtl"', false)
        ->assertSee(__('app.nav.accounts'));
});

test('users can logout', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/logout')->assertRedirect('/');

    $this->assertGuest();
});

test('there is no self registration', function () {
    $this->get('/register')->assertNotFound();
});
