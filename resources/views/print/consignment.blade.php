@extends('print.layout')

@section('content')
    @php($v = $ownership->vehicle)
    <h1>{{ __('ownership.receipt_title') }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('documents.number') }}</td><td class="num">{{ $ownership->number }}</td>
            <td class="label">{{ __('ownership.received_at') }}</td><td class="num">{{ $ownership->received_at->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.vehicle') }}</td><td>{{ $v->title() }}</td>
            <td class="label">{{ __('vehicles.vin') }}</td><td class="num">{{ $v->vin }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.color') }}</td><td>{{ $v->color?->name ?? '—' }}</td>
            <td class="label">{{ __('vehicles.plate') }}</td><td class="num">{{ $v->plate_no ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.mileage') }}</td><td class="num">{{ $v->mileage !== null ? number_format($v->mileage) : '—' }}</td>
            <td class="label">{{ __('vehicles.asking_price') }}</td><td class="num">{{ $v->asking_price ? \App\Support\Money::format($v->asking_price) : '—' }}</td>
        </tr>
    </table>

    <h3 style="color: #14244f; margin: 10px 0 6px">{{ __('ownership.owners') }}</h3>
    <table class="grid">
        <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('parties.national_id') }}</th><th>{{ __('app.fields.phone') }}</th><th>{{ __('ownership.share') }}</th></tr></thead>
        <tbody>
        @foreach ($ownership->owners as $owner)
            <tr>
                <td>{{ $owner->party->name }}</td>
                <td class="num">{{ $owner->party->national_id ?? '—' }}</td>
                <td class="num">{{ $owner->party->phone ?? '—' }}</td>
                <td class="num">{{ rtrim(rtrim($owner->share, '0'), '.') }}%</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3 style="color: #14244f; margin: 10px 0 6px">{{ __('ownership.agreement') }}</h3>
    <p>@include('livewire.ownership.partials.agreement', ['ownership' => $ownership])</p>
    <p>{{ __('ownership.receipt_terms') }}</p>
    @if ($ownership->notes)
        <p>{{ __('app.fields.notes') }}: {{ $ownership->notes }}</p>
    @endif

    <table class="signatures">
        <tr>
            <td>{{ __('ownership.owner_signature') }}<br>........................</td>
            <td>{{ __('ownership.showroom_signature') }}<br>........................</td>
        </tr>
    </table>
@endsection
