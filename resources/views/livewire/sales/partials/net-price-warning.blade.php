{{-- Consignment car below what its owners were promised: a warning only, the showroom bears it (App\Services\Ownership\NetPriceCheck). --}}
@foreach ($warnings as $w)
    <div class="flex items-start gap-2 rounded-md border border-yellow-300 bg-yellow-50 p-3 text-sm text-yellow-900">
        <x-ui.icon name="shield" class="mt-0.5 h-5 w-5 shrink-0" />
        <p>{{ __('ownership.below_net_price', ['vin' => $w['vin'], 'short' => \App\Support\Money::format($w['short'])]) }}</p>
    </div>
@endforeach
