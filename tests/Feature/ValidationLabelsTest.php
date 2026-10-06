<?php

use App\Livewire\Expenses\Categories;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;

test('messages name nested fields in Arabic, not by their technical key', function () {
    app()->setLocale('ar');

    $errors = Validator::make(
        ['form' => [], 'items' => [['price' => '']], 'lines' => [['debit' => 'x']], 'roles' => [5]],
        ['form.account_id' => 'required', 'items.*.price' => 'required', 'lines.*.debit' => 'numeric', 'roles.*' => 'string'],
    )->errors();

    expect($errors->first('form.account_id'))->toContain('الحساب')->not->toContain('form')
        ->and($errors->first('items.0.price'))->toContain('السعر')->not->toContain('items')
        ->and($errors->first('lines.0.debit'))->toContain('مدين')
        ->and($errors->first('roles.0'))->toContain('الأدوار');
});

test('the expense category form says which account is missing', function () {
    $this->actingAs(userWithRole('admin'));

    Livewire::test(Categories::class)
        ->call('create')
        ->set('form.name', 'بند جديد')
        ->call('save')
        ->assertHasErrors(['form.account_id'])
        ->assertSee('الحساب')
        ->assertDontSee('form.account id');
});
