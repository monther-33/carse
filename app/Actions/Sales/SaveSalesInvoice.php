<?php

namespace App\Actions\Sales;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\InstallmentStatus;
use App\Enums\PaymentType;
use App\Enums\ReservationStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\Guarantor;
use App\Models\Installment;
use App\Models\InstallmentPlan;
use App\Models\Reservation;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\SalesInvoicePayment;
use App\Models\TradeIn;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use App\Services\Installments\InstallmentScheduleService;
use App\Services\Sales\DepositService;
use App\Services\Vehicles\DraftVehicleResolver;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Creates or updates a DRAFT sales invoice (which is also the printable quotation).
 *
 * Price rules are checked against the user saving it:
 *  - discount (in LYD) above users.max_discount needs sales.override_discount;
 *  - a vehicle's net price below its min_price needs sales.override_min_price.
 */
class SaveSalesInvoice
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ExchangeRateService $rates,
        private readonly DraftVehicleResolver $vehicles,
        private readonly InstallmentScheduleService $schedule,
        private readonly DepositService $deposits,
    ) {}

    /**
     * @param  array<string, mixed>  $data  see App\Livewire\Sales\Form::save() for the shape
     */
    public function handle(array $data, ?SalesInvoice $invoice = null): SalesInvoice
    {
        return DB::transaction(function () use ($data, $invoice) {
            if ($invoice !== null) {
                $invoice = $this->lockInStatus($invoice, DocumentStatus::Draft);
            }

            /** @var User $user */
            $user = Auth::user();
            $type = PaymentType::from($data['payment_type']);
            $currencyId = (int) $data['currency_id'];
            $rate = $this->rates->isBase($currencyId) ? Money::rate(1) : Money::rate((string) $data['rate']);
            $reservation = $this->reservation($data);

            $prices = array_map(fn (array $item) => Money::of((string) $item['price']), $data['items']);
            if ($prices === []) {
                throw BusinessRuleException::make('sales.errors.no_items');
            }
            $discount = Money::of($data['discount'] ?? '0');
            $discounts = Money::allocate($discount, $prices);

            $this->checkVehicles($data['items'], $reservation, $invoice);
            $this->checkPricing($user, $data['items'], $prices, $discounts, $discount, $rate);

            $payments = $this->payments($data['payments'] ?? [], $currencyId);
            $installment = $type === PaymentType::Installment ? ($data['installment'] ?? []) : null;
            $tradeInValue = Money::of($data['trade_in']['value'] ?? '0');

            $terms = new SalesTerms(
                $type,
                Money::sum($prices),
                $discount,
                $tradeInValue,
                $this->depositToApply($data['deposit_applied'] ?? '0', (int) $data['party_id'], $currencyId),
                array_column($payments, 'amount'),
                $installment !== null ? Money::of($installment['down_payment'] ?? '0') : null,
                $installment !== null ? (int) ($installment['months'] ?? 0) : null,
            );
            $terms->validate();

            $invoice ??= new SalesInvoice(['status' => DocumentStatus::Draft, 'branch_id' => $user->branch_id]);
            $invoice->fill([
                'date' => $data['date'],
                'party_id' => $data['party_id'],
                'salesperson_id' => $data['salesperson_id'] ?? $invoice->salesperson_id ?? $user->id,
                'reservation_id' => $reservation?->id,
                'payment_type' => $type,
                'currency_id' => $currencyId,
                'rate' => (string) $rate,
                'subtotal' => (string) $terms->subtotal,
                'discount' => (string) $discount,
                'trade_in_value' => (string) $tradeInValue,
                'total' => (string) $terms->total,
                'deposit_applied' => (string) $terms->deposit,
                'paid' => (string) $terms->paid,
                'notes' => $data['notes'] ?? null,
            ]);
            $invoice->save();

            $this->syncItems($invoice, $data['items'], $prices, $discounts, $rate);
            $this->syncTradeIn($invoice, $data['trade_in'] ?? null, $rate);
            $this->syncPayments($invoice, $payments);
            $this->syncPlan($invoice, $installment, $terms);

            return $invoice->load(['items.vehicle', 'tradeIn.vehicle', 'payments', 'installmentPlan.installments']);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function reservation(array $data): ?Reservation
    {
        if (empty($data['reservation_id'])) {
            return null;
        }

        $reservation = Reservation::query()->findOrFail($data['reservation_id']);

        if ($reservation->status !== ReservationStatus::Active || $reservation->party_id !== (int) $data['party_id']) {
            throw BusinessRuleException::make('sales.errors.reservation');
        }

        return $reservation;
    }

    /**
     * Any part of the customer's posted deposit credit in the invoice currency may be
     * applied — from this reservation, an older cancelled one, or several.
     */
    public function depositToApply(mixed $requested, int $partyId, int $currencyId): BigDecimal
    {
        $amount = Money::of((string) ($requested ?: '0'));

        if ($amount->isNegative()) {
            throw BusinessRuleException::make('sales.errors.deposit_negative');
        }

        $available = $this->deposits->availableCredit($partyId, $currencyId);
        if ($amount->isGreaterThan($available)) {
            throw BusinessRuleException::make('sales.errors.deposit_exceeds', ['available' => Money::format($available)]);
        }

        return $amount;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private function checkVehicles(array $items, ?Reservation $reservation, ?SalesInvoice $invoice): void
    {
        $ids = array_map(fn ($item) => (int) $item['vehicle_id'], $items);
        if (count($ids) !== count(array_unique($ids))) {
            throw BusinessRuleException::make('sales.errors.duplicate_vehicle');
        }

        foreach (Vehicle::query()->findMany($ids) as $vehicle) {
            $reservedForThisSale = $vehicle->status === VehicleStatus::Reserved && $reservation?->vehicle_id === $vehicle->id;

            if ($vehicle->status !== VehicleStatus::Available && ! $reservedForThisSale) {
                throw BusinessRuleException::make('sales.errors.not_sellable', ['vin' => $vehicle->vin, 'status' => $vehicle->status->label()]);
            }
        }

        if ($reservation !== null && ! in_array($reservation->vehicle_id, $ids, true)) {
            throw BusinessRuleException::make('sales.errors.reservation_vehicle');
        }
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  list<BigDecimal>  $prices
     * @param  list<BigDecimal>  $discounts
     */
    private function checkPricing(User $user, array $items, array $prices, array $discounts, BigDecimal $discount, BigDecimal $rate): void
    {
        if (Money::toBase($discount, $rate)->isGreaterThan(Money::of($user->max_discount)) && ! $user->can('sales.override_discount')) {
            throw BusinessRuleException::make('sales.errors.discount_limit', ['limit' => Money::format($user->max_discount)]);
        }

        $vehicles = Vehicle::query()->findMany(array_column($items, 'vehicle_id'))->keyBy('id');

        foreach ($items as $i => $item) {
            $vehicle = $vehicles[(int) $item['vehicle_id']];
            $netBase = Money::toBase($prices[$i]->minus($discounts[$i]), $rate);

            if ($vehicle->min_price !== null && $netBase->isLessThan(Money::of($vehicle->min_price)) && ! $user->can('sales.override_min_price')) {
                throw BusinessRuleException::make('sales.errors.below_min_price', ['vin' => $vehicle->vin, 'min' => Money::format($vehicle->min_price)]);
            }
        }
    }

    /**
     * @param  list<array<string, mixed>>  $payments
     * @return list<array{cashbox_id: int, amount: BigDecimal}>
     */
    private function payments(array $payments, int $currencyId): array
    {
        $result = [];

        foreach ($payments as $payment) {
            $amount = Money::of((string) ($payment['amount'] ?? '0'));
            if (! $amount->isPositive()) {
                continue;
            }

            $cashbox = Cashbox::query()->findOrFail($payment['cashbox_id']);
            if ($cashbox->currency_id !== $currencyId) {
                throw BusinessRuleException::make('documents.errors.cashbox_currency');
            }

            $result[] = ['cashbox_id' => $cashbox->id, 'amount' => $amount];
        }

        return $result;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  list<BigDecimal>  $prices
     * @param  list<BigDecimal>  $discounts
     */
    private function syncItems(SalesInvoice $invoice, array $items, array $prices, array $discounts, BigDecimal $rate): void
    {
        $kept = [];

        foreach ($items as $i => $item) {
            $net = $prices[$i]->minus($discounts[$i]);
            $kept[] = (int) $item['vehicle_id'];

            SalesInvoiceItem::query()->updateOrCreate(
                ['invoice_id' => $invoice->id, 'vehicle_id' => (int) $item['vehicle_id']],
                [
                    'price' => (string) $prices[$i],
                    'discount' => (string) $discounts[$i],
                    'net' => (string) $net,
                    'net_base' => (string) Money::toBase($net, $rate),
                ],
            );
        }

        $invoice->items()->whereNotIn('vehicle_id', $kept)->delete();
    }

    /**
     * @param  array<string, mixed>|null  $data
     */
    private function syncTradeIn(SalesInvoice $invoice, ?array $data, BigDecimal $rate): void
    {
        $existing = TradeIn::query()->where('sales_invoice_id', $invoice->id)->with('vehicle')->first();

        if ($data === null || empty($data['vin'])) {
            if ($existing !== null) {
                $existing->delete();
                if ($existing->vehicle->status === VehicleStatus::Pending) {
                    $existing->vehicle->delete();
                }
            }

            return;
        }

        if (! in_array($data['entry_status'] ?? '', [VehicleStatus::InPreparation->value, VehicleStatus::Available->value], true)) {
            throw BusinessRuleException::make('sales.errors.trade_in_status');
        }

        $value = Money::of((string) $data['value']);
        if (! $value->isPositive()) {
            throw BusinessRuleException::make('sales.errors.trade_in_value');
        }

        $vehicle = $this->vehicles->resolve($data, $invoice->branch_id, fn (Vehicle $v) => $existing?->vehicle_id === $v->id);

        if ($existing !== null && $existing->vehicle_id !== $vehicle->id && $existing->vehicle->status === VehicleStatus::Pending) {
            $old = $existing->vehicle;
            $existing->delete();
            $old->delete();
            $existing = null;
        }

        TradeIn::query()->updateOrCreate(
            ['sales_invoice_id' => $invoice->id],
            [
                'vehicle_id' => $vehicle->id,
                'value' => (string) $value,
                'value_base' => (string) Money::toBase($value, $rate),
                'entry_status' => $data['entry_status'],
            ],
        );
    }

    /**
     * @param  list<array{cashbox_id: int, amount: BigDecimal}>  $payments
     */
    private function syncPayments(SalesInvoice $invoice, array $payments): void
    {
        $invoice->payments()->delete();

        foreach ($payments as $payment) {
            SalesInvoicePayment::query()->create([
                'invoice_id' => $invoice->id,
                'cashbox_id' => $payment['cashbox_id'],
                'amount' => (string) $payment['amount'],
            ]);
        }
    }

    /**
     * The schedule is rebuilt on every save of the draft (nothing can be collected yet).
     *
     * @param  array<string, mixed>|null  $data
     */
    private function syncPlan(SalesInvoice $invoice, ?array $data, SalesTerms $terms): void
    {
        $existing = InstallmentPlan::query()->where('sales_invoice_id', $invoice->id)->first();
        if ($existing !== null) {
            Installment::query()->where('plan_id', $existing->id)->delete();
            $existing->delete();
        }

        if ($data === null) {
            return;
        }

        $guarantorId = $this->guarantor($invoice, $data);
        $startDate = CarbonImmutable::parse($data['start_date'] ?? $invoice->date->copy()->addMonth());
        $schedule = $this->schedule->build($terms->financed(), (int) $data['months'], $startDate);

        $plan = InstallmentPlan::query()->create([
            'sales_invoice_id' => $invoice->id,
            'guarantor_id' => $guarantorId,
            'down_payment' => (string) ($terms->downPayment ?? Money::zero()),
            'financed_amount' => (string) $terms->financed(),
            'months' => (int) $data['months'],
            'monthly_amount' => (string) $schedule[0]['amount'],
            'start_date' => $startDate->toDateString(),
        ]);

        foreach ($schedule as $row) {
            Installment::query()->create([
                'plan_id' => $plan->id,
                'sequence' => $row['sequence'],
                'due_date' => $row['due_date']->toDateString(),
                'amount' => (string) $row['amount'],
                'paid_amount' => '0',
                'status' => InstallmentStatus::Pending,
            ]);
        }
    }

    /**
     * Spec 4.3: installment sales come with a guarantor — an existing one of the customer
     * or a new one entered on the sale screen.
     *
     * @param  array<string, mixed>  $data
     */
    private function guarantor(SalesInvoice $invoice, array $data): int
    {
        if (! empty($data['guarantor_id'])) {
            $guarantor = Guarantor::query()->where('party_id', $invoice->party_id)->find($data['guarantor_id']);
            if ($guarantor === null) {
                throw BusinessRuleException::make('sales.errors.guarantor');
            }

            return $guarantor->id;
        }

        $new = $data['guarantor'] ?? [];
        if (empty($new['name'])) {
            throw BusinessRuleException::make('sales.errors.guarantor');
        }

        return Guarantor::query()->create([
            'party_id' => $invoice->party_id,
            'name' => $new['name'],
            'phone' => $new['phone'] ?? null,
            'national_id' => $new['national_id'] ?? null,
            'relation' => $new['relation'] ?? null,
        ])->id;
    }
}
