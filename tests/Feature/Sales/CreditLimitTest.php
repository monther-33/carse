<?php

use App\Enums\DocumentStatus;
use App\Livewire\Sales\Show;
use App\Services\Sales\CreditLimitCheck;
use App\Support\Money;
use Livewire\Livewire;

/*
| Credit limit is a warning only (owner's decision): the sale is never blocked.
*/
test('going over the credit limit warns but the sale is still saved and approved', function () {
    $this->actingAs(userWithRole('admin'));
    $customer = customer();
    $customer->update(['credit_limit' => '20000']);

    sell(purchaseVehicle('10000'), ['party_id' => $customer->id, 'price' => '15000', 'payment_type' => 'credit']);
    $draft = saveSale(purchaseVehicle('8000'), ['party_id' => $customer->id, 'price' => '10000', 'payment_type' => 'credit']);

    $warning = app(CreditLimitCheck::class)->warning($customer->fresh(), Money::of('10000'));
    expect((string) $warning['balance'])->toBe('15000.000')
        ->and((string) $warning['over'])->toBe('5000.000');

    Livewire::test(Show::class, ['invoice' => $draft])
        ->assertSee(__('sales.credit_limit_warning.title'))
        ->call('approve')
        ->assertHasNoErrors();

    expect($draft->fresh()->status)->toBe(DocumentStatus::Posted);
});

test('no warning without a limit, within the limit, or when nothing stays owed', function () {
    $this->actingAs(userWithRole('admin'));
    $customer = customer();
    $check = app(CreditLimitCheck::class);

    expect($check->warning($customer, Money::of('50000')))->toBeNull();       // limit 0 = not set

    $customer->update(['credit_limit' => '20000']);
    expect($check->warning($customer->fresh(), Money::of('20000')))->toBeNull()   // exactly at the limit
        ->and($check->warning($customer->fresh(), Money::zero()))->toBeNull();    // cash sale
});
