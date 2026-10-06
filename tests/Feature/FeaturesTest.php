<?php

use App\Actions\Reservations\CreateReservation;
use App\Exceptions\BusinessRuleException;
use App\Livewire\Sales\Form as SalesForm;
use App\Livewire\Settings\Features as FeaturesScreen;
use App\Models\Commission;
use App\Models\User;
use App\Reports\ReportRegistry;
use App\Support\Features;
use App\Support\Navigation;
use App\Support\Settings;
use Livewire\Livewire;

function navLabels(User $user): array
{
    return collect(Navigation::for($user))->flatMap(fn ($s) => array_column($s['items'], 'label'))->all();
}

function reportKeys(User $user): array
{
    return collect(ReportRegistry::forUser($user))->flatten()->map(fn ($r) => $r::key())->all();
}

test('switching reservations off hides them everywhere and the server refuses them; on brings them back', function () {
    $admin = userWithRole('admin');
    $this->actingAs($admin);

    Livewire::test(FeaturesScreen::class)->call('toggle', 'reservations')->assertHasNoErrors();
    expect(app(Features::class)->enabled('reservations'))->toBeFalse()
        ->and(navLabels($admin))->not->toContain(__('app.nav.reservations'));

    $this->get(route('reservations.index'))->assertNotFound();
    expect(fn () => app(CreateReservation::class)->handle([
        'vehicle_id' => purchaseVehicle('30000')->id, 'party_id' => customer()->id,
        'cashbox_id' => cashbox('خزينة دينار')->id, 'deposit' => '1000', 'expires_at' => today()->addWeek()->toDateString(),
    ]))->toThrow(BusinessRuleException::class);

    Livewire::test(FeaturesScreen::class)->call('toggle', 'reservations');
    expect(navLabels($admin))->toContain(__('app.nav.reservations'));
    $this->get(route('reservations.index'))->assertOk();
});

test('a feature with open business cannot be switched off', function () {
    $this->actingAs(userWithRole('admin'));
    app(CreateReservation::class)->handle([
        'vehicle_id' => purchaseVehicle('30000')->id, 'party_id' => customer()->id,
        'cashbox_id' => cashbox('خزينة دينار')->id, 'deposit' => '1000', 'expires_at' => today()->addWeek()->toDateString(),
    ]);
    sell(purchaseVehicle('20000'), [
        'party_id' => customer()->id, 'price' => '24000', 'payment_type' => 'installment',
        'installment' => ['down_payment' => '0', 'months' => 4, 'guarantor' => ['name' => 'كفيل']],
    ]);

    $features = app(Features::class);
    expect($features->blocker('reservations'))->toContain('1')
        ->and($features->blocker('installments'))->toContain('4')
        ->and($features->blocker('trade_in'))->toBeNull()
        ->and(fn () => $features->set('installments', false))->toThrow(BusinessRuleException::class);

    Livewire::test(FeaturesScreen::class)->call('toggle', 'reservations')->assertHasErrors('feature_reservations');
    expect($features->enabled('reservations'))->toBeTrue();
});

test('installments and trade-ins off: gone from the sales form and refused when saving', function () {
    $this->actingAs(userWithRole('admin'));
    $features = app(Features::class);
    $features->set('installments', false);
    $features->set('trade_in', false);

    Livewire::test(SalesForm::class)
        ->assertDontSee(__('enums.payment_type.installment'))
        ->assertDontSee(__('sales.has_trade_in'));

    expect(fn () => saveSale(purchaseVehicle('20000'), [
        'party_id' => customer()->id, 'price' => '24000', 'payment_type' => 'installment',
        'installment' => ['down_payment' => '0', 'months' => 4, 'guarantor' => ['name' => 'كفيل']],
    ]))->toThrow(BusinessRuleException::class);

    expect(reportKeys(auth()->user()))->not->toContain('installments');
});

test('commissions off: sales create no commission and the commission screens disappear', function () {
    $this->actingAs(userWithRole('admin'));
    app(Settings::class)->set(['sales.commission_type' => 'percent', 'sales.commission_value' => '1']);
    app(Features::class)->set('commissions', false);

    sell(purchaseVehicle('20000'), ['price' => '25000']);

    expect(Commission::query()->count())->toBe(0)
        ->and(navLabels(auth()->user()))->not->toContain(__('app.nav.commissions'))
        ->and(reportKeys(auth()->user()))->not->toContain('commissions');
    $this->get(route('commissions.index'))->assertNotFound();
});

test('only settings managers reach the features screen', function () {
    $this->actingAs(userWithRole('accountant'))->get(route('settings.features'))->assertForbidden();
    $this->actingAs(userWithRole('admin'))->get(route('settings.features'))->assertOk()->assertSee(__('features.names.trade_in'));
});
