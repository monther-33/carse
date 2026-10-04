<?php

namespace App\Actions\Purchases;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Cashbox;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use App\Services\Vehicles\DraftVehicleResolver;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Creates or updates a DRAFT purchase invoice. Nothing is posted here.
 *
 * Each line describes a vehicle by VIN. A new VIN creates a vehicle in status "pending"
 * (not in stock). A VIN we already know is re-used only if the car is out of stock
 * (sold before, or returned to its supplier) — e.g. buying back a car we once sold.
 */
class SavePurchaseInvoice
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ExchangeRateService $rates,
        private readonly DraftVehicleResolver $vehicles,
    ) {}

    /**
     * @param  array{date: string, party_id: int, source: string, currency_id: int, rate: string|null,
     *               discount: string|null, paid: string|null, cashbox_id: int|null, notes: string|null,
     *               items: list<array<string, mixed>>}  $data
     */
    public function handle(array $data, ?PurchaseInvoice $invoice = null): PurchaseInvoice
    {
        return DB::transaction(function () use ($data, $invoice) {
            if ($invoice !== null) {
                $invoice = $this->lockInStatus($invoice, DocumentStatus::Draft);
            }

            $rate = $this->rates->isBase($data['currency_id']) ? Money::rate(1) : Money::rate((string) $data['rate']);
            $prices = array_map(fn (array $item) => Money::of((string) $item['price']), $data['items']);
            $subtotal = Money::sum($prices);
            $discount = Money::of($data['discount'] ?? '0');
            $total = $subtotal->minus($discount);
            $paid = Money::of($data['paid'] ?? '0');

            if ($total->isNegative() || ! $total->isPositive()) {
                throw BusinessRuleException::make('purchases.errors.total');
            }
            if ($paid->isGreaterThan($total)) {
                throw BusinessRuleException::make('purchases.errors.overpaid');
            }
            if ($paid->isPositive()) {
                $cashbox = Cashbox::query()->findOrFail($data['cashbox_id']);
                if ($cashbox->currency_id !== (int) $data['currency_id']) {
                    throw BusinessRuleException::make('documents.errors.cashbox_currency');
                }
            }

            $invoice ??= new PurchaseInvoice(['status' => DocumentStatus::Draft, 'branch_id' => Auth::user()?->branch_id]);
            $invoice->fill([
                'date' => $data['date'],
                'party_id' => $data['party_id'],
                'source' => $data['source'],
                'currency_id' => $data['currency_id'],
                'rate' => (string) $rate,
                'subtotal' => (string) $subtotal,
                'discount' => (string) $discount,
                'total' => (string) $total,
                'paid' => (string) $paid,
                'cashbox_id' => $paid->isPositive() ? $data['cashbox_id'] : null,
                'notes' => $data['notes'] ?? null,
            ]);
            $invoice->save();

            $this->syncItems($invoice, $data['items'], Money::allocate($discount, $prices));

            return $invoice->load('items.vehicle');
        });
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  list<BigDecimal>  $discounts
     */
    private function syncItems(PurchaseInvoice $invoice, array $items, array $discounts): void
    {
        $keptVehicleIds = [];

        foreach ($items as $i => $item) {
            $vehicle = $this->resolveVehicle($invoice, $item);
            $keptVehicleIds[] = $vehicle->id;

            $price = Money::of((string) $item['price']);
            PurchaseInvoiceItem::query()->updateOrCreate(
                ['invoice_id' => $invoice->id, 'vehicle_id' => $vehicle->id],
                [
                    'entry_status' => $item['entry_status'],
                    'price' => (string) $price,
                    'discount' => (string) $discounts[$i],
                    'net' => (string) $price->minus($discounts[$i]),
                    'cost_base' => (string) Money::toBase($price->minus($discounts[$i]), $invoice->rate),
                ],
            );
        }

        // Lines removed from the draft: drop them, and their vehicles if they only existed here.
        $removed = $invoice->items()->whereNotIn('vehicle_id', $keptVehicleIds)->with('vehicle')->get();
        foreach ($removed as $item) {
            $item->delete();
            if ($item->vehicle->status === VehicleStatus::Pending) {
                $item->vehicle->delete();
            }
        }
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function resolveVehicle(PurchaseInvoice $invoice, array $item): Vehicle
    {
        return $this->vehicles->resolve(
            $item,
            $invoice->branch_id,
            fn (Vehicle $vehicle) => $invoice->items()->where('vehicle_id', $vehicle->id)->exists(),
        );
    }
}
