<?php

use App\Enums\AccountNature;
use App\Enums\AccountRole;
use App\Enums\AccountType;
use App\Models\Account;
use App\Models\Cashbox;
use App\Models\FiscalPeriod;
use App\Models\User;
use App\Services\Accounting\AccountResolver;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('the default chart of accounts follows the spec', function () {
    $expected = [
        '1' => AccountType::Asset, '11' => AccountType::Asset, '12' => AccountType::Asset, '13' => AccountType::Asset,
        '14' => AccountType::Asset, '15' => AccountType::Asset,
        '2' => AccountType::Liability, '21' => AccountType::Liability, '22' => AccountType::Liability, '23' => AccountType::Liability,
        '3' => AccountType::Equity, '31' => AccountType::Equity, '32' => AccountType::Equity, '33' => AccountType::Equity,
        '4' => AccountType::Revenue, '41' => AccountType::Revenue, '42' => AccountType::Revenue,
        '5' => AccountType::Expense, '6' => AccountType::Expense, '7' => AccountType::Expense,
    ];

    foreach ($expected as $code => $type) {
        expect(account($code)->type)->toBe($type, "account {$code}");
    }

    expect(account('1')->is_group)->toBeTrue()
        ->and(account('11')->is_group)->toBeTrue()
        ->and(account('13')->is_group)->toBeFalse()
        ->and(account('13')->nature)->toBe(AccountNature::Debit)
        ->and(account('21')->nature)->toBe(AccountNature::Credit)
        ->and(account('1101')->name)->toBe('خزينة دينار')
        ->and(account('1102')->name)->toBe('خزينة دولار')
        ->and(account('1101')->parent_id)->toBe(account('11')->id);

    // Every child code starts with its parent's code.
    Account::query()->with('parent')->whereNotNull('parent_id')->get()->each(
        fn (Account $a) => expect(str_starts_with($a->code, $a->parent->code))->toBeTrue()
    );
});

test('every account role resolves to a postable account through settings', function () {
    $resolver = app(AccountResolver::class);

    foreach (AccountRole::cases() as $role) {
        expect($resolver->for($role)->isPostable())->toBeTrue($role->value);
    }

    expect($resolver->for(AccountRole::Receivables)->code)->toBe('13')
        ->and($resolver->for(AccountRole::Inventory)->code)->toBe('14');
});

test('three cashboxes exist with their own ledger accounts', function () {
    $cashboxes = Cashbox::query()->with(['account', 'currency'])->get()->keyBy('name');

    expect($cashboxes)->toHaveCount(3)
        ->and($cashboxes['خزينة دينار']->currency->code)->toBe('LYD')
        ->and($cashboxes['خزينة دولار']->currency->code)->toBe('USD')
        ->and($cashboxes['حساب مصرفي رئيسي']->account->code)->toBe('1201');
});

test('current-year monthly periods are open', function () {
    expect(FiscalPeriod::query()->whereYear('start_date', now()->year)->where('is_closed', false)->count())->toBe(12)
        ->and(FiscalPeriod::query()->containing(today())->exists())->toBeTrue();
});

test('the five roles carry the spec permission matrix', function () {
    $can = fn (string $role, string $permission) => Role::findByName($role)->hasPermissionTo($permission);

    expect(User::query()->where('username', 'admin')->first()->hasRole('admin'))->toBeTrue();

    // Admin: everything.
    expect(Role::findByName('admin')->permissions()->count())->toBe(Permission::query()->count());

    // Cost and profit: admin, accountant, purchasing only.
    expect($can('accountant', 'vehicles.view_cost'))->toBeTrue()
        ->and($can('purchasing', 'vehicles.view_cost'))->toBeTrue()
        ->and($can('cashier', 'vehicles.view_cost'))->toBeFalse()
        ->and($can('sales', 'vehicles.view_cost'))->toBeFalse();

    // Purchasing creates purchase invoices but cannot approve; sales creates sales invoices but cannot approve.
    expect($can('purchasing', 'purchases.create'))->toBeTrue()
        ->and($can('purchasing', 'purchases.approve'))->toBeFalse()
        ->and($can('sales', 'sales.create'))->toBeTrue()
        ->and($can('sales', 'sales.approve'))->toBeFalse()
        ->and($can('accountant', 'sales.approve'))->toBeTrue();

    // Overrides, cancelling posted documents and closing periods: admin only.
    foreach (['accountant', 'cashier', 'sales', 'purchasing'] as $role) {
        foreach (['sales.override_min_price', 'sales.override_discount', 'vouchers.cancel', 'sales.cancel', 'periods.close', 'users.manage', 'roles.manage', 'audit.view'] as $permission) {
            expect($can($role, $permission))->toBeFalse("{$role} must not have {$permission}");
        }
    }

    // Vouchers and expenses: admin, accountant, cashier.
    expect($can('cashier', 'vouchers.create'))->toBeTrue()
        ->and($can('cashier', 'expenses.create'))->toBeTrue()
        ->and($can('sales', 'vouchers.create'))->toBeFalse()
        ->and($can('cashier', 'cashboxes.view_all'))->toBeFalse();

    // Manual journal, chart of accounts and financial reports: admin, accountant.
    expect($can('accountant', 'journal.create'))->toBeTrue()
        ->and($can('accountant', 'accounts.manage'))->toBeTrue()
        ->and($can('accountant', 'reports.financial'))->toBeTrue()
        ->and($can('cashier', 'journal.create'))->toBeFalse()
        ->and($can('purchasing', 'reports.financial'))->toBeFalse();
});

test('seeding twice changes nothing', function () {
    $counts = fn () => [Account::count(), Cashbox::count(), FiscalPeriod::count(), Role::count(), User::count()];
    $before = $counts();

    $this->seed(DatabaseSeeder::class);

    expect($counts())->toBe($before);
});
