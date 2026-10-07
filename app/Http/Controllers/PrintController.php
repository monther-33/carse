<?php

namespace App\Http\Controllers;

use App\Enums\AccountRole;
use App\Models\Cashbox;
use App\Models\Party;
use App\Models\SalesInvoice;
use App\Models\Vehicle;
use App\Models\VehicleOwnership;
use App\Models\Voucher;
use App\Reports\PartyStatement;
use App\Services\Accounting\AccountResolver;
use App\Support\Pdf;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Printable A4 documents with the showroom letterhead (name, logo, phone, address from settings).
 */
class PrintController extends Controller
{
    public function __construct(private readonly Settings $settings) {}

    public function sales(SalesInvoice $invoice, string $document): Response
    {
        Gate::authorize('print', $invoice);

        // A quotation is a draft; every other document needs a posted sale.
        abort_unless($document === 'quotation' ? $invoice->isDraft() : $invoice->isPosted(), 404);
        abort_if($document === 'delivery' && $invoice->delivered_at === null, 404);
        abort_if($document === 'schedule' && $invoice->installmentPlan()->doesntExist(), 404);

        $invoice->load([
            'party', 'salesperson', 'currency', 'items.vehicle.brand', 'items.vehicle.carModel', 'items.vehicle.color',
            'tradeIn.vehicle.brand', 'tradeIn.vehicle.carModel', 'installmentPlan.installments', 'installmentPlan.guarantor', 'deliverer',
        ]);

        return Pdf::inline("print.sales.{$document}", $this->letterhead() + ['invoice' => $invoice], ($invoice->number ?? 'quotation-'.$invoice->id)."-{$document}.pdf");
    }

    public function voucher(Voucher $voucher): Response
    {
        Gate::authorize('view', $voucher);
        abort_unless($voucher->isPosted(), 404);
        abort_unless(request()->user()->can('view', Cashbox::query()->findOrFail($voucher->cashbox_id)), 403);

        $voucher->load(['party', 'cashbox', 'toCashbox', 'account', 'currency', 'approver']);

        return Pdf::inline('print.voucher', $this->letterhead() + ['voucher' => $voucher], $voucher->number.'.pdf');
    }

    /**
     * The full vehicle card: details, prices, costs (with vehicles.view_cost), purchase,
     * sale and status history.
     */
    public function vehicle(Request $request, Vehicle $vehicle): Response
    {
        Gate::authorize('view', $vehicle);

        $vehicle->load(['brand', 'carModel', 'color', 'location', 'purchaseInvoice.party', 'saleInvoice.party', 'statusLogs.user', 'costs']);

        return Pdf::inline('print.vehicle', $this->letterhead() + [
            'vehicle' => $vehicle,
            'canViewCost' => $request->user()->can('vehicles.view_cost'),
        ], 'vehicle-'.$vehicle->vin.'.pdf');
    }

    /** Receipt of a consignment car: the car, its owners and the agreement, signed by both sides. */
    public function consignment(VehicleOwnership $ownership): Response
    {
        abort_unless($ownership->isConsignment(), 404);

        $ownership->load(['vehicle.brand', 'vehicle.carModel', 'vehicle.color', 'owners.party']);

        return Pdf::inline('print.consignment', $this->letterhead() + ['ownership' => $ownership], $ownership->number.'.pdf');
    }

    public function statement(Request $request, Party $party, AccountResolver $accounts): Response
    {
        Gate::authorize('viewStatement', $party);

        $from = $request->date('from') ? CarbonImmutable::parse($request->date('from')) : now()->startOfYear()->toImmutable();
        $to = $request->date('to') ? CarbonImmutable::parse($request->date('to')) : now()->toImmutable();
        $statement = new PartyStatement($party, $accounts->partyAccountIds(), $from, $to);

        return Pdf::inline('print.statement', $this->letterhead() + [
            'party' => $party,
            'from' => $from,
            'to' => $to,
            'opening' => $statement->opening(),
            'lines' => $statement->lines(),
            'closing' => $statement->closing(),
            'receivablesId' => $accounts->idFor(AccountRole::Receivables),
        ], 'statement-'.$party->id.'.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    private function letterhead(): array
    {
        $logo = $this->settings->get('company.logo');

        return [
            'company' => [
                'name' => $this->settings->get('company.name', config('app.name')),
                'phone' => $this->settings->get('company.phone'),
                'address' => $this->settings->get('company.address'),
                'logo' => $logo && Storage::disk('public')->exists($logo) ? Storage::disk('public')->path($logo) : null,
            ],
            'contractTerms' => $this->settings->get('print.contract_terms'),
        ];
    }
}
