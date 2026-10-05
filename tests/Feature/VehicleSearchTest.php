<?php

use App\Livewire\Pickers\VehicleFinder;
use App\Livewire\Pickers\VehiclePicker;
use App\Models\CarModel;
use App\Models\Color;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));

    [$a, $b] = CarModel::query()->with('brand')->get()->unique('brand_id')->take(2)->values()->all();
    $this->modelA = $a;
    $this->modelB = $b;
    $white = Color::query()->firstOrFail();

    $this->cheap = purchaseVehicle('30000');
    $this->cheap->update(['brand_id' => $a->brand_id, 'model_id' => $a->id, 'year' => 2018, 'asking_price' => '35000', 'color_id' => $white->id]);
    $this->dear = purchaseVehicle('90000');
    $this->dear->update(['brand_id' => $a->brand_id, 'model_id' => $a->id, 'year' => 2022, 'asking_price' => '99000']);
    $this->other = purchaseVehicle('50000');
    $this->other->update(['brand_id' => $b->brand_id, 'model_id' => $b->id, 'year' => 2020, 'asking_price' => '56000']);
    $this->sold = purchaseVehicle('40000');
    $this->sold->update(['brand_id' => $a->brand_id, 'model_id' => $a->id]);
    sell($this->sold, ['price' => '45000']);
});

test('the search modal filters by brand, model, year, colour and price', function () {
    $picker = Livewire::test(VehiclePicker::class, ['statuses' => ['available']])
        ->call('openSearch')
        ->assertSet('showSearch', true)
        ->assertSee($this->cheap->vin)->assertSee($this->other->vin)
        ->assertDontSee($this->sold->vin);                         // not an allowed status

    $picker->set('filter.brand_id', (string) $this->modelA->brand_id)
        ->assertSee($this->cheap->vin)->assertSee($this->dear->vin)->assertDontSee($this->other->vin)
        ->set('filter.year_from', '2020')
        ->assertDontSee($this->cheap->vin)->assertSee($this->dear->vin)
        ->set('filter.year_from', '')->set('filter.price_max', '40000')
        ->assertSee($this->cheap->vin)->assertDontSee($this->dear->vin)
        ->call('resetFilters')
        ->set('filter.color_id', (string) $this->cheap->color_id)
        ->assertSee($this->cheap->vin)->assertDontSee($this->other->vin);

    // Changing the brand clears the model filter.
    $picker->set('filter.model_id', (string) $this->modelA->id)
        ->set('filter.brand_id', (string) $this->modelB->brand_id)
        ->assertSet('filter.model_id', '');
});

test('the typed text carries into the modal, and choosing selects only allowed vehicles', function () {
    Livewire::test(VehiclePicker::class, ['statuses' => ['available']])
        ->set('search', $this->other->vin)
        ->assertSee(__('vehicle_search.advanced'))
        ->call('openSearch')
        ->assertSet('filter.q', $this->other->vin)
        ->call('choose', $this->other->id)
        ->assertSet('value', $this->other->id)
        ->assertSet('showSearch', false)
        ->call('choose', $this->sold->id)
        ->assertSet('value', null);
});

test('vehicle details show cost only to those who may see it', function () {
    Livewire::test(VehiclePicker::class)->call('preview', $this->cheap->id)
        ->assertSee($this->cheap->vin)->assertSee('30,000.000')->assertSee(__('vehicle_search.choose'));

    $this->actingAs(userWithRole('sales'));
    Livewire::test(VehiclePicker::class, ['statuses' => ['available']])->call('preview', $this->cheap->id)
        ->assertSee('35,000.000')->assertDontSee('30,000.000');

    // A vehicle outside the picker's statuses has no details here.
    Livewire::test(VehiclePicker::class, ['statuses' => ['available']])->call('preview', $this->sold->id)
        ->assertSet('previewId', null);
});

test('the topbar finder searches every vehicle and opens its card', function () {
    $this->get(route('dashboard'))->assertSee(__('vehicle_search.find'));

    Livewire::test(VehicleFinder::class)->call('openSearch')
        ->assertSee($this->sold->vin)
        ->assertSee(route('vehicles.show', $this->sold))
        ->assertDontSee(__('vehicle_search.choose'));
});
