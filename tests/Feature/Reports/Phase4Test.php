<?php

use App\Actions\Journals\PostManualJournal;
use App\Actions\Journals\SaveManualJournal;
use App\Enums\DocumentStatus;
use App\Exceptions\BusinessRuleException;
use App\Livewire\Dashboard;
use App\Livewire\Journals\Index as JournalsIndex;
use App\Livewire\Reports\Viewer;
use App\Models\JournalEntry;
use App\Models\ManualJournal;
use App\Reports\Accounting\AgingReport;
use App\Reports\Accounting\LedgerReport;
use App\Services\Currency\ExchangeRateService;
use Livewire\Livewire;

test('a manual journal posts through PostingService and cancels with a reversal', function () {
    $this->actingAs(userWithRole('accountant'));
    app(ExchangeRateService::class)->setRate(usd(), today(), '5.000000');

    $journal = app(SaveManualJournal::class)->handle(['date' => today()->toDateString(), 'description' => 'رأس مال', 'lines' => [
        ['account_id' => cashbox('خزينة دينار')->account_id, 'debit' => '10000'],
        ['account_id' => cashbox('خزينة دولار')->account_id, 'debit' => '1000', 'currency_id' => usd()->id],   // rate of the day: 5
        ['account_id' => account('31')->id, 'credit' => '15000'],
    ]]);
    expect($journal->number)->toBeNull()
        ->and(JournalEntry::query()->count())->toBe(0);

    app(PostManualJournal::class)->handle($journal);
    expect($journal->fresh()->number)->toStartWith('MJ-')
        ->and((string) baseBalance('31'))->toBe('-15000.000');

    $this->actingAs(userWithRole('admin'));
    app(PostManualJournal::class)->cancel($journal->fresh(), 'خطأ');
    expect($journal->fresh()->status)->toBe(DocumentStatus::Cancelled)
        ->and(baseBalance('31')->isZero())->toBeTrue();
});

test('a manual journal must balance, use posting accounts and name the party on control accounts', function () {
    $this->actingAs(userWithRole('accountant'));
    $save = fn (array $lines) => fn () => app(SaveManualJournal::class)->handle(['date' => today()->toDateString(), 'description' => 'x', 'lines' => $lines]);
    $cash = cashbox('خزينة دينار')->account_id;

    expect($save([['account_id' => $cash, 'debit' => '1'], ['account_id' => account('31')->id, 'credit' => '2']]))->toThrow(BusinessRuleException::class)
        ->and($save([['account_id' => account('1')->id, 'debit' => '1'], ['account_id' => account('31')->id, 'credit' => '1']]))->toThrow(BusinessRuleException::class)
        ->and($save([['account_id' => account('13')->id, 'debit' => '1'], ['account_id' => account('31')->id, 'credit' => '1']]))->toThrow(BusinessRuleException::class)
        ->and($save([['account_id' => $cash, 'debit' => '1', 'credit' => '1'], ['account_id' => account('31')->id, 'credit' => '1']]))->toThrow(BusinessRuleException::class)
        ->and($save([['account_id' => $cash, 'debit' => '1']]))->toThrow(BusinessRuleException::class);

    // With the party named on the receivables line it is accepted.
    expect($save([['account_id' => account('13')->id, 'party_id' => customer()->id, 'debit' => '1'], ['account_id' => account('31')->id, 'credit' => '1']])())
        ->toBeInstanceOf(ManualJournal::class);
});

test('manual journals from the screen; only the admin cancels', function () {
    $this->actingAs(userWithRole('accountant'));

    Livewire::test(JournalsIndex::class)
        ->call('create')
        ->set('description', 'مسحوبات شريك')
        ->set('lines.0.account_id', account('32')->id)->set('lines.0.debit', '2500')
        ->set('lines.1.account_id', cashbox('خزينة دينار')->account_id)->set('lines.1.credit', '2500')
        ->assertSee(__('reports.balanced'))
        ->call('save')
        ->assertHasNoErrors();

    $journal = ManualJournal::query()->latest('id')->firstOrFail();
    Livewire::test(JournalsIndex::class)->call('approve', $journal->id)->assertHasNoErrors();
    expect($journal->fresh()->status)->toBe(DocumentStatus::Posted);

    Livewire::test(JournalsIndex::class)->call('openCancel', $journal->id)->assertForbidden();
    $this->actingAs(userWithRole('cashier'))->get('/journals')->assertForbidden();
});

test('reports follow permissions and hide cost from roles without view_cost', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('41234.5');
    $vehicle->update(['asking_price' => '48000']);
    $seller = userWithRole('sales');
    sell($vehicle, ['price' => '48000', 'salesperson_id' => $seller->id]);

    // A salesperson sees their own sales report, without cost or profit.
    $this->actingAs($seller);
    Livewire::test(Viewer::class, ['key' => 'sales'])->assertSee('48,000.000')->assertDontSee('41,234.500');
    $this->get(route('reports.show', 'balance_sheet'))->assertForbidden();
    $this->get(route('reports.pdf', 'trial_balance'))->assertForbidden();
    $this->get(route('reports.show', 'vehicle_profit'))->assertForbidden();

    // The accountant sees cost and profit.
    $this->actingAs(userWithRole('accountant'));
    Livewire::test(Viewer::class, ['key' => 'sales'])->assertSee('41,234.500');

    // A treasurer only gets the cashbox movement of their own cashbox.
    $cashier = userWithRole('cashier');
    $cashier->cashboxes()->attach(cashbox('خزينة دينار'));
    $this->actingAs($cashier);
    $this->get(route('reports.show', ['key' => 'cashbox_movement', 'f' => ['cashbox_id' => cashbox('خزينة دينار')->id]]))->assertOk();
    $this->get(route('reports.show', ['key' => 'cashbox_movement', 'f' => ['cashbox_id' => cashbox('خزينة دولار')->id]]))->assertNotFound();
});

test('debt ageing applies payments to the oldest debts first', function () {
    $this->actingAs(userWithRole('admin'));
    $customer = customer();

    $this->travelTo(today()->subDays(100));
    sell(purchaseVehicle('10000'), ['party_id' => $customer->id, 'price' => '12000']);
    $this->travelBack();
    $this->travelTo(today()->subDays(45));
    sell(purchaseVehicle('10000'), ['party_id' => $customer->id, 'price' => '15000']);
    $this->travelBack();

    // 5,000 paid today settles part of the oldest (100-day) debt.
    postVoucher(['type' => 'receipt', 'party_id' => $customer->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('13')->id, 'amount' => '5000']);

    $row = collect(app(AgingReport::class)->rows(auth()->user(), ['as_of' => today()->toDateString(), 'side' => 'receivables']))
        ->firstWhere('party', $customer->name);

    expect((string) $row['over'])->toBe('7000.000')
        ->and((string) $row['d60'])->toBe('15000.000')
        ->and((string) $row['total'])->toBe('22000.000');
});

test('the ledger of a group account includes its sub-accounts with a running balance', function () {
    $this->actingAs(userWithRole('admin'));
    purchaseVehicle('20000');
    postExpense(null, '500', cashbox('خزينة دينار'));

    $rows = app(LedgerReport::class)->rows(auth()->user(), [
        'account_id' => account('11')->id, 'from' => today()->startOfYear()->toDateString(), 'to' => today()->toDateString(),
    ]);

    expect(end($rows)['balance']->isEqualTo('-500'))->toBeTrue();
});

test('the dashboard shows only what the role may see', function () {
    $this->actingAs(userWithRole('admin'));
    $old = purchaseVehicle('30000');
    $old->update(['received_at' => today()->subDays(75)]);
    sell(purchaseVehicle('20000'), ['price' => '26000', 'payment_type' => 'cash',
        'payments' => [['cashbox_id' => cashbox('خزينة دينار')->id, 'amount' => '26000']]]);

    Livewire::test(Dashboard::class)
        ->assertSee(__('dashboard.sales_today'))
        ->assertSee('26,000.000')
        ->assertSee(__('dashboard.cashboxes'))
        ->assertSee($old->fresh()->title());

    $this->actingAs(userWithRole('purchasing'));
    Livewire::test(Dashboard::class)
        ->assertDontSee(__('dashboard.sales_today'))
        ->assertDontSee(__('dashboard.cashboxes'))
        ->assertSee(__('dashboard.available_vehicles'));
});

test('the vehicle card prints, with cost only for those who may see it', function () {
    $this->actingAs(userWithRole('admin'));
    $vehicle = purchaseVehicle('30000');

    $this->get(route('print.vehicle', $vehicle))->assertOk()->assertHeader('Content-Type', 'application/pdf');
    $this->actingAs(userWithRole('sales'))->get(route('print.vehicle', $vehicle))->assertOk();
});
