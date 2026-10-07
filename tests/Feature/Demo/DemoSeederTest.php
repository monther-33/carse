<?php

use App\Enums\AccountRole;
use App\Enums\DocumentStatus;
use App\Enums\OwnershipKind;
use App\Enums\OwnershipStatus;
use App\Models\OpeningStock;
use App\Models\Party;
use App\Models\SalesInvoice;
use App\Models\User;
use App\Models\VehicleOwnership;
use App\Notifications\DailyAlerts;
use App\Reports\Accounting\BalanceSheetReport;
use App\Reports\DashboardMetrics;
use App\Reports\TrialBalance;
use App\Services\Accounting\AccountResolver;
use App\Services\Alerts\AlertDigest;
use App\Services\Ownership\OwnerPayouts;
use Carbon\CarbonImmutable;
use Database\Seeders\DemoSeeder;
use Illuminate\Support\Facades\Hash;

/*
| The demo data a user tries the system with: built only through the real Actions and
| importers, it must leave the books balanced and give every dashboard widget something.
*/
beforeEach(function () {
    $this->seed(DemoSeeder::class);
    $this->admin = User::query()->where('username', 'admin')->firstOrFail();
    $this->actingAs($this->admin);
});

test('the demo books balance and the opening balances account is closed into capital', function () {
    $sheet = app(BalanceSheetReport::class)->build(['as_of' => CarbonImmutable::today()->toDateString()]);

    expect((string) $sheet['assets'])->toBe((string) $sheet['liabilities']->plus($sheet['equity']))
        ->and((new TrialBalance)->isBalanced())->toBeTrue()
        ->and(ledgerIsBalanced())->toBeTrue()
        ->and(baseBalance(app(AccountResolver::class)->for(AccountRole::OpeningBalances))->isZero())->toBeTrue()
        ->and(OpeningStock::query()->sole()->status)->toBe(DocumentStatus::Posted);
});

test('there is an active user for every role, with the admin password', function () {
    foreach (['accountant', 'cashier', 'sales1', 'sales2', 'purchasing'] as $name) {
        $user = User::query()->where('username', $name)->firstOrFail();
        expect($user->roles)->toHaveCount(1)
            ->and($user->is_active)->toBeTrue()
            ->and(Hash::check((string) config('app.seed_admin_password'), $user->password))->toBeTrue();
    }
});

test('the dashboard and the bell have something to show today', function () {
    $metrics = app(DashboardMetrics::class);

    expect($metrics->sales($this->admin, CarbonImmutable::today())['count'])->toBeGreaterThan(0)
        ->and($metrics->installments($this->admin)['overdue_count'])->toBeGreaterThan(0)
        ->and($metrics->installments($this->admin)['week_count'])->toBeGreaterThan(0)
        ->and($metrics->expiringReservations($this->admin))->not->toBeEmpty()
        ->and($metrics->staleVehicles($this->admin))->not->toBeEmpty()
        ->and(array_column(app(AlertDigest::class)->for($this->admin), 'key'))->toContain('installments_overdue', 'reservations_expiring', 'stale_critical')
        ->and($this->admin->unreadNotifications()->where('type', DailyAlerts::class)->count())->toBe(1);

    // Sales staff own their sales.
    $seller = User::query()->where('username', 'sales1')->firstOrFail();
    expect(SalesInvoice::query()->visibleTo($seller)->count())->toBeGreaterThan(0)
        ->and(SalesInvoice::query()->visibleTo($seller)->count())->toBeLessThan(SalesInvoice::query()->count());
});

test('seeding the demo twice changes nothing', function () {
    $sales = SalesInvoice::query()->count();
    $this->seed(DemoSeeder::class);

    expect(SalesInvoice::query()->count())->toBe($sales);
});

test('the demo shows consignment and partnership cars in every case', function () {
    $ownerships = VehicleOwnership::query()->get();
    $payouts = app(OwnerPayouts::class);
    $party = fn (string $name) => Party::query()->where('name', $name)->firstOrFail()->id;

    expect($ownerships->where('kind', OwnershipKind::Consignment)->count())->toBe(5)
        ->and($ownerships->where('kind', OwnershipKind::Partnership)->count())->toBe(2)
        ->and($ownerships->where('status', OwnershipStatus::Active)->count())->toBe(2)     // the C-Class and the Hilux
        ->and($ownerships->where('status', OwnershipStatus::Returned)->count())->toBe(1)   // the Sunny
        ->and($ownerships->where('status', OwnershipStatus::Sold)->count())->toBe(4)
        ->and($payouts->balance($party('حسين المصراتي'))->isZero())->toBeTrue()            // paid in full
        ->and((string) $payouts->balance($party('هدى الكيلاني')))->toBe('76000.000')        // the K5, no commission
        ->and((string) $payouts->balance($party('فرج الطرابلسي')))->toBe('104000.000')      // Land Cruiser 138,000 + Sonata 26,000 − paid 60,000 (his 120,000 share paid in)
        ->and($payouts->pending($party('سالم الدرسي'))->isPositive())->toBeTrue()          // Sonata in installments
        ->and((string) $payouts->balance($party('سالم الدرسي')))->toBe('-34000.000')       // Sonata 26,000 − his Hilux share 60,000
        ->and(baseBalance('43')->isNegative())->toBeTrue();
});
