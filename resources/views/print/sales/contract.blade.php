@extends('print.layout')

@section('content')
    <h1>{{ __('print.contract') }}</h1>

    <p>{{ __('print.contract_intro', ['date' => $invoice->date->format('Y-m-d'), 'number' => $invoice->number]) }}</p>

    <table class="info">
        <tr><td class="label">{{ __('print.first_party') }}</td><td>{{ $company['name'] }}</td></tr>
        <tr>
            <td class="label">{{ __('print.second_party') }}</td>
            <td>
                {{ $invoice->party->name }}
                @if ($invoice->party->national_id) — {{ __('parties.national_id') }}: <span class="num">{{ $invoice->party->national_id }}</span>@endif
                @if ($invoice->party->address) — {{ $invoice->party->address }}@endif
                @if ($invoice->party->phone) — <span class="num">{{ $invoice->party->phone }}</span>@endif
            </td>
        </tr>
    </table>

    <p><strong>{{ __('print.contract_vehicles') }}</strong></p>
    @include('print.sales.partials.vehicles')

    <p><strong>{{ __('print.contract_price') }}</strong></p>
    @include('print.sales.partials.totals')

    @if ($invoice->installmentPlan)
        @php($plan = $invoice->installmentPlan)
        <p>{{ __('print.installment_summary', [
            'months' => $plan->months,
            'amount' => \App\Support\Money::format($plan->monthly_amount),
            'start' => $plan->start_date->format('Y-m-d'),
        ]) }}</p>
        @if ($plan->guarantor)
            <p>{{ __('print.guarantor_line', ['name' => $plan->guarantor->name, 'id' => $plan->guarantor->national_id ?? '—', 'phone' => $plan->guarantor->phone ?? '—']) }}</p>
        @endif
    @endif

    @if ($invoice->tradeIn)
        <p>{{ __('print.trade_in_line', [
            'vehicle' => $invoice->tradeIn->vehicle->title(),
            'vin' => $invoice->tradeIn->vehicle->vin,
            'value' => \App\Support\Money::format($invoice->tradeIn->value),
        ]) }}</p>
    @endif

    @if ($contractTerms)
        <p><strong>{{ __('print.contract_terms') }}</strong></p>
        <div class="terms">{!! nl2br(e($contractTerms)) !!}</div>
    @endif

    <table class="signatures">
        <tr>
            <td>{{ __('print.first_party') }}<br>........................</td>
            <td>{{ __('print.second_party') }}<br>........................</td>
            @if ($invoice->installmentPlan?->guarantor)
                <td>{{ __('sales.guarantor') }}<br>........................</td>
            @endif
        </tr>
    </table>
@endsection
