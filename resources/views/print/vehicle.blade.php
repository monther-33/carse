@extends('print.layout')

@section('content')
    <h1>{{ __('vehicles.card') }} — {{ $vehicle->title() }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('vehicles.vin') }}</td><td class="num">{{ $vehicle->vin }}</td>
            <td class="label">{{ __('vehicles.plate') }}</td><td class="num">{{ $vehicle->plate_no }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.color') }}</td><td>{{ $vehicle->color?->name }}</td>
            <td class="label">{{ __('vehicles.mileage') }}</td><td class="num">{{ $vehicle->mileage !== null ? number_format($vehicle->mileage) : '' }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.fuel') }}</td><td>{{ $vehicle->fuel?->label() }}</td>
            <td class="label">{{ __('vehicles.transmission') }}</td><td>{{ $vehicle->transmission?->label() }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.condition') }}</td><td>{{ $vehicle->condition->label() }}</td>
            <td class="label">{{ __('vehicles.origin') }}</td><td>{{ $vehicle->origin }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('app.fields.status') }}</td><td>{{ $vehicle->status->label() }}</td>
            <td class="label">{{ __('vehicles.location') }}</td><td>{{ $vehicle->location?->name }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.received_at') }}</td><td class="num">{{ $vehicle->received_at?->format('Y-m-d') }}</td>
            <td class="label">{{ __('vehicles.age') }}</td><td>{{ $vehicle->status->isInStock() && $vehicle->daysInStock() !== null ? __('vehicles.days', ['count' => $vehicle->daysInStock()]) : '' }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('vehicles.asking_price') }}</td><td class="num">{{ $vehicle->asking_price !== null ? \App\Support\Money::format($vehicle->asking_price) : '' }}</td>
            <td class="label">{{ __('vehicles.min_price') }}</td><td class="num">{{ $vehicle->min_price !== null ? \App\Support\Money::format($vehicle->min_price) : '' }}</td>
        </tr>
    </table>

    @if ($canViewCost)
        <h3>{{ __('vehicles.cost') }}</h3>
        <table class="grid" style="width: 60%">
            <tr><td>{{ __('vehicles.purchase_cost') }}</td><td class="num">{{ \App\Support\Money::format($vehicle->purchase_cost) }}</td></tr>
            @foreach ($vehicle->costs as $cost)
                <tr><td>{{ $cost->description }}@if ($cost->to_cost_of_sales) ({{ __('vehicles.after_sale') }})@endif</td><td class="num">{{ \App\Support\Money::format($cost->amount) }}</td></tr>
            @endforeach
            <tr class="total-row"><td>{{ __('vehicles.total_cost') }}</td><td class="num">{{ \App\Support\Money::format($vehicle->total_cost) }}</td></tr>
        </table>
        @if ($vehicle->purchaseInvoice)
            <p>{{ __('vehicles.bought_from', ['party' => $vehicle->purchaseInvoice->party->name]) }} <span class="num">{{ $vehicle->purchaseInvoice->number }}</span></p>
        @endif
    @endif

    @if ($vehicle->saleInvoice)
        <p>{{ __('print.sold_to', ['party' => $vehicle->saleInvoice->party->name, 'number' => $vehicle->saleInvoice->number, 'date' => $vehicle->sold_at?->format('Y-m-d')]) }}</p>
    @endif

    <h3>{{ __('vehicles.history') }}</h3>
    <table class="grid">
        <thead><tr><th>{{ __('app.fields.date') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.user') }}</th><th>{{ __('app.fields.notes') }}</th></tr></thead>
        <tbody>
        @foreach ($vehicle->statusLogs as $log)
            <tr>
                <td class="num">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $log->from_status?->label() ?? '—' }} ← {{ $log->to_status->label() }}</td>
                <td>{{ $log->user?->name }}</td>
                <td>{{ $log->note }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
