@extends('print.layout')

@section('content')
    <h1>{{ __('parties.statement_of', ['name' => $party->name]) }}</h1>
    <p class="muted">{{ __('reports.from') }} <span class="num">{{ $from->format('Y-m-d') }}</span> {{ __('reports.to') }} <span class="num">{{ $to->format('Y-m-d') }}</span></p>

    <table class="grid">
        <thead>
        <tr>
            <th>{{ __('app.fields.date') }}</th>
            <th>{{ __('documents.number') }}</th>
            <th>{{ __('app.fields.description') }}</th>
            <th>{{ __('reports.debit') }}</th>
            <th>{{ __('reports.credit') }}</th>
            <th>{{ __('reports.balance') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr class="total-row"><td colspan="5">{{ __('reports.opening_balance') }}</td><td class="num">{{ \App\Support\Money::format($opening) }}</td></tr>
        @foreach ($lines as $line)
            <tr>
                <td class="num">{{ $line->date }}</td>
                <td class="num">{{ $line->number }}</td>
                <td>{{ $line->description }}</td>
                <td class="num">{{ \App\Support\Money::of($line->debit_base)->isPositive() ? \App\Support\Money::format($line->debit_base) : '' }}</td>
                <td class="num">{{ \App\Support\Money::of($line->credit_base)->isPositive() ? \App\Support\Money::format($line->credit_base) : '' }}</td>
                <td class="num">{{ \App\Support\Money::format($line->balance) }}</td>
            </tr>
        @endforeach
        <tr class="total-row"><td colspan="5">{{ __('reports.closing_balance') }}</td><td class="num">{{ \App\Support\Money::format($closing) }}</td></tr>
        </tbody>
    </table>
    <p class="muted">{{ __('reports.balance_sign_hint') }}</p>
@endsection
