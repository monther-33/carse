<?php

namespace Database\Seeders;

use App\Actions\Accounting\GenerateFiscalYear;
use App\Actions\Alerts\SendDailyAlerts;
use App\Actions\Imports\ImportOpeningBalances;
use App\Actions\Imports\ImportOpeningStock;
use App\Actions\Imports\ImportParties;
use App\Actions\Journals\PostManualJournal;
use App\Actions\OpeningStock\PostOpeningStock;
use App\Actions\Reservations\CreateReservation;
use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Models\Cashbox;
use App\Models\Currency;
use App\Models\ManualJournal;
use App\Models\OpeningStock;
use App\Models\Party;
use App\Models\SalesInvoice;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\Accounting\AccountResolver;
use App\Services\Currency\ExchangeRateService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Money;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * A realistic showroom to try the system with (spec phase 5), built only through the real
 * Actions and importers, on top of the base seeders:
 *
 *  - a user per role (password = ADMIN_PASSWORD, "password" by default);
 *  - go-live two months ago: parties, 14 cars in stock and opening balances imported from
 *    the same importers as the Excel screen, then closed into capital;
 *  - the go-live month (sales, an installment plan, purchases, expenses, collections);
 *  - last month in full (DemoMonthSeeder);
 *  - this month up to today, so the dashboard and the bell have something to say: a sale
 *    today, an overdue installment and one due this week, a reservation ending soon and
 *    stale stock.
 *
 *   php artisan migrate:fresh --seed && php artisan db:seed --class=DemoSeeder
 *
 * It runs once: on a database that already has the demo users it does nothing.
 */
class DemoSeeder extends DemoMonthSeeder
{
    private CarbonImmutable $realNow;

    private CarbonImmutable $opening;

    /** @var array<string, Cashbox> */
    private array $boxes = [];

    /** @var array<string, User> */
    private array $staff = [];

    public function run(): void
    {
        if (User::query()->where('username', 'sales1')->exists()) {
            $this->command?->warn('Demo data already present — nothing to do.');

            return;
        }

        $this->realNow = CarbonImmutable::now();
        $this->opening = $this->realNow->subMonthsNoOverflow(2)->startOfMonth()->subDay();
        foreach (array_unique([$this->opening->year, $this->realNow->year]) as $year) {
            app(GenerateFiscalYear::class)->handle($year);
        }

        Auth::login(User::query()->where('username', 'admin')->firstOrFail());
        app(Settings::class)->set(['sales.commission_type' => 'percent', 'sales.commission_value' => '1']);

        try {
            $this->boxes = [
                'cash' => Cashbox::query()->where('name', 'خزينة دينار')->firstOrFail(),
                'usd' => Cashbox::query()->where('name', 'خزينة دولار')->firstOrFail(),
                'bank' => Cashbox::query()->where('name', 'حساب مصرفي رئيسي')->firstOrFail(),
            ];
            $this->users();
            $this->goLive();
            $this->goLiveMonth();
        } finally {
            $this->resetClock();
            Auth::logout();
        }

        parent::run(); // last month, day by day

        Auth::login(User::query()->where('username', 'admin')->firstOrFail());
        try {
            $this->currentMonth();
            $this->resetClock();
            app(SendDailyAlerts::class)->handle();
        } finally {
            $this->resetClock();
            Auth::logout();
        }
    }

    private function users(): void
    {
        $password = (string) config('app.seed_admin_password');
        $people = [
            'accountant' => ['accountant', 'نوري الشريف', 'accountant', '0'],
            'cashier' => ['cashier', 'سامي الككلي', 'cashier', '0'],
            'sales1' => ['sales1', 'علي المبروك', 'sales', '2000'],
            'sales2' => ['sales2', 'رانية الحاسي', 'sales', '1500'],
            'purchasing' => ['purchasing', 'مراد بن غلبون', 'purchasing', '0'],
        ];

        foreach ($people as $key => [$username, $name, $role, $discount]) {
            $user = User::query()->create([
                'branch_id' => $this->branchId(), 'name' => $name, 'username' => $username,
                'password' => $password, 'is_active' => true, 'max_discount' => $discount,
            ]);
            $user->assignRole($role);
            $this->staff[$key] = $user;
        }

        $this->staff['cashier']->cashboxes()->attach($this->boxes['cash']);
    }

    /** Go-live: the same importers the Excel screen uses, then approval and closing into capital. */
    private function goLive(): void
    {
        $date = $this->at($this->opening, 16);
        app(ExchangeRateService::class)->setRate($this->usd(), $date, '4.800000');
        $options = ['date' => $this->opening->toDateString()];

        app(ImportParties::class)->import($this->rows([
            ['name' => 'عبد الرحمن القماطي', 'type' => 'عميل', 'phone' => '0913345566', 'national_id' => '119870012345', 'credit_limit' => '30000', 'address' => 'طرابلس - سوق الجمعة'],
            ['name' => 'نجلاء الورفلي', 'type' => 'عميل', 'phone' => '0925566778', 'national_id' => '219900023456', 'address' => 'طرابلس - حي الأندلس'],
            ['name' => 'محمد الزنتاني', 'type' => 'عميل', 'phone' => '0914455667', 'national_id' => '119850034567', 'credit_limit' => '60000'],
            ['name' => 'شركة البناء الحديث', 'type' => 'عميل ومورّد', 'phone' => '0213337788', 'credit_limit' => '100000', 'address' => 'طرابلس - قرجي'],
            ['name' => 'إبراهيم السويحلي', 'type' => 'عميل', 'phone' => '0918899001', 'national_id' => '119920045678', 'address' => 'مصراتة'],
            ['name' => 'آمنة الفرجاني', 'type' => 'عميل', 'phone' => '0927788990', 'national_id' => '219950056789'],
            ['name' => 'شركة النجم لتجارة السيارات', 'type' => 'مورّد', 'phone' => '0214445566', 'address' => 'طرابلس - طريق المطار'],
            ['name' => 'معرض الريان', 'type' => 'مورّد', 'phone' => '0512223344', 'address' => 'مصراتة'],
            ['name' => 'Emirates Auto Export', 'type' => 'مورّد', 'phone' => '+97142223344', 'address' => 'دبي - سوق العوير'],
        ]), $options);

        // [vin, brand, model, year, cost, asking, minimum, colour, mileage, days in stock before go-live, status]
        $cars = [
            ['JTMHV05J804123401', 'تويوتا', 'لاندكروزر', 2019, '285000', '318000', '300000', 'أبيض', 98000, 40, null],
            ['4T1B11HK5LU123402', 'تويوتا', 'كامري', 2020, '98000', '112000', '105000', 'فضي', 61000, 25, null],
            ['JTDBR32E520123403', 'تويوتا', 'كورولا', 2018, '52000', '60500', '57000', 'أبيض', 120000, 70, null],
            ['MR0HA3CD800123404', 'تويوتا', 'هايلكس', 2021, '118000', '132000', '126000', 'أبيض', 45000, 15, null],
            ['KM8J33A47LU123405', 'هيونداي', 'توسان', 2020, '78000', '89000', '84000', 'رمادي', 70000, 50, null],
            ['KMHD841CBKU123406', 'هيونداي', 'إلنترا', 2019, '46000', '54000', '50000', 'أحمر', 88000, 80, null],
            ['5NMS3CAD8JH123407', 'هيونداي', 'سانتافي', 2018, '82000', '94000', '88000', 'أسود', 110000, 35, null],
            ['U5YPG81AAML123408', 'كيا', 'سبورتاج', 2021, '86000', '97000', '92000', 'أزرق', 39000, 10, null],
            ['3KPF24AD7KE123409', 'كيا', 'سيراتو', 2019, '43000', '50000', '47000', 'فضي', 95000, 120, null],
            ['JN8AY2NC0H9123410', 'نيسان', 'باترول', 2017, '195000', '220000', '208000', 'أبيض', 140000, 150, null],
            ['WDD2130421A123411', 'مرسيدس', 'E-Class', 2016, '125000', '145000', '136000', 'أسود', 130000, 100, null],
            ['MMBJNKB40LD123412', 'ميتسوبيشي', 'L200', 2020, '72000', '82000', '78000', 'بيج', 76000, 30, null],
            ['1FM5K8GT5JG123413', 'فورد', 'إكسبلورر', 2018, '88000', '99000', '94000', 'رمادي', 92000, 60, null],
            ['1GNSKCKC8HR123414', 'شيفروليه', 'تاهو', 2017, '110000', '126000', '119000', 'أسود', 125000, 0, 'في الطريق'],
        ];
        app(ImportOpeningStock::class)->import($this->rows(array_map(fn ($c) => [
            'vin' => $c[0], 'brand' => $c[1], 'model' => $c[2], 'year' => $c[3], 'cost' => $c[4], 'asking_price' => $c[5],
            'min_price' => $c[6], 'color' => $c[7], 'mileage' => $c[8], 'received_at' => $this->opening->subDays($c[9])->toDateString(),
            'status' => $c[10], 'condition' => 'مستعملة', 'fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'location' => 'المعرض الرئيسي',
        ], $cars)), $options + ['description' => 'بضاعة أول المدة — جرد المعرض']);
        app(PostOpeningStock::class)->handle(OpeningStock::query()->where('status', DocumentStatus::Draft)->latest('id')->firstOrFail());

        // The sheet names accounts by code, as the accountant would type them.
        $receivables = app(AccountResolver::class)->for(AccountRole::Receivables)->code;
        $payables = app(AccountResolver::class)->for(AccountRole::Payables)->code;
        app(ImportOpeningBalances::class)->import($this->rows([
            ['account' => $this->boxes['cash']->account->code, 'debit' => '85000', 'memo' => 'رصيد الخزينة'],
            ['account' => $this->boxes['bank']->account->code, 'debit' => '240000', 'memo' => 'كشف المصرف'],
            ['account' => $this->boxes['usd']->account->code, 'currency' => 'USD', 'debit' => '8000', 'rate' => '4.8'],
            ['account' => $receivables, 'party' => '119870012345', 'debit' => '18500', 'memo' => 'باقي ثمن سيارة'],
            ['account' => $receivables, 'party' => 'شركة البناء الحديث', 'debit' => '7200'],
            ['account' => $payables, 'party' => 'شركة النجم لتجارة السيارات', 'credit' => '46000'],
            ['account' => $payables, 'party' => 'Emirates Auto Export', 'currency' => 'USD', 'credit' => '5500', 'rate' => '4.8'],
        ]), $options);
        app(PostManualJournal::class)->handle(ManualJournal::query()->where('status', DocumentStatus::Draft)->latest('id')->firstOrFail());

        // The accountant closes the opening balances account into capital.
        $openingAccount = app(AccountResolver::class)->idFor(AccountRole::OpeningBalances);
        $balance = Money::of((string) DB::table('journal_lines')->where('account_id', $openingAccount)->sum(DB::raw('credit_base - debit_base')));
        $this->journal('إقفال الأرصدة الافتتاحية في رأس المال', [
            ['account_id' => $openingAccount, 'debit' => (string) $balance],
            ['account_id' => app(AccountResolver::class)->idFor(AccountRole::Capital), 'credit' => (string) $balance],
        ]);
    }

    private function goLiveMonth(): void
    {
        $month = $this->opening->addDay();
        $day = fn (int $d, int $hour = 10) => $this->at($month->addDays($d - 1), $hour);
        $car = fn (string $vin) => Vehicle::query()->where('vin', $vin)->firstOrFail();
        $party = fn (string $name) => Party::query()->where('name', $name)->firstOrFail();
        $machine = app(VehicleStateMachine::class);
        [$cash, $bank] = [$this->boxes['cash'], $this->boxes['bank']];

        $day(1);
        app(ExchangeRateService::class)->setRate($this->usd(), now(), '4.820000');
        $this->expense('إيجار', $cash, '6000', null, 'إيجار المعرض');

        // The Tahoe arrives: customs, then preparation.
        $day(3);
        $machine->transition($car('1GNSKCKC8HR123414'), VehicleStatus::InCustoms, manual: true);
        $this->expense('شحن', $cash, '6500', $car('1GNSKCKC8HR123414'), 'رسوم جمركية وتخليص');

        $day(5);
        $this->sell($car('JTDBR32E520123403'), $party('إبراهيم السويحلي'), '60500', 'cash', [[$cash, '60500']], ['salesperson_id' => $this->staff['sales1']->id]);

        // Santa Fe on twelve monthly installments, the first one due at the start of next month.
        $day(8);
        $this->sell($car('5NMS3CAD8JH123407'), $party('نجلاء الورفلي'), '94000', 'installment', [[$cash, '24000']], [
            'salesperson_id' => $this->staff['sales2']->id,
            'installment' => ['down_payment' => '24000', 'months' => 12, 'start_date' => $month->addMonth()->toDateString(),
                'guarantor' => ['name' => 'خالد الورفلي', 'phone' => '0917654321', 'relation' => 'أخ']],
        ]);

        $day(9);
        $machine->transition($car('1GNSKCKC8HR123414'), VehicleStatus::InPreparation, manual: true);
        $this->expense('صيانة', $cash, '900', $car('1GNSKCKC8HR123414'), 'تغيير زيوت وفحص');
        $machine->transition($car('1GNSKCKC8HR123414'), VehicleStatus::Available, manual: true);

        $day(12);
        $this->purchase($party('معرض الريان'), [['41000', 'available', '48000'], ['57000', 'available', '65000']]);

        $day(15);
        $this->expense('رواتب', $bank, '16500', null, 'رواتب الموظفين');
        $this->expense('دعاية', $bank, '1800', null, 'إعلانات على وسائل التواصل');

        $day(20);
        $this->sell($car('KMHD841CBKU123406'), $party('محمد الزنتاني'), '54000', 'credit', [], ['salesperson_id' => $this->staff['sales1']->id]);

        $day(25);
        $this->voucher('payment', $bank, '20000', $party('شركة النجم لتجارة السيارات'), $this->role(AccountRole::Payables), 'دفعة من رصيد أول المدة');
        $this->voucher('receipt', $cash, '10000', $party('عبد الرحمن القماطي'), $this->role(AccountRole::Receivables), 'تحصيل من رصيد أول المدة');

        $day(28);
        $this->expense('كهرباء', $cash, '1200', null, 'فاتورة الكهرباء');
    }

    private function currentMonth(): void
    {
        $month = $this->realNow->startOfMonth();
        $day = fn (int $d, int $hour = 10) => $this->at($month->addDays($d - 1), $hour);
        $car = fn (string $vin) => Vehicle::query()->where('vin', $vin)->firstOrFail();
        $party = fn (string $name) => Party::query()->where('name', $name)->firstOrFail();
        [$cash, $bank] = [$this->boxes['cash'], $this->boxes['bank']];

        $day(1, 9);
        app(ExchangeRateService::class)->setRate($this->usd(), now(), '4.970000');
        $this->expense('إيجار', $cash, '6000', null, 'إيجار المعرض');
        $this->purchase($party('Emirates Auto Export'), [['14500', 'in_transit', '86000']], currency: $this->usd(), rate: '4.97');
        $this->purchase($party('معرض الريان'), [['39000', 'available', '45500']], paid: '39000', cashbox: $cash);

        // One installment collected (last month's); this month's stays overdue.
        $day(2, 11);
        $plan = SalesInvoice::query()->where('party_id', $party('نجلاء الورفلي')->id)->where('payment_type', 'installment')->latest('id')->firstOrFail()->installmentPlan;
        $this->voucher('receipt', $cash, (string) $plan->monthly_amount, $party('نجلاء الورفلي'), $this->role(AccountRole::Receivables), 'قسط شهر سابق', reference: $plan);

        // A reservation ending in two days.
        Auth::login($this->staff['sales1']);
        app(CreateReservation::class)->handle([
            'vehicle_id' => $car('JTMHV05J804123401')->id, 'party_id' => $party('آمنة الفرجاني')->id,
            'cashbox_id' => $cash->id, 'deposit' => '10000',
            'expires_at' => $this->realNow->addDays(2)->toDateString(), 'notes' => 'تنتظر تحويل المصرف',
        ]);
        Auth::login(User::query()->where('username', 'admin')->firstOrFail());

        // Installment sale whose first installment falls due this week.
        $day(3, 12);
        $this->sell($car('KM8J33A47LU123405'), $party('عبد الرحمن القماطي'), '89000', 'installment', [[$bank, '30000']], [
            'salesperson_id' => $this->staff['sales2']->id,
            'installment' => ['down_payment' => '30000', 'months' => 10, 'start_date' => $this->realNow->addDays(3)->toDateString(),
                'guarantor' => ['name' => 'سالم القماطي', 'phone' => '0912223344', 'relation' => 'ابن عم']],
        ]);

        $day(4, 10);
        $this->voucher('receipt', $bank, '15000', $party('محمد الزنتاني'), $this->role(AccountRole::Receivables), 'دفعة من حساب السيارة');

        // Today: a cash sale and a small expense.
        $this->at($this->realNow, 11);
        $this->sell($car('MR0HA3CD800123404'), $party('شركة البناء الحديث'), '132000', 'cash', [[$cash, '82000'], [$bank, '50000']], ['salesperson_id' => $this->staff['sales1']->id]);
        $this->expense('متنوعة', $cash, '350', null, 'ضيافة ومستلزمات');
    }

    /**
     * Moves the clock to a moment of that day, never past the real "now".
     */
    private function at(CarbonImmutable $date, int $hour = 10): CarbonImmutable
    {
        $moment = $date->setTime($hour, 0);
        if ($moment->isAfter($this->realNow)) {
            $moment = $this->realNow->isSameDay($moment) ? $this->realNow->subMinutes(5) : $this->realNow;
        }

        Carbon::setTestNow($moment);
        CarbonImmutable::setTestNow($moment);

        return $moment;
    }

    private function resetClock(): void
    {
        Carbon::setTestNow();
        CarbonImmutable::setTestNow();
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>> keyed like spreadsheet rows
     */
    private function rows(array $rows): array
    {
        return array_combine(range(2, count($rows) + 1), $rows);
    }

    private function usd(): Currency
    {
        return Currency::query()->where('code', 'USD')->firstOrFail();
    }

    private function role(AccountRole $role): int
    {
        return app(AccountResolver::class)->idFor($role);
    }
}
