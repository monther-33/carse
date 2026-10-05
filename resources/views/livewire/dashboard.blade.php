<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @if ($today)
            <x-ui.card>
                <p class="text-xs text-gray-500">{{ __('dashboard.sales_today') }}</p>
                <p class="num mt-1 text-2xl font-bold">{{ \App\Support\Money::format($today['revenue']) }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ __('dashboard.vehicles_sold', ['count' => $today['count']]) }}
                    @if ($today['profit'] !== null) · {{ __('sales.profit') }} <span class="num">{{ \App\Support\Money::format($today['profit']) }}</span>@endif
                </p>
            </x-ui.card>
        @endif
        @if ($month)
            <x-ui.card>
                <p class="text-xs text-gray-500">{{ __('dashboard.sales_month') }}</p>
                <p class="num mt-1 text-2xl font-bold">{{ \App\Support\Money::format($month['revenue']) }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ __('dashboard.vehicles_sold', ['count' => $month['count']]) }}
                    @if ($month['profit'] !== null) · {{ __('sales.profit') }} <span class="num">{{ \App\Support\Money::format($month['profit']) }}</span>@endif
                </p>
            </x-ui.card>
        @endif
        @if ($stock)
            <x-ui.card>
                <p class="text-xs text-gray-500">{{ __('dashboard.available_vehicles') }}</p>
                <p class="mt-1 text-2xl font-bold"><span class="num">{{ $stock['count'] }}</span> <span class="text-sm font-normal text-gray-500">/ {{ __('dashboard.in_stock', ['count' => $stock['in_stock']]) }}</span></p>
                <p class="mt-1 text-xs text-gray-500">{{ __('dashboard.asking_value') }} <span class="num">{{ \App\Support\Money::format($stock['asking']) }}</span>
                    @if ($stock['cost'] !== null) · {{ __('vehicles.total_cost') }} <span class="num">{{ \App\Support\Money::format($stock['cost']) }}</span>@endif
                </p>
            </x-ui.card>
        @endif
        @if ($installments)
            <x-ui.card>
                <p class="text-xs text-gray-500">{{ __('dashboard.installments') }}</p>
                <p class="mt-1 text-sm">{{ __('dashboard.due_week') }}: <strong class="num">{{ $installments['week_count'] }}</strong> — <span class="num">{{ \App\Support\Money::format($installments['week_amount']) }}</span></p>
                <p class="mt-1 text-sm text-red-700">{{ __('dashboard.overdue') }}: <strong class="num">{{ $installments['overdue_count'] }}</strong> — <span class="num">{{ \App\Support\Money::format($installments['overdue_amount']) }}</span></p>
                <a href="{{ route('installments.index') }}" wire:navigate class="mt-2 inline-block text-xs text-brand-700 hover:underline">{{ __('app.view') }}</a>
            </x-ui.card>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
        @if ($cashboxes)
            <x-ui.card :title="__('dashboard.cashboxes')" :padding="false">
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($cashboxes as $cb)
                        <li class="flex justify-between px-4 py-2.5">
                            <span>{{ $cb['name'] }}</span>
                            <span class="num font-semibold">{{ \App\Support\Money::format($cb['balance']) }} {{ $cb['currency'] }}</span>
                        </li>
                    @empty
                        <li class="px-4 py-4 text-gray-500">{{ __('app.no_records') }}</li>
                    @endforelse
                </ul>
            </x-ui.card>
        @endif

        @if ($stale)
            <x-ui.card :title="__('dashboard.stale', ['days' => $staleDays])" :padding="false">
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($stale as $vehicle)
                        <li class="flex justify-between px-4 py-2.5">
                            <a href="{{ route('vehicles.show', $vehicle) }}" wire:navigate class="text-brand-700 hover:underline">{{ $vehicle->title() }}</a>
                            <span class="num text-red-700">{{ __('vehicles.days', ['count' => $vehicle->daysInStock()]) }}</span>
                        </li>
                    @empty
                        <li class="px-4 py-4 text-gray-500">{{ __('dashboard.no_stale') }}</li>
                    @endforelse
                </ul>
            </x-ui.card>
        @endif

        @if ($reservations)
            <x-ui.card :title="__('dashboard.expiring_reservations')" :padding="false">
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($reservations as $r)
                        <li class="px-4 py-2.5">
                            <div class="flex justify-between">
                                <span>{{ $r->party->name }}</span>
                                <span class="num text-red-700">{{ $r->expires_at->format('Y-m-d') }}</span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $r->vehicle->title() }}</p>
                        </li>
                    @empty
                        <li class="px-4 py-4 text-gray-500">{{ __('app.no_records') }}</li>
                    @endforelse
                </ul>
            </x-ui.card>
        @endif
    </div>

    @if (! $today && ! $stock && ! $cashboxes && ! $installments)
        <x-ui.card>
            <p class="text-gray-700">{{ __('app.dashboard.welcome', ['name' => auth()->user()->name]) }}</p>
        </x-ui.card>
    @endif
</div>
