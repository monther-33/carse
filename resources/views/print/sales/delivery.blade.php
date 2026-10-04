@extends('print.layout')

@section('content')
    <h1>{{ __('print.delivery') }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('sales.invoice_number') }}</td><td class="num">{{ $invoice->number }}</td>
            <td class="label">{{ __('print.delivered_at') }}</td><td class="num">{{ $invoice->delivered_at->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.customer') }}</td><td>{{ $invoice->party->name }}</td>
            <td class="label">{{ __('parties.national_id') }}</td><td class="num">{{ $invoice->party->national_id }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('print.delivered_by') }}</td><td>{{ $invoice->deliverer?->name }}</td>
            <td></td><td></td>
        </tr>
    </table>

    @include('print.sales.partials.vehicles', ['withPrices' => false])

    <p>{{ __('print.delivery_statement') }}</p>

    <table class="signatures">
        <tr>
            <td>{{ __('print.delivered_by') }}<br>........................</td>
            <td>{{ __('print.received_by') }}<br>........................</td>
        </tr>
    </table>
@endsection
