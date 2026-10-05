<?php

use App\Models\Commission;
use App\Models\InstallmentPayment;
use App\Models\ManualJournal;
use App\Models\PurchaseInvoice;
use App\Models\Reservation;
use App\Models\ReturnDocument;
use App\Models\SalesInvoice;
use App\Reports\Accounting\BalanceSheetReport;
use App\Reports\Accounting\IncomeStatementReport;
use App\Reports\Accounting\TrialBalanceReport;
use App\Reports\ReportRegistry;
use App\Reports\TrialBalance;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Database\Seeders\DemoMonthSeeder;
use Illuminate\Support\Facades\DB;

/*
| Phase 4 acceptance gate (spec section 9): on a full month of seeded activity the balance
| sheet balances (assets = liabilities + equity), and so does the trial balance.
*/
beforeEach(function () {
    $this->seed(DemoMonthSeeder::class);
    $this->monthEnd = CarbonImmutable::now()->subMonthNoOverflow()->endOfMonth();
    $this->monthStart = $this->monthEnd->startOfMonth();
    $this->actingAs(userWithRole('admin'));
});

test('the balance sheet balances on a full month of activity', function () {
    $sheet = app(BalanceSheetReport::class);

    foreach ([$this->monthStart->addDays(13), $this->monthEnd, CarbonImmutable::today()] as $date) {
        $b = $sheet->build(['as_of' => $date->toDateString()]);

        expect($b['assets']->isPositive())->toBeTrue()
            ->and((string) $b['assets'])->toBe((string) $b['liabilities']->plus($b['equity']), "balance sheet as of {$date->toDateString()}");
    }

    expect((new TrialBalance)->isBalanced())->toBeTrue()
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('the month really happened: every kind of document was posted', function () {
    expect(SalesInvoice::query()->posted()->count())->toBe(5)
        ->and(PurchaseInvoice::query()->posted()->count())->toBe(3)
        ->and(ReturnDocument::query()->count())->toBe(2)
        ->and(ManualJournal::query()->posted()->count())->toBe(2)
        ->and(Reservation::query()->count())->toBe(2)
        ->and(Commission::query()->where('status', 'paid')->count())->toBeGreaterThan(0)
        ->and(InstallmentPayment::query()->count())->toBe(2)
        ->and(baseBalance('71')->isZero())->toBeFalse()                 // currency differences happened
        ->and((string) baseBalance('42'))->toBe('-1000.000');          // forfeited deposit
});

test('net profit in the income statement equals current earnings in the balance sheet', function () {
    $f = ['from' => '2000-01-01', 'to' => CarbonImmutable::today()->toDateString(), 'as_of' => CarbonImmutable::today()->toDateString()];

    $net = app(IncomeStatementReport::class)->build($f)['net'];
    $earnings = collect(app(BalanceSheetReport::class)->build($f)['rows'])->firstWhere('name', __('reports.current_earnings'))['amount'];

    // Independent check straight from the ledger: credit − debit on every revenue/expense line.
    $raw = DB::table('journal_lines as l')->join('accounts as a', 'a.id', '=', 'l.account_id')
        ->whereIn('a.type', ['revenue', 'expense'])->selectRaw('SUM(l.credit_base - l.debit_base) AS n')->value('n');

    expect((string) $net)->toBe((string) $earnings)
        ->and((string) $net)->toBe((string) Money::of((string) $raw))
        ->and(app(TrialBalanceReport::class)->notes(auth()->user(), $f + ['branch_id' => null]))->toBe([__('reports.balanced')]);
});

test('every report runs, renders and exports for the admin', function () {
    $f = [
        'from' => $this->monthStart->toDateString(), 'to' => $this->monthEnd->toDateString(), 'as_of' => $this->monthEnd->toDateString(),
        'account_id' => account('13')->id, 'cashbox_id' => cashbox('خزينة دينار')->id,
    ];

    foreach (ReportRegistry::forUser(auth()->user()) as $reports) {
        foreach ($reports as $report) {
            $key = $report::key();
            $this->get(route('reports.show', ['key' => $key, 'f' => $f]))->assertOk();

            $pdf = $this->get(route('reports.pdf', ['key' => $key, 'f' => $f]));
            $pdf->assertOk()->assertHeader('Content-Type', 'application/pdf');

            $this->get(route('reports.excel', ['key' => $key, 'f' => $f]))->assertOk()->assertDownload();
        }
    }
});
