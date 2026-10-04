<?php

use App\Actions\Reservations\CreateReservation;
use App\Actions\Reservations\EndReservation;
use App\Actions\Reservations\ManageReservation;
use App\Actions\Reservations\SettleDeposit;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Livewire\Reservations\Index as ReservationsIndex;
use App\Models\Reservation;
use App\Services\Sales\DepositService;
use App\Support\Settings;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
    $this->customer = customer();
    $this->vehicle = purchaseVehicle('30000');
    $this->reserve = fn (string $deposit = '3000', $vehicle = null) => app(CreateReservation::class)->handle([
        'vehicle_id' => ($vehicle ?? $this->vehicle)->id, 'party_id' => $this->customer->id,
        'cashbox_id' => cashbox('خزينة دينار')->id, 'deposit' => $deposit,
        'expires_at' => today()->addWeek()->toDateString(),
    ]);
    $this->credit = fn () => (string) app(DepositService::class)->availableCredit($this->customer->id, lyd()->id);
});

test('cancel with part refunded, part forfeited and the rest kept as credit', function () {
    $reservation = ($this->reserve)('3000');

    app(EndReservation::class)->cancel($reservation, 'عدل عن الشراء', refund: '1000', cashboxId: cashbox('خزينة دينار')->id, forfeit: '500');

    $reservation->refresh();
    expect($reservation->status)->toBe(ReservationStatus::Cancelled)
        ->and((string) $reservation->forfeited_amount)->toBe('500.000')
        ->and((string) app(DepositService::class)->remaining($reservation))->toBe('1500.000')
        ->and(($this->credit)())->toBe('1500.000')
        ->and((string) baseBalance('42'))->toBe('-500.000')                         // forfeited → other revenue
        ->and((string) baseBalance(cashbox('خزينة دينار')->account))->toBe('2000.000') // 3,000 in − 1,000 out
        ->and($this->vehicle->fresh()->status)->toBe(VehicleStatus::Available)
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('the remaining deposit can be settled later, but never more than is left', function () {
    $reservation = ($this->reserve)('2000');
    app(EndReservation::class)->cancel($reservation, 'إلغاء');

    expect(fn () => app(SettleDeposit::class)->handle($reservation, refund: '2500', cashboxId: cashbox('خزينة دينار')->id))
        ->toThrow(BusinessRuleException::class);

    app(SettleDeposit::class)->handle($reservation, refund: '1200', cashboxId: cashbox('خزينة دينار')->id);
    app(SettleDeposit::class)->handle($reservation, forfeit: '800');

    expect(($this->credit)())->toBe('0.000')
        ->and((string) app(DepositService::class)->remaining($reservation->fresh()))->toBe('0.000')
        ->and(fn () => app(SettleDeposit::class)->handle($reservation, forfeit: '1'))->toThrow(BusinessRuleException::class);
});

test('a refund needs a cashbox and an amount', function () {
    $reservation = ($this->reserve)();

    expect(fn () => app(SettleDeposit::class)->handle($reservation, refund: '100'))->toThrow(BusinessRuleException::class)
        ->and(fn () => app(SettleDeposit::class)->handle($reservation))->toThrow(BusinessRuleException::class);
});

test('add to the deposit, extend, and move the reservation to another car', function () {
    $reservation = ($this->reserve)('1000');
    $other = purchaseVehicle('40000');
    $manage = app(ManageReservation::class);

    $manage->topUp($reservation, cashbox('خزينة دينار')->id, '1500');
    $manage->extend($reservation, today()->addMonth()->toDateString());
    $manage->changeVehicle($reservation, $other->id);

    $reservation->refresh();
    expect((string) $reservation->deposit)->toBe('2500.000')
        ->and((string) app(DepositService::class)->remaining($reservation))->toBe('2500.000')
        ->and($reservation->expires_at->toDateString())->toBe(today()->addMonth()->toDateString())
        ->and($reservation->vehicle_id)->toBe($other->id)
        ->and($this->vehicle->fresh()->status)->toBe(VehicleStatus::Available)
        ->and($other->fresh()->status)->toBe(VehicleStatus::Reserved);

    // The moved deposit is used when the new car is sold.
    $sale = sell($other, ['party_id' => $this->customer->id, 'reservation_id' => $reservation->id, 'price' => '45000', 'deposit_applied' => '2500']);
    expect((string) $sale->deposit_applied)->toBe('2500.000')
        ->and((string) baseBalance('13'))->toBe('42500.000');
});

test('credit from an old cancelled reservation can be applied, in part, to any later sale', function () {
    $reservation = ($this->reserve)('3000');
    app(EndReservation::class)->cancel($reservation, 'اختار سيارة أخرى');

    $another = purchaseVehicle('20000');
    $sale = sell($another, ['party_id' => $this->customer->id, 'price' => '25000', 'deposit_applied' => '2000']);

    expect((string) $sale->deposit_applied)->toBe('2000.000')
        ->and((string) baseBalance('13'))->toBe('23000.000')
        ->and(($this->credit)())->toBe('1000.000')
        ->and(ledgerIsBalanced())->toBeTrue();

    // Only what is still there can be settled from the old reservation.
    expect((string) app(SettleDeposit::class)->limit($reservation->fresh()))->toBe('1000.000');

    // More than the available credit is refused on a sale.
    expect(fn () => saveSale(purchaseVehicle(), ['party_id' => $this->customer->id, 'deposit_applied' => '1000.001']))
        ->toThrow(BusinessRuleException::class);
});

test('expiry keeps the deposit as credit or forfeits it, per settings', function (string $policy, string $credit, string $forfeited) {
    app(Settings::class)->set(['reservations.expiry_action' => $policy]);
    $reservation = ($this->reserve)('1200');
    $reservation->update(['expires_at' => today()->subDay()]);

    $this->artisan('reservations:expire')->assertSuccessful();

    expect($reservation->fresh()->status)->toBe(ReservationStatus::Expired)
        ->and(($this->credit)())->toBe($credit)
        ->and((string) baseBalance('42')->negated())->toBe($forfeited);
})->with([
    'keep as credit' => ['credit', '1200.000', '0.000'],
    'forfeit' => ['forfeit', '0.000', '1200.000'],
]);

test('cancel with settlement from the reservations screen', function () {
    $reservation = ($this->reserve)('2000');

    Livewire::test(ReservationsIndex::class)
        ->call('open', 'cancel', $reservation->id)
        ->assertSet('input.limit', '2000.000')
        ->set('input.reason', 'طلب العميل')
        ->set('input.refund', '1500')
        ->set('input.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('input.forfeit', '600')
        ->call('submit')
        ->assertHasErrors('input');                // 2,100 > 2,000

    expect($reservation->fresh()->status)->toBe(ReservationStatus::Active);

    Livewire::test(ReservationsIndex::class)
        ->call('open', 'cancel', $reservation->id)
        ->set('input.reason', 'طلب العميل')
        ->set('input.refund', '1500')
        ->set('input.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('input.forfeit', '500')
        ->call('submit')
        ->assertHasNoErrors();

    expect($reservation->fresh()->status)->toBe(ReservationStatus::Cancelled)
        ->and(($this->credit)())->toBe('0.000');

    // A salesperson can manage but not cancel or settle.
    $this->actingAs(userWithRole('sales'));
    $active = ($this->reserve)('500', purchaseVehicle());
    Livewire::test(ReservationsIndex::class)
        ->call('open', 'extend', $active->id)->assertOk()
        ->call('open', 'cancel', $active->id)->assertForbidden();
});
