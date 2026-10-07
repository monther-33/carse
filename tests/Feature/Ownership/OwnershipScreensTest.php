<?php

use App\Enums\CostBearer;
use App\Enums\OwnershipStatus;
use App\Livewire\Consignments\Form;
use App\Livewire\Consignments\Index;
use App\Livewire\Expenses\Index as Expenses;
use App\Livewire\Purchases\Form as PurchaseForm;
use App\Livewire\Sales\Form as SalesForm;
use App\Livewire\Vehicles\Show;
use App\Models\CarModel;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\SalesInvoiceItem;
use App\Models\VehicleOwnership;
use App\Support\Features;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
});

it('receives a consignment car from the screen', function () {
    $model = CarModel::query()->firstOrFail();
    $owner = customer();

    Livewire::test(Form::class)
        ->set('vehicle.vin', 'CONS123456789')
        ->set('vehicle.brand_id', $model->brand_id)
        ->set('vehicle.model_id', $model->id)
        ->set('owners.0.party_id', $owner->id)
        ->set('earning_mode', 'net_price')
        ->set('earning_amount', '45000')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('consignments.index'));

    $ownership = VehicleOwnership::query()->with('owners')->sole();
    expect($ownership->earning_amount)->toBe('45000.000')
        ->and($ownership->owners->sole()->party_id)->toBe($owner->id);
});

it('lists consignment cars, prints the receipt and hands a car back', function () {
    $ownership = receiveConsignment();

    Livewire::test(Index::class)
        ->assertSee($ownership->number)
        ->call('openReturn', $ownership->id)
        ->set('reason', 'طلب المالك')
        ->call('returnToOwner')
        ->assertHasNoErrors();

    expect($ownership->refresh()->status)->toBe(OwnershipStatus::Returned);

    $this->get(route('print.consignment', $ownership))->assertOk()->assertHeader('content-type', 'application/pdf');
});

it('keeps the screens away from users without the permission', function () {
    $this->actingAs(userWithRole('sales'));

    $this->get(route('consignments.index'))->assertForbidden();
    $this->get(route('consignments.create'))->assertForbidden();
});

it('shows sales staff only that the car is on consignment', function () {
    $ownership = receiveConsignment(['earning_mode' => 'net_price', 'earning_amount' => '47123']);
    $this->actingAs(userWithRole('sales'));

    Livewire::test(Show::class, ['vehicle' => $ownership->vehicle])
        ->assertSee(__('enums.ownership_kind.consignment'))
        ->assertDontSee($ownership->owners()->first()->party->name)
        ->assertDontSee('47,123');
});

it('shows the owners and the agreement to cost viewers', function () {
    $ownership = receiveConsignment(['earning_mode' => 'net_price', 'earning_amount' => '47123']);

    Livewire::test(Show::class, ['vehicle' => $ownership->vehicle])
        ->assertSee($ownership->owners()->first()->party->name)
        ->assertSee('47,123');
});

it('adds partners to a purchase line', function () {
    $partner = customer();

    $component = Livewire::test(PurchaseForm::class)
        ->call('addPartner', 0)
        ->set('items.0.partners.0.party_id', $partner->id)
        ->set('items.0.partners.0.share', '40')
        ->assertSee(__('ownership.showroom_share_is', ['share' => '60']));

    expect($component->get('items.0.partners'))->toHaveCount(1);
});

it('asks who bears an expense on a car with owners', function () {
    $ownership = receiveConsignment();

    Livewire::test(Expenses::class)
        ->call('create')
        ->set('form.vehicle_id', $ownership->vehicle_id)
        ->assertSee(__('expenses.borne_by'))
        ->set('form.category_id', ExpenseCategory::query()->firstOrFail()->id)
        ->set('form.cashbox_id', cashbox('خزينة دينار')->id)
        ->set('form.amount', '150')
        ->set('form.description', 'غسيل')
        ->set('form.borne_by', 'owners')
        ->call('save')
        ->assertHasNoErrors();

    expect(Expense::query()->latest('id')->first()->borne_by)->toBe(CostBearer::Owners);
});

it('hides everything when the feature is off', function () {
    app(Features::class)->set(Features::CONSIGNMENT, false);

    $this->get(route('consignments.index'))->assertNotFound();
    Livewire::test(PurchaseForm::class)->assertDontSee(__('ownership.partners'));
});

it('reports what each owner is due', function () {
    $ownership = receiveConsignment(['earning_mode' => 'fixed', 'earning_amount' => '1000']);
    sell($ownership->vehicle, ['price' => '20000']);
    $owner = $ownership->owners()->first()->party;

    $this->get(route('reports.show', ['key' => 'owner_dues']))
        ->assertOk()
        ->assertSee($owner->name)
        ->assertSee('19,000.000');
});

it('receives a car with no commission from the screen', function () {
    $model = CarModel::query()->firstOrFail();

    Livewire::test(Form::class)
        ->set('vehicle.vin', 'NOCOMM12345678')
        ->set('vehicle.brand_id', $model->brand_id)
        ->set('vehicle.model_id', $model->id)
        ->set('owners.0.party_id', customer()->id)
        ->set('earning_mode', 'none')
        ->assertDontSee(__('ownership.fixed_commission'))
        ->call('save')
        ->assertHasNoErrors();

    $ownership = VehicleOwnership::query()->sole();
    expect($ownership->earning_amount)->toBeNull()
        ->and($ownership->earning_percent)->toBeNull();
});

it('chooses no commission on the sale screen', function () {
    $ownership = receiveConsignment();

    Livewire::test(SalesForm::class)
        ->set('party_id', customer()->id)
        ->set('pickVehicleId', $ownership->vehicle_id)
        ->assertSee(__('ownership.sale_commission.agreement'))
        ->set('items.0.price', '30000')
        ->set('payment_type', 'credit')
        ->set('items.0.commission_mode', 'none')
        ->call('save')
        ->assertHasNoErrors();

    expect(SalesInvoiceItem::query()->sole()->showroom_commission)->toBe('0.000');
});
