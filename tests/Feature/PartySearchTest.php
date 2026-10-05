<?php

use App\Enums\PartyType;
use App\Livewire\Pickers\PartyPicker;
use App\Models\Party;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(userWithRole('accountant'));
    $this->ali = Party::factory()->create(['name' => 'علي الككلي', 'type' => PartyType::Customer, 'phone' => '0911000001', 'national_id' => '119800000001', 'address' => 'جنزور']);
    $this->firm = Party::factory()->create(['name' => 'شركة الأمانة', 'type' => PartyType::Supplier, 'phone' => '0211000002']);
    $this->both = Party::factory()->create(['name' => 'علي للتجارة', 'type' => PartyType::Both]);
    $this->inactive = Party::factory()->create(['name' => 'علي القديم', 'type' => PartyType::Customer, 'is_active' => false]);
});

test('the party search modal filters by text and type and keeps to the picker kind', function () {
    Livewire::test(PartyPicker::class, ['kind' => 'customer'])
        ->set('search', 'علي')
        ->call('openSearch')
        ->assertSet('filter.q', 'علي')
        ->assertSee('علي الككلي')->assertSee('علي للتجارة')
        ->assertDontSee('علي القديم')          // inactive
        ->assertDontSee('شركة الأمانة')        // supplier only
        ->set('filter.type', PartyType::Both->value)
        ->assertDontSee('علي الككلي')->assertSee('علي للتجارة')
        ->set('filter.type', '')->set('filter.q', '119800000001')
        ->assertSee('علي الككلي')->assertDontSee('علي للتجارة');
});

test('details show the party with its statement link, and only offered parties can be chosen', function () {
    Livewire::test(PartyPicker::class, ['kind' => 'customer'])
        ->call('preview', $this->ali->id)
        ->assertSee('جنزور')->assertSee(route('parties.statement', $this->ali))
        ->call('choose', $this->ali->id)->assertSet('value', $this->ali->id)->assertSet('showSearch', false)
        ->call('choose', $this->firm->id)->assertSet('value', null)
        ->call('choose', $this->inactive->id)->assertSet('value', null);
});
