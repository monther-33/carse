<div class="space-y-4">
    @php($brand = app(\App\Support\Branding::class))
    <div class="relative overflow-hidden rounded-2xl shadow-sm">
        @if ($cover = $brand->coverUrl())
            <img src="{{ $cover }}" alt="{{ $brand->name() }}" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-l from-brand-900/90 via-brand-900/60 to-brand-900/20 rtl:bg-gradient-to-r"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-700 via-brand-900 to-gray-900"></div>
            <x-ui.icon name="car" class="absolute -bottom-10 end-4 h-48 w-48 text-white/5" />
        @endif
        <div class="relative flex items-center gap-4 p-5 text-white sm:p-6">
            <x-brand-logo class="hidden h-14 w-14 shrink-0 rounded-xl bg-white/95 p-1 text-2xl sm:inline-flex" />
            <div class="min-w-0">
                <p class="text-xl font-bold sm:text-2xl">{{ __('dashboard.greeting', ['name' => auth()->user()->name]) }}</p>
                <p class="mt-1 truncate text-sm text-white/80">{{ $brand->name() }} · {{ now()->translatedFormat('l j F Y') }}</p>
            </div>
        </div>
    </div>
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
