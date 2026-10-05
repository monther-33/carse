<?php

use App\Actions\Alerts\SendDailyAlerts;
use App\Actions\Reservations\CreateReservation;
use App\Livewire\Notifications\Bell;
use App\Livewire\Notifications\Index;
use App\Notifications\DailyAlerts;
use App\Services\Alerts\AlertDigest;
use Illuminate\Console\Scheduling\Schedule;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));

    // Installments: one overdue (5 days), one due in 3 days, the rest later.
    $sale = sell(purchaseVehicle('20000'), [
        'party_id' => customer()->id, 'price' => '24000', 'payment_type' => 'installment',
        'installment' => ['down_payment' => '0', 'months' => 4, 'guarantor' => ['name' => 'كفيل']],
    ]);
    $installments = $sale->installmentPlan->installments()->orderBy('due_date')->get();
    $installments[0]->update(['due_date' => today()->subDays(5)]);
    $installments[1]->update(['due_date' => today()->addDays(3)]);

    // A reservation ending in two days.
    app(CreateReservation::class)->handle([
        'vehicle_id' => purchaseVehicle('30000')->id, 'party_id' => customer()->id,
        'cashbox_id' => cashbox('خزينة دينار')->id, 'deposit' => '1000',
        'expires_at' => today()->addDays(2)->toDateString(),
    ]);

    // Stale stock: 70 days (warning) and 100 days (critical).
    purchaseVehicle('25000')->update(['received_at' => today()->subDays(70)]);
    purchaseVehicle('26000')->update(['received_at' => today()->subDays(100)]);
});

test('the digest has every section for the admin, with figures', function () {
    $sections = collect(app(AlertDigest::class)->for(auth()->user()))->keyBy('key');

    expect($sections->keys()->all())->toBe(['installments_overdue', 'installments_week', 'reservations_expiring', 'stale_critical', 'stale_warning'])
        ->and($sections['installments_overdue']['count'])->toBe(1)
        ->and($sections['installments_overdue']['amount'])->toBe('6000.000')
        ->and($sections['installments_week']['count'])->toBe(1)
        ->and($sections['stale_critical']['count'])->toBe(1)
        ->and($sections['stale_warning']['count'])->toBe(1);
});

test('each role is told only what it may see', function () {
    $keys = fn (string $role) => array_column(app(AlertDigest::class)->for(userWithRole($role)), 'key');

    expect($keys('cashier'))->toBe(['installments_overdue', 'installments_week'])
        ->and($keys('sales'))->toBe(['reservations_expiring'])
        ->and($keys('purchasing'))->toBe(['stale_critical', 'stale_warning']);
});

test('the daily command notifies users once, replacing an unread digest instead of piling up', function () {
    $admin = auth()->user();
    $cashier = userWithRole('cashier');

    $this->artisan('alerts:daily')->assertSuccessful();
    app(SendDailyAlerts::class)->handle();

    expect($admin->unreadNotifications()->where('type', DailyAlerts::class)->count())->toBe(1)
        ->and($cashier->unreadNotifications()->count())->toBe(1);

    // Read digests are kept as history; the next run adds a new one.
    $admin->unreadNotifications()->update(['read_at' => now()]);
    app(SendDailyAlerts::class)->handle();
    expect($admin->notifications()->count())->toBe(2);
});

test('the bell shows the unread count and the alerts in the reader language', function () {
    app(SendDailyAlerts::class)->handle();

    Livewire::test(Bell::class)
        ->assertSee(__('notifications.alerts.installments_overdue', ['count' => 1, 'amount' => '6,000.000']));

    Livewire::test(Index::class)->call('markAllRead');
    expect(auth()->user()->unreadNotifications()->count())->toBe(0);

    $this->get(route('notifications.index'))->assertOk();
});

test('the alerts and the reservation expiry are scheduled daily', function () {
    $commands = collect(app(Schedule::class)->events())->map(fn ($event) => $event->command)->implode("\n");

    expect($commands)->toContain('alerts:daily')->toContain('reservations:expire');
});
