<?php

use App\Livewire\Journals\Index;
use App\Livewire\Vouchers\Index as VouchersIndex;
use App\Models\User;
use App\Support\Navigation;
use Livewire\Livewire;

/** @return array<string, list<string>> sidebar item label => its dropdown labels */
function sidebar(User $user): array
{
    $items = [];
    foreach (Navigation::for($user) as $section) {
        foreach ($section['items'] as $item) {
            $items[$item['label']] = array_column($item['children'], 'label');
        }
    }

    return $items;
}

test('sidebar items open a view / new dropdown, keeping only what the role may do', function () {
    $accountant = sidebar(userWithRole('accountant'));
    expect($accountant[__('app.nav.vouchers')])->toBe([
        __('app.nav_actions.view_vouchers'), __('app.nav_actions.new_receipt'), __('app.nav_actions.new_payment'), __('app.nav_actions.new_transfer'),
    ])->and($accountant[__('app.nav.sales')])->toContain(__('app.nav_actions.new_sale'));

    // The cashier may view parties but not add them: a plain link, no dropdown.
    $cashier = sidebar(userWithRole('cashier'));
    expect($cashier[__('app.nav.parties')])->toBe([])
        ->and($cashier[__('app.nav.expenses')])->toContain(__('app.nav_actions.new_expense'));
});

test('the "new" links open the right form', function () {
    $this->actingAs(userWithRole('accountant'));

    Livewire::withQueryParams(['new' => 'payment'])->test(VouchersIndex::class)
        ->assertSet('showForm', true)->assertSet('form.type', 'payment');
    Livewire::withQueryParams(['new' => '1'])->test(Index::class)->assertSet('showForm', true);
    Livewire::withQueryParams(['new' => '1'])->test(App\Livewire\Parties\Index::class)->assertSet('showForm', true);
    Livewire::withQueryParams([]);
});
