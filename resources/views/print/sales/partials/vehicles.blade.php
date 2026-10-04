<table class="grid">
    <thead>
    <tr>
        <th>#</th>
        <th>{{ __('vehicles.vehicle') }}</th>
        <th>{{ __('vehicles.vin') }}</th>
        <th>{{ __('vehicles.plate') }}</th>
        <th>{{ __('vehicles.color') }}</th>
        <th>{{ __('vehicles.mileage') }}</th>
        @if ($withPrices ?? true)
            <th>{{ __('sales.price') }}</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach ($invoice->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->vehicle->title() }} — {{ $item->vehicle->condition->label() }}</td>
            <td class="num">{{ $item->vehicle->vin }}</td>
            <td class="num">{{ $item->vehicle->plate_no }}</td>
            <td>{{ $item->vehicle->color?->name }}</td>
            <td class="num">{{ $item->vehicle->mileage !== null ? number_format($item->vehicle->mileage) : '' }}</td>
            @if ($withPrices ?? true)
                <td class="num">{{ \App\Support\Money::format($item->price) }}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
