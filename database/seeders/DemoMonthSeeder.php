<?php

namespace Database\Seeders;

use App\Actions\Accounting\GenerateFiscalYear;
use App\Actions\Expenses\PostExpense;
use App\Actions\Expenses\SaveExpense;
use App\Actions\Journals\PostManualJournal;
use App\Actions\Journals\SaveManualJournal;
use App\Actions\Purchases\PostPurchaseInvoice;
use App\Actions\Purchases\ReturnPurchaseItem;
use App\Actions\Purchases\SavePurchaseInvoice;
use App\Actions\Reservations\CreateReservation;
use App\Actions\Reservations\EndReservation;
use App\Actions\Sales\PayCommissions;
use App\Actions\Sales\PostSalesInvoice;
use App\Actions\Sales\ReturnSalesItem;
use App\Actions\Sales\SaveSalesInvoice;
use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Enums\PartyType;
use App\Enums\VehicleStatus;
use App\Models\Account;
use App\Models\Branch;
use App\Models\CarModel;
use App\Models\Cashbox;
use App\Models\Commission;
use App\Models\Currency;
use App\Models\ExpenseCategory;
use App\Models\Party;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use App\Services\Vehicles\VehicleStateMachine;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * One full month of realistic activity (last month), run through the same Actions the
 * screens use, with the clock moved day by day: capital, purchases in LYD and USD,
 * customs, expenses, supplier payments at a new rate, reservations (one sold, one cancelled
 * with a forfeit), cash / credit / installment-with-trade-in / mixed / dollar sales,
 * collections, a sales and a purchase return, commissions, a partner drawing and a transfer.
 *
 *   php artisan db:seed --class=DemoMonthSeeder
 */
class DemoMonthSeeder extends Seeder
{
    private CarbonImmutable $start;

    public function run(): void
    {
        $this->start = CarbonImmutable::now()->subMonthNoOverflow()->startOfMonth();
        app(GenerateFiscalYear::class)->handle($this->start->year);

        Auth::login(User::query()->where('email', 'admin@cars.local')->firstOrFail());
        app(Settings::class)->set(['sales.commission_type' => 'percent', 'sales.commission_value' => '1']);

        try {
            $this->month();
        } finally {
            Carbon::setTestNow();
            CarbonImmutable::setTestNow();
            Auth::logout();
        }
    }

    protected function branchId(): int
    {
        return (int) Branch::query()->value('id');
    }

    /** A 17-character VIN-like code (no I, O or Q). */
    protected function vin(string $prefix): string
    {
        $chars = 'ABCDEFGHJKLMNPRSTUVWXYZ0123456789';
        $vin = $prefix;
        while (strlen($vin) < 17) {
            $vin .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $vin;
    }

    private function day(int $day): CarbonImmutable
    {
        $date = $this->start->addDays($day - 1)->setTime(10, 0);
        Carbon::setTestNow($date);
        CarbonImmutable::setTestNow($date);

        return $date;
    }

    private function month(): void
    {
        $lyd = Currency::query()->where('code', 'LYD')->firstOrFail();
        $usd = Currency::query()->where('code', 'USD')->firstOrFail();
        $cash = Cashbox::query()->where('name', 'خزينة دينار')->firstOrFail();
        $cashUsd = Cashbox::query()->where('name', 'خزينة دولار')->firstOrFail();
        $bank = Cashbox::query()->where('name', 'حساب مصرفي رئيسي')->firstOrFail();
        $code = fn (string $c) => Account::query()->where('code', $c)->value('id');
        $rates = app(ExchangeRateService::class);

        // Day 1 — capital and opening rates.
        $date = $this->day(1);
        $rates->setRate($usd, $date, '4.850000');
        $this->journal('رأس المال الافتتاحي', [
            ['account_id' => $cash->account_id, 'debit' => '600000'],
            ['account_id' => $bank->account_id, 'debit' => '400000'],
            ['account_id' => $cashUsd->account_id, 'debit' => '50000', 'currency_id' => $usd->id, 'rate' => '4.85'],
            ['account_id' => $code('31'), 'credit' => '1242500'],
        ]);

        $suppliers = collect(['شركة الواحة للسيارات', 'معرض الجبل', 'مزاد طرابلس'])
            ->map(fn ($n) => Party::query()->create(['branch_id' => $this->branchId(), 'type' => PartyType::Supplier, 'name' => $n, 'phone' => '0911'.random_int(100000, 999999)]));
        $customers = collect(['أحمد الفيتوري', 'سالم بن عمران', 'خالد المصراتي', 'فاطمة الزاوي', 'عمر الككلي', 'مريم الترهوني', 'يوسف الشريف', 'هدى البرعصي'])
            ->map(fn ($n) => Party::query()->create(['branch_id' => $this->branchId(), 'type' => PartyType::Customer, 'name' => $n, 'phone' => '0925'.random_int(100000, 999999)]));

        // Day 2 — local purchases (cash and credit).
        $this->day(2);
        $local = $this->purchase($suppliers[0], [['45000', 'available', '52000'], ['38000', 'available', '44000'], ['61000', 'in_preparation', '70000']], paid: '80000', cashbox: $cash);
        $auction = $this->purchase($suppliers[2], [['27000', 'available', '32000'], ['33500', 'available', '39000'], ['52000', 'available', '60000']], source: 'auction');

        // Day 3 — dollar import, cars on the way.
        $this->day(3);
        $import = $this->purchase($suppliers[1], [['9000', 'in_transit', '55000'], ['11500', 'in_transit', '68000']], currency: $usd, rate: '4.85', discount: '500');

        // Day 5 — rent, salaries advance, advertising; preparation costs on cars.
        $this->day(5);
        $this->expense('إيجار', $cash, '6000', null, 'إيجار المعرض');
        $this->expense('دعاية', $bank, '2500', null, 'إعلانات ممولة');
        $this->expense('صيانة', $cash, '1800', $local->items[2]->vehicle, 'تجهيز وصيانة');
        $this->expense('صيانة', $cash, '650', $auction->items[0]->vehicle, 'تلميع وتنظيف');

        // Day 8 — imports reach customs, duties paid on the cars.
        $this->day(8);
        foreach ($import->items as $item) {
            app(VehicleStateMachine::class)->transition($item->vehicle, VehicleStatus::InCustoms, manual: true);
            $this->expense('شحن', $cash, '4200', $item->vehicle->fresh(), 'جمارك وتخليص');
        }

        // Day 10 — reservations; cash sale; local car ready.
        $this->day(10);
        app(VehicleStateMachine::class)->transition($local->items[2]->vehicle->fresh(), VehicleStatus::Available, manual: true);
        $reserved = app(CreateReservation::class)->handle(['vehicle_id' => $local->items[0]->vehicle_id, 'party_id' => $customers[0]->id,
            'cashbox_id' => $cash->id, 'deposit' => '5000', 'expires_at' => $this->start->addDays(20)->toDateString()]);
        $dropped = app(CreateReservation::class)->handle(['vehicle_id' => $auction->items[2]->vehicle_id, 'party_id' => $customers[5]->id,
            'cashbox_id' => $cash->id, 'deposit' => '3000', 'expires_at' => $this->start->addDays(14)->toDateString()]);
        $this->sell($auction->items[0]->vehicle, $customers[1], '32000', 'cash', [[$cash, '32000']]);

        // Day 12 — imports cleared and prepared; supplier paid in dollars at a new rate.
        $date = $this->day(12);
        $rates->setRate($usd, $date, '4.900000');
        foreach ($import->items as $item) {
            app(VehicleStateMachine::class)->transition($item->vehicle->fresh(), VehicleStatus::InPreparation, manual: true);
            app(VehicleStateMachine::class)->transition($item->vehicle->fresh(), VehicleStatus::Available, manual: true);
        }
        $this->voucher('payment', $cashUsd, '15000', $suppliers[1], $code('21'), 'دفعة للمورد بالدولار', rate: '4.90');

        // Day 14 — the reserved car is sold on installments with a trade-in.
        $this->day(14);
        $installmentSale = $this->sell($local->items[0]->vehicle, $customers[0], '52000', 'installment', [[$cash, '7000']], [
            'reservation_id' => $reserved->id,
            'deposit_applied' => '5000',
            'trade_in' => $this->tradeIn('15000'),
            'installment' => ['down_payment' => '7000', 'months' => 10, 'start_date' => $this->start->addMonth()->toDateString(),
                'guarantor' => ['name' => 'محمود الفيتوري', 'phone' => '0913334455', 'relation' => 'أخ']],
        ]);

        // Day 15 — the other reservation is cancelled: part refunded, part forfeited.
        $this->day(15);
        app(EndReservation::class)->cancel($dropped, 'عدل عن الشراء', refund: '2000', cashboxId: $cash->id, forfeit: '1000');

        // Day 16 — credit sale, mixed sale.
        $this->day(16);
        $credit = $this->sell($auction->items[1]->vehicle, $customers[2], '39000', 'credit');
        $this->sell($local->items[1]->vehicle, $customers[3], '44000', 'mixed', [[$cash, '20000'], [$bank, '14000']]);

        // Day 18 — a dollar sale of an imported car.
        $this->day(18);
        $usdSale = $this->sell($import->items[0]->vehicle, $customers[4], '12500', 'credit', [], ['currency_id' => $usd->id, 'rate' => '4.90']);

        // Day 20 — electricity, salaries.
        $this->day(20);
        $this->expense('كهرباء', $cash, '1350', null, 'فاتورة الكهرباء');
        $this->expense('رواتب', $bank, '18000', null, 'رواتب الموظفين');

        // Day 22 — collections: credit customer pays part, dollar customer pays in full at 4.95.
        $date = $this->day(22);
        $rates->setRate($usd, $date, '4.950000');
        $this->voucher('receipt', $bank, '20000', $customers[2], $code('13'), 'دفعة من العميل', reference: $credit);
        $this->voucher('receipt', $cashUsd, '12500', $customers[4], $code('13'), 'سداد بالدولار', rate: '4.95', reference: $usdSale);

        // Day 24 — two early installments collected.
        $this->day(24);
        $plan = $installmentSale->installmentPlan;
        $this->voucher('receipt', $cash, (string) $plan->monthly_amount, $customers[0], $code('13'), 'قسط', reference: $plan);
        $this->voucher('receipt', $cash, (string) $plan->monthly_amount, $customers[0], $code('13'), 'قسط', reference: $plan);

        // Day 25 — a car sold on the 16th comes back; a purchased car goes back to its supplier.
        $this->day(25);
        app(ReturnSalesItem::class)->handle($credit->items->first(), 'عيب في ناقل الحركة');
        app(ReturnPurchaseItem::class)->handle($auction->items[2]->fresh(), 'مستندات ناقصة');

        // Day 27 — commissions paid, supplier paid, partner drawing, cash moved to the bank.
        $this->day(27);
        $sellers = Commission::query()->where('status', 'accrued')->get()->groupBy('user_id');
        foreach ($sellers as $userId => $commissions) {
            app(PayCommissions::class)->handle(User::query()->findOrFail($userId), $commissions->pluck('id')->all(), $cash);
        }
        $this->voucher('payment', $bank, '64000', $suppliers[0], $code('21'), 'سداد مورد', reference: $local);
        $this->journal('مسحوبات شريك', [
            ['account_id' => $code('32'), 'debit' => '10000'],
            ['account_id' => $cash->account_id, 'credit' => '10000'],
        ]);
        $this->voucher('transfer', $cash, '50000', null, null, 'إيداع في المصرف', toCashbox: $bank);
    }

    /**
     * @param  list<array{0: string, 1: string, 2: string}>  $lines  [price, entry status, asking price]
     */
    protected function purchase(Party $supplier, array $lines, string $paid = '0', ?Cashbox $cashbox = null, ?Currency $currency = null, string $rate = '1', string $discount = '0', string $source = 'supplier'): PurchaseInvoice
    {
        $models = CarModel::query()->inRandomOrder()->limit(count($lines))->get();
        $items = [];
        foreach ($lines as $i => [$price, $status, $asking]) {
            $model = $models[$i % $models->count()];
            $items[] = [
                'vin' => $this->vin('DM'),
                'brand_id' => $model->brand_id, 'model_id' => $model->id, 'year' => random_int(2016, 2024),
                'condition' => 'used', 'fuel' => 'petrol', 'transmission' => 'automatic', 'mileage' => random_int(20000, 140000),
                'entry_status' => $status, 'price' => $price, 'asking_price' => $asking,
                'min_price' => (string) intdiv((int) $asking * 93, 100),
            ];
        }

        $invoice = app(SavePurchaseInvoice::class)->handle([
            'date' => now()->toDateString(), 'party_id' => $supplier->id, 'source' => $source,
            'currency_id' => ($currency ?? Currency::query()->where('is_base', true)->firstOrFail())->id, 'rate' => $rate,
            'discount' => $discount, 'paid' => $paid, 'cashbox_id' => $cashbox?->id, 'notes' => null, 'items' => $items,
        ]);

        return app(PostPurchaseInvoice::class)->handle($invoice);
    }

    /**
     * @param  list<array{0: Cashbox, 1: string}>  $payments
     * @param  array<string, mixed>  $extra
     */
    protected function sell(Vehicle $vehicle, Party $customer, string $price, string $type, array $payments = [], array $extra = []): SalesInvoice
    {
        $draft = app(SaveSalesInvoice::class)->handle($extra + [
            'date' => now()->toDateString(), 'party_id' => $customer->id, 'payment_type' => $type,
            'currency_id' => Currency::query()->where('is_base', true)->value('id'), 'rate' => '1', 'discount' => '0',
            'items' => [['vehicle_id' => $vehicle->id, 'price' => $price]],
            'payments' => array_map(fn ($p) => ['cashbox_id' => $p[0]->id, 'amount' => $p[1]], $payments),
            'trade_in' => null, 'installment' => null, 'notes' => null,
        ]);

        return app(PostSalesInvoice::class)->handle($draft);
    }

    /** @return array<string, mixed> */
    protected function tradeIn(string $value): array
    {
        $model = CarModel::query()->inRandomOrder()->firstOrFail();

        return [
            'vin' => $this->vin('TR'), 'brand_id' => $model->brand_id, 'model_id' => $model->id,
            'year' => 2015, 'condition' => 'used', 'value' => $value, 'entry_status' => 'in_preparation',
            'asking_price' => (string) ((int) $value + 3000),
        ];
    }

    protected function expense(string $category, Cashbox $cashbox, string $amount, ?Vehicle $vehicle, string $description): void
    {
        $categoryId = ExpenseCategory::query()->where('name', 'like', "%{$category}%")->value('id') ?? ExpenseCategory::query()->value('id');

        $expense = app(SaveExpense::class)->handle([
            'date' => now()->toDateString(), 'category_id' => $categoryId, 'cashbox_id' => $cashbox->id,
            'amount' => $amount, 'vehicle_id' => $vehicle?->id, 'description' => $description, 'recurs_every_months' => null,
        ]);
        app(PostExpense::class)->handle($expense);
    }

    protected function voucher(string $type, Cashbox $cashbox, string $amount, ?Party $party, ?int $accountId, string $description, ?string $rate = null, mixed $reference = null, ?Cashbox $toCashbox = null): void
    {
        $voucher = app(SaveVoucher::class)->handle([
            'type' => $type, 'date' => now()->toDateString(), 'cashbox_id' => $cashbox->id, 'to_cashbox_id' => $toCashbox?->id,
            'party_id' => $party?->id, 'account_id' => $accountId, 'amount' => $amount, 'rate' => $rate, 'description' => $description,
            'reference_type' => $reference?->getMorphClass(), 'reference_id' => $reference?->getKey(),
        ]);
        app(PostVoucher::class)->handle($voucher);
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     */
    protected function journal(string $description, array $lines): void
    {
        $journal = app(SaveManualJournal::class)->handle(['date' => now()->toDateString(), 'description' => $description, 'lines' => $lines]);
        app(PostManualJournal::class)->handle($journal);
    }
}
