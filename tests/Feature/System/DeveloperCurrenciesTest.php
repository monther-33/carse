<?php

use App\Livewire\Currencies\Index;
use App\Models\Currency;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('lets only the developer add currencies', function () {
    $this->actingAs(userWithRole('developer'));

    Livewire::test(Index::class)
        ->call('create')
        ->set('code', 'EUR')
        ->set('name', 'يورو')
        ->set('symbol', '€')
        ->set('decimals', 2)
        ->call('save')
        ->assertHasNoErrors();

    expect(Currency::query()->where('code', 'EUR')->exists())->toBeTrue();
});

it('keeps exchange rates with the admin and the accountant but not currencies', function (string $role) {
    $this->actingAs(userWithRole($role));

    expect(auth()->user()->can('currencies.manage'))->toBeFalse()
        ->and(auth()->user()->can('exchange_rates.manage'))->toBeTrue();

    $this->get(route('currencies.index'))->assertOk();
    Livewire::test(Index::class)->call('create')->assertForbidden();
})->with(['admin', 'accountant']);

it('denies a developer-only permission even when a role was given it', function () {
    $role = Role::findOrCreate('legacy', 'web');
    $role->givePermissionTo('currencies.manage');
    $user = userWithRole('cashier');
    $user->assignRole($role);

    expect($user->can('currencies.manage'))->toBeFalse();
});
