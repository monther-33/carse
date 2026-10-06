<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/profile')
        ->assertOk()
        ->assertSeeVolt('profile.update-profile-information-form')
        ->assertSeeVolt('profile.update-password-form');
});

test('users can update their name but not their username', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Volt::test('profile.update-profile-information-form')
        ->set('name', 'Test User')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $fresh = $user->fresh();
    expect($fresh->name)->toBe('Test User')
        ->and($fresh->username)->toBe($user->username);
});
