<?php

use App\Actions\Expenses\CancelExpense;
use App\Actions\Expenses\PostExpense;
use App\Actions\Expenses\SaveExpense;
use App\Actions\Ownership\ReturnToOwner;
use App\Enums\CostBearer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vehicle;
use App\Services\Ownership\OwnerPayouts;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
    $this->a = customer();
    $this->b = customer();
    $this->ownership = receiveConsignment(['owners' => [
        ['party_id' => $this->a->id, 'share' => '75'],
        ['party_id' => $this->b->id, 'share' => '25'],
    ]]);
});

function vehicleExpense(Vehicle $vehicle, string $amount, ?string $borneBy): Expense
{
    $expense = app(SaveExpense::class)->handle([
        'date' => today()->toDateString(),
        'category_id' => ExpenseCategory::query()->firstOrFail()->id,
        'cashbox_id' => cashbox('خزينة دينار')->id,
        'amount' => $amount,
        'vehicle_id' => $vehicle->id,
        'borne_by' => $borneBy,
        'description' => 'تنظيف',
        'recurs_every_months' => null,
    ]);

    return app(PostExpense::class)->handle($expense);
}

it('charges an expense borne by the owners to their accounts by share', function () {
    $vehicle = $this->ownership->vehicle;
    $expense = vehicleExpense($vehicle, '400', 'owners');
    $payouts = app(OwnerPayouts::class);

    expect($expense->borne_by)->toBe(CostBearer::Owners)
        ->and((string) $payouts->balance($this->a->id))->toBe('-300.000')
        ->and((string) $payouts->balance($this->b->id))->toBe('-100.000')
        ->and($vehicle->refresh()->total_cost)->toBe('0.000')
        ->and((string) baseBalance('14'))->toBe('0.000')
        ->and(ledgerIsBalanced())->toBeTrue();

    app(CancelExpense::class)->handle($expense, 'خطأ');

    expect((string) baseBalance('24'))->toBe('0.000')
        ->and($vehicle->refresh()->total_cost)->toBe('0.000');
});

it('capitalises an expense borne by the showroom and keeps it in the car profit', function () {
    $vehicle = $this->ownership->vehicle;
    vehicleExpense($vehicle, '500', 'showroom');

    expect($vehicle->refresh()->total_cost)->toBe('500.000');

    $invoice = sell($vehicle->refresh(), ['price' => '40000']);   // 5% → 2,000 to the showroom

    expect((string) $invoice->items->first()->profit())->toBe('1500.000')
        ->and((string) baseBalance('51'))->toBe('500.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('expenses the showroom costs when the car goes back to its owner', function () {
    $vehicle = $this->ownership->vehicle;
    vehicleExpense($vehicle, '500', 'showroom');

    app(ReturnToOwner::class)->handle($this->ownership, 'سحبها');

    expect((string) baseBalance('14'))->toBe('0.000')
        ->and((string) baseBalance('51'))->toBe('500.000')
        ->and($vehicle->refresh()->total_cost)->toBe('0.000')
        ->and(ledgerIsBalanced())->toBeTrue();
});

it('ignores who bears it on the showroom own cars', function () {
    $expense = vehicleExpense(purchaseVehicle(), '300', 'owners');

    expect($expense->borne_by)->toBeNull()
        ->and((string) baseBalance('24'))->toBe('0.000');
});
