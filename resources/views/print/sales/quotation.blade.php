@extends('print.layout')

@section('content')
    <h1>{{ __('print.quotation') }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('app.fields.date') }}</td><td class="num">{{ now()->format('Y-m-d') }}</td>
            <td class="label">{{ __('print.reference') }}</td><td class="num">{{ $invoice->displayNumber() }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.customer') }}</td><td>{{ $invoice->party->name }}</td>
            <td class="label">{{ __('app.fields.phone') }}</td><td class="num">{{ $invoice->party->phone }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.payment_type') }}</td><td>{{ $invoice->payment_type->label() }}</td>
            <td class="label">{{ __('sales.salesperson') }}</td><td>{{ $invoice->salesperson->name }}</td>
        </tr>
    </table>

    @include('print.sales.partials.vehicles')
    @include('print.sales.partials.totals')

    @if ($invoice->installmentPlan)
        <p>{{ __('print.installment_summary', [
            'months' => $invoice->installmentPlan->months,
            'amount' => \App\Support\Money::format($invoice->installmentPlan->monthly_amount),
            'start' => $invoice->installmentPlan->start_date->format('Y-m-d'),
        ]) }}</p>
    @endif

    <p class="muted">{{ __('print.quotation_note') }}</p>
@endsection
