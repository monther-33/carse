<?php

use App\Enums\PartyType;
use App\Livewire\Expenses\Index as ExpensesIndex;
use App\Livewire\Pickers\PartyPicker;
use App\Livewire\Purchases\Form as PurchaseForm;
use App\Livewire\QuickCreate;
use App\Livewire\Vouchers\Index as VouchersIndex;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\ExpenseCategory;
use App\Models\Location;
use App\Models\Party;
use Livewire\Livewire;

test('a customer added from the picker modal is created with its details and selected in the picker', function () {
    $this->actingAs(userWithRole('sales'));
    $picker = Livewire::test(PartyPicker::class, ['kind' => 'customer', 'allowCreate' => true])
        ->set('search', 'جمال')
        ->assertSee(__('quick.add_named', ['name' => 'جمال']));

    Livewire::test(QuickCreate::class)
        ->call('start', 'party', $picker->id(), 'value', ['name' => 'جمال البوسيفي', 'kind' => 'customer'])
        ->assertSet('form.name', 'جمال البوسيفي')
        ->set('form.phone', '0915551122')
        ->set('form.national_id', '119880011223')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('quick-created', type: 'party', owner: $picker->id(), target: 'value');

    $party = Party::query()->where('name', 'جمال البوسيفي')->sole();
    expect($party->type)->toBe(PartyType::Customer)
        ->and($party->national_id)->toBe('119880011223');

    $picker->call('acceptQuickCreated', 'party', $party->id, $picker->id(), 'value')
        ->assertSet('value', $party->id);
});

test('a new brand is selected on the purchase line and clears the old model; other components ignore it', function () {
    $this->actingAs(userWithRole('admin'));
    $old = CarModel::query()->firstOrFail();

    $form = Livewire::test(PurchaseForm::class)
        ->set('items.0.brand_id', $old->brand_id)
        ->set('items.0.model_id', $old->id);

    Livewire::test(QuickCreate::class)->call('start', 'brand', $form->id(), 'items.0.brand_id')
        ->set('form.name', 'جيلي')->call('save')->assertHasNoErrors();
    $brand = Brand::query()->where('name', 'جيلي')->sole();

    $form->call('acceptQuickCreated', 'brand', $brand->id, 'another-component', 'items.0.brand_id')
        ->assertSet('items.0.brand_id', $old->brand_id)
        ->call('acceptQuickCreated', 'brand', $brand->id, $form->id(), 'items.0.brand_id')
        ->assertSet('items.0.brand_id', $brand->id)
        ->assertSet('items.0.model_id', null);

    // The model button passes the chosen brand.
    Livewire::test(QuickCreate::class)->call('start', 'model', $form->id(), 'items.0.model_id', ['brand_id' => $brand->id])
        ->assertSet('form.brand_id', $brand->id)
        ->set('form.name', 'كولراي')->call('save')->assertHasNoErrors();
    expect(CarModel::query()->where('brand_id', $brand->id)->where('name', 'كولراي')->exists())->toBeTrue();
});

test('quick-created references follow the same rules as their screens', function () {
    $this->actingAs(userWithRole('admin'));
    $model = CarModel::query()->firstOrFail();

    Livewire::test(QuickCreate::class)->call('start', 'model', null, null, ['brand_id' => $model->brand_id])
        ->set('form.name', $model->name)->call('save')->assertHasErrors('form.name');
    Livewire::test(QuickCreate::class)->call('start', 'model')
        ->set('form.name', 'X')->call('save')->assertHasErrors('form.brand_id');
    Livewire::test(QuickCreate::class)->call('start', 'color')
        ->set('form.name', 'ذهبي')->set('form.hex', 'gold')->call('save')->assertHasErrors('form.hex')
        ->set('form.hex', '#D4AF37')->call('save')->assertHasNoErrors();
    Livewire::test(QuickCreate::class)->call('start', 'location')
        ->set('form.name', 'المخزن الخلفي')->call('save')->assertHasNoErrors();
    Livewire::test(QuickCreate::class)->call('start', 'expense_category')
        ->set('form.name', 'وقود')->set('form.account_id', account('41')->id)->call('save')->assertHasErrors('form.account_id')
        ->set('form.account_id', account('66')->id)->call('save')->assertHasNoErrors();

    expect(Color::query()->where('name', 'ذهبي')->value('hex'))->toBe('#D4AF37')
        ->and(Location::query()->where('name', 'المخزن الخلفي')->value('branch_id'))->toBe(auth()->user()->branch_id)
        ->and(ExpenseCategory::query()->where('name', 'وقود')->value('is_active'))->toBeTrue();
});

test('quick add follows permissions, in the modal and for the buttons', function () {
    $this->actingAs(userWithRole('cashier'));
    Livewire::test(QuickCreate::class)->call('start', 'party')->assertForbidden();
    Livewire::test(QuickCreate::class)->call('start', 'expense_category')->assertForbidden();
    Livewire::test(ExpensesIndex::class)->call('create')->assertDontSee(__('quick.titles.expense_category'));

    $this->actingAs(userWithRole('sales'));
    Livewire::test(QuickCreate::class)->call('start', 'brand')->assertForbidden();
    Livewire::test(QuickCreate::class)->call('start', 'party')->assertOk();

    $this->actingAs(userWithRole('accountant'));
    Livewire::test(ExpensesIndex::class)->call('create')->assertSee(__('quick.titles.expense_category'));
});

test('the topbar quick-add menu offers what the user may create, and ?new=1 opens the form', function () {
    $this->actingAs(userWithRole('accountant'));
    $this->get(route('dashboard'))->assertOk()
        ->assertSee(__('quick.menu'))
        ->assertSee(__('quick.menu_items.party_customer'))
        ->assertSee(__('quick.links.voucher'))
        ->assertDontSee(__('quick.menu_items.brand'));

    Livewire::withQueryParams(['new' => 1])->test(VouchersIndex::class)->assertSet('showForm', true);
    Livewire::withQueryParams([])->test(VouchersIndex::class)->assertSet('showForm', false);
});
