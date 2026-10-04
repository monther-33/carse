@extends('print.layout')

@section('content')
    @php($plan = $invoice->installmentPlan)
    <h1>{{ __('print.schedule') }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('sales.invoice_number') }}</td><td class="num">{{ $invoice->number }}</td>
            <td class="label">{{ __('sales.customer') }}</td><td>{{ $invoice->party->name }} — <span class="num">{{ $invoice->party->phone }}</span></td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.financed') }}</td><td class="num">{{ \App\Support\Money::format($plan->financed_amount) }} {{ $invoice->currency->code }}</td>
            <td class="label">{{ __('sales.months') }}</td><td class="num">{{ $plan->months }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('sales.down_payment') }}</td><td class="num">{{ \App\Support\Money::format($plan->down_payment) }}</td>
            <td class="label">{{ __('sales.guarantor') }}</td><td>{{ $plan->guarantor?->name }} <span class="num">{{ $plan->guarantor?->phone }}</span></td>
        </tr>
    </table>

    <table class="grid">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('sales.due_date') }}</th>
            <th>{{ __('documents.amount') }}</th>
            <th>{{ __('documents.paid') }}</th>
            <th>{{ __('app.fields.status') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($plan->installments as $inst)
            <tr>
                <td class="num">{{ $inst->sequence }}</td>
                <td class="num">{{ $inst->due_date->format('Y-m-d') }}</td>
                <td class="num">{{ \App\Support\Money::format($inst->amount) }}</td>
                <td class="num">{{ \App\Support\Money::format($inst->paid_amount) }}</td>
                <td>{{ $inst->status->label() }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">{{ __('documents.total') }}</td>
            <td class="num">{{ \App\Support\Money::format($plan->financed_amount) }}</td>
            <td class="num">{{ \App\Support\Money::format(\App\Support\Money::sum($plan->installments->pluck('paid_amount')->all())) }}</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <div class="words">{{ __('print.amount_in_words') }}: {{ \App\Support\Tafqeet::amount($plan->financed_amount, $invoice->currency->code) }}</div>

    <table class="signatures">
        <tr>
            <td>{{ __('print.buyer_signature') }}<br>........................</td>
            <td>{{ __('sales.guarantor') }}<br>........................</td>
        </tr>
    </table>
@endsection
