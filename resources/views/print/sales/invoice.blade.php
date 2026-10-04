@extends('print.layout')

@section('content')
    <h1>{{ __('print.invoice') }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('documents.number') }}</td><td class="num">{{ $invoice->number }}</td>
            <td class="label">{{ __('app.fields.date') }}</td><td class="num">{{ $invoice->date->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.customer') }}</td><td>{{ $invoice->party->name }}</td>
            <td class="label">{{ __('app.fields.phone') }}</td><td class="num">{{ $invoice->party->phone }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('parties.national_id') }}</td><td class="num">{{ $invoice->party->national_id }}</td>
            <td class="label">{{ __('sales.payment_type') }}</td><td>{{ $invoice->payment_type->label() }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.salesperson') }}</td><td>{{ $invoice->salesperson->name }}</td>
            <td class="label">{{ __('app.fields.currency') }}</td><td>{{ $invoice->currency->name }}</td>
        </tr>
    </table>

    @include('print.sales.partials.vehicles')

    @if ($invoice->tradeIn)
        <p>{{ __('sales.trade_in') }}: {{ $invoice->tradeIn->vehicle->title() }} — <span class="num">{{ $invoice->tradeIn->vehicle->vin }}</span></p>
    @endif

    @include('print.sales.partials.totals')

    <table class="signatures">
        <tr>
            <td>{{ __('print.seller_signature') }}<br>........................</td>
            <td>{{ __('print.buyer_signature') }}<br>........................</td>
        </tr>
    </table>
@endsection
