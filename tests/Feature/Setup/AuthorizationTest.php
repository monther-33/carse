<?php

use App\Livewire\Accounts\Index as AccountsIndex;
use App\Livewire\Cashboxes\Index as CashboxesIndex;
use App\Livewire\FiscalPeriods\Index as PeriodsIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Models\FiscalPeriod;
use Livewire\Livewire;

dataset('screens', [
    'accounts' => ['/accounts', ['admin', 'accountant']],
    'cashboxes' => ['/cashboxes', ['admin', 'accountant', 'cashier']],
    'periods' => ['/periods', ['admin', 'accountant']],
    'currencies' => ['/currencies', ['admin', 'accountant']],
    'brands' => ['/references/brands', ['admin', 'purchasing']],
    'colors' => ['/references/colors', ['admin', 'purchasing']],
    'locations' => ['/references/locations', ['admin', 'purchasing']],
    'branches' => ['/branches', ['admin']],
    'settings' => ['/settings', ['admin']],
    'users' => ['/users', ['admin']],
    'roles' => ['/roles', ['admin']],
]);

test('screens are enforced server-side per role', function (string $url, array $allowed) {
    foreach (['admin', 'accountant', 'cashier', 'sales', 'purchasing'] as $role) {
        $response = $this->actingAs(userWithRole($role))->get($url);

        in_array($role, $allowed, true)
            ? $response->assertOk()
            : $response->assertForbidden();
    }
})->with('screens');

test('guests are redirected to login', function () {
    $this->get('/accounts')->assertRedirect(route('login'));
});

test('Livewire actions re-check permissions, not only the page load', function () {
    $this->actingAs(userWithRole('accountant'));

    // The accountant can view periods but closing is admin-only.
    $period = FiscalPeriod::query()->containing(today())->first();
    Livewire::test(PeriodsIndex::class)->call('close', $period->id)->assertForbidden();
    expect($period->fresh()->is_closed)->toBeFalse();

    // Cashier sees cashboxes, but cannot create or edit them.
    $this->actingAs(userWithRole('cashier'));
    Livewire::test(CashboxesIndex::class)->call('create')->assertForbidden();

    // Sales cannot open the users or accounts components at all.
    $this->actingAs(userWithRole('sales'));
    Livewire::test(UsersIndex::class)->assertForbidden();
    Livewire::test(AccountsIndex::class)->assertForbidden();
});

test('a treasurer only sees the cashboxes assigned to them', function () {
    $cashier = userWithRole('cashier');
    $cashier->cashboxes()->attach(cashbox('خزينة دينار'));

    $this->actingAs($cashier);

    Livewire::test(CashboxesIndex::class)
        ->assertSee('خزينة دينار')
        ->assertDontSee('خزينة دولار')
        ->assertDontSee('حساب مصرفي رئيسي');

    expect($cashier->can('view', cashbox('خزينة دينار')))->toBeTrue()
        ->and($cashier->can('view', cashbox('خزينة دولار')))->toBeFalse();

    $this->actingAs(userWithRole('accountant'));
    Livewire::test(CashboxesIndex::class)->assertSee('خزينة دولار');
});

test('the sidebar only lists screens the user may open', function () {
    $this->actingAs(userWithRole('sales'))->get('/dashboard')
        ->assertOk()
        ->assertDontSee(__('app.nav.users'))
        ->assertDontSee(__('app.nav.accounts'));

    $this->actingAs(userWithRole('admin'))->get('/dashboard')
        ->assertSee(__('app.nav.users'))
        ->assertSee(__('app.nav.accounts'));
});
