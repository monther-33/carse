<?php

use App\Actions\Accounting\DeleteAccount;
use App\Enums\AccountRole;
use App\Enums\AccountType;
use App\Exceptions\Accounting\ClosedPeriodException;
use App\Livewire\Accounts\Index as AccountsIndex;
use App\Livewire\Cashboxes\Index as CashboxesIndex;
use App\Livewire\Currencies\Index as CurrenciesIndex;
use App\Livewire\FiscalPeriods\Index as PeriodsIndex;
use App\Livewire\References\Brands;
use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Models\Account;
use App\Models\Brand;
use App\Models\Cashbox;
use App\Models\FiscalPeriod;
use App\Models\User;
use App\Services\Accounting\AccountResolver;
use App\Services\Accounting\JournalBuilder;
use App\Services\Currency\ExchangeRateService;
use App\Support\Settings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->admin = userWithRole('admin');
    $this->actingAs($this->admin);
});

test('admin creates a user with roles and cashboxes, and the change is audited', function () {
    Livewire::test(UsersIndex::class)
        ->call('create')
        ->set('name', 'سالم')
        ->set('email', 'salem@cars.local')
        ->set('password', 'secret-pass-1')
        ->set('max_discount', '500.5')
        ->set('roles', ['cashier'])
        ->set('cashbox_ids', [cashbox('خزينة دينار')->id])
        ->call('save')
        ->assertHasNoErrors();

    $user = User::query()->where('email', 'salem@cars.local')->firstOrFail();
    expect($user->hasRole('cashier'))->toBeTrue()
        ->and((string) $user->max_discount)->toBe('500.500')
        ->and($user->cashboxes->pluck('name')->all())->toBe(['خزينة دينار'])
        ->and(Hash::check('secret-pass-1', $user->password))->toBeTrue()
        ->and(Activity::query()->where('subject_type', User::class)->where('subject_id', $user->id)->where('event', 'created')->exists())->toBeTrue();
});

test('users are deactivated, never deleted, and the admin cannot deactivate themself', function () {
    $user = userWithRole('sales');

    Livewire::test(UsersIndex::class)->call('toggleActive', $user->id);
    expect($user->fresh()->is_active)->toBeFalse();

    Livewire::test(UsersIndex::class)->call('toggleActive', $this->admin->id)->assertForbidden();
    expect($this->admin->fresh()->is_active)->toBeTrue()
        ->and($this->admin->can('delete', $user))->toBeFalse();
});

test('a child account inherits type and must use the parent code as prefix', function () {
    Livewire::test(AccountsIndex::class)
        ->call('create', account('6')->id)
        ->assertSet('code', '67')
        ->set('name', 'صيانة المعرض')
        ->call('save')
        ->assertHasNoErrors();

    $account = account('67');
    expect($account->type)->toBe(AccountType::Expense)
        ->and($account->parent_id)->toBe(account('6')->id);

    Livewire::test(AccountsIndex::class)
        ->call('create', account('6')->id)
        ->set('code', '9901')
        ->set('name', 'خطأ')
        ->call('save')
        ->assertHasErrors('code');
});

test('accounts in use cannot be deleted or turned into groups', function () {
    posting()->post(JournalBuilder::make(today(), 'حركة')->debit(account('66'), '10')->credit(account('31'), '10'));

    Livewire::test(AccountsIndex::class)
        ->call('edit', account('66')->id)
        ->set('is_group', true)
        ->call('save')
        ->assertHasErrors('is_group');

    $unused = Account::query()->create([
        'code' => '68', 'name' => 'مؤقت', 'parent_id' => account('6')->id,
        'type' => AccountType::Expense, 'nature' => AccountType::Expense->nature(),
    ]);
    Livewire::test(AccountsIndex::class)->call('delete', $unused->id)->assertHasNoErrors();
    expect(Account::query()->whereKey($unused->id)->exists())->toBeFalse();

    // A mapped settings account is protected even without postings.
    $receivables = app(AccountResolver::class)->for(AccountRole::Receivables);
    expect(fn () => app(DeleteAccount::class)->handle($receivables))
        ->toThrow(ValidationException::class);
});

test('a new cashbox gets its own ledger account under the configured parent', function () {
    Livewire::test(CashboxesIndex::class)
        ->call('create')
        ->set('name', 'مصرف التجارة')
        ->set('type', 'bank')
        ->set('currency_id', usd()->id)
        ->set('bank_name', 'مصرف التجارة والتنمية')
        ->set('account_number', '0012345')
        ->call('save')
        ->assertHasNoErrors();

    $cashbox = Cashbox::query()->where('name', 'مصرف التجارة')->with('account')->firstOrFail();
    expect($cashbox->account->code)->toBe('1202')
        ->and($cashbox->account->parent_id)->toBe(account('12')->id)
        ->and($cashbox->account->is_group)->toBeFalse();
});

test('closing a period blocks posting into it', function () {
    $period = FiscalPeriod::query()->containing(today())->firstOrFail();

    Livewire::test(PeriodsIndex::class)->call('close', $period->id)->assertHasNoErrors();

    $period->refresh();
    expect($period->is_closed)->toBeTrue()
        ->and($period->closed_by)->toBe($this->admin->id);

    expect(fn () => posting()->post(JournalBuilder::make(today(), 'x')->debit(account('14'), '1')->credit(account('21'), '1')))
        ->toThrow(ClosedPeriodException::class);
});

test('generating a fiscal year creates twelve months once', function () {
    Livewire::test(PeriodsIndex::class)->set('year', 2035)->call('generate');
    Livewire::test(PeriodsIndex::class)->set('year', 2035)->call('generate');

    expect(FiscalPeriod::query()->whereYear('start_date', 2035)->count())->toBe(12);
});

test('exchange rates are entered per day for foreign currencies only', function () {
    Livewire::test(CurrenciesIndex::class)
        ->set('selectedId', usd()->id)
        ->set('rate_date', today()->toDateString())
        ->set('rate', '4.875')
        ->call('saveRate')
        ->assertHasNoErrors();

    expect((string) app(ExchangeRateService::class)->rateFor(usd(), today()))->toBe('4.875000');

    Livewire::test(CurrenciesIndex::class)
        ->set('selectedId', lyd()->id)
        ->set('rate', '2')
        ->call('saveRate')
        ->assertHasErrors('rate');
});

test('role permissions are edited from the UI, except the admin role', function () {
    $sales = Role::findByName('sales');

    Livewire::test(RolesIndex::class)
        ->call('select', $sales->id)
        ->set('permissions', ['vehicles.view', 'reservations.create'])
        ->call('save')
        ->assertHasNoErrors();

    expect($sales->fresh()->permissions->pluck('name')->sort()->values()->all())->toBe(['reservations.create', 'vehicles.view']);

    Livewire::test(RolesIndex::class)
        ->call('select', Role::findByName('admin')->id)
        ->set('permissions', [])
        ->call('save')
        ->assertHasErrors('permissions');

    expect($this->admin->fresh()->can('users.manage'))->toBeTrue();
});

test('settings save the account mapping and only accept postable accounts', function () {
    Livewire::test(SettingsIndex::class)
        ->set('company_name', 'معرض النخبة')
        ->set('accounts.'.AccountRole::OtherRevenue->value, account('4')->id)
        ->call('save')
        ->assertHasErrors('accounts.'.AccountRole::OtherRevenue->value);

    Livewire::test(SettingsIndex::class)
        ->set('company_name', 'معرض النخبة')
        ->set('require_approval', false)
        ->call('save')
        ->assertHasNoErrors();

    $settings = app(Settings::class);
    $settings->flush();
    expect($settings->get('company.name'))->toBe('معرض النخبة')
        ->and($settings->bool('documents.require_approval'))->toBeFalse();
});

test('brands and their models are managed together', function () {
    Livewire::test(Brands::class)
        ->call('create')->set('form.name', 'بي واي دي')->call('save')->assertHasNoErrors();

    $brand = Brand::query()->where('name', 'بي واي دي')->firstOrFail();

    Livewire::test(Brands::class)
        ->call('selectBrand', $brand->id)
        ->set('modelName', 'سونج')->call('saveModel')->assertHasNoErrors()
        ->set('modelName', 'سونج')->call('saveModel')->assertHasErrors('modelName');

    expect($brand->models()->pluck('name')->all())->toBe(['سونج']);
});

test('all setup screens render for the admin', function (string $url) {
    $this->get($url)->assertOk();
})->with(['/dashboard', '/accounts', '/cashboxes', '/periods', '/currencies', '/references/brands', '/references/colors', '/references/locations', '/branches', '/settings', '/users', '/roles', '/profile']);
