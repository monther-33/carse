<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\PurchaseInvoice::class)
                <x-ui.button icon="plus" :href="route('purchases.create')" wire:navigate>{{ __('purchases.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="flex flex-wrap gap-3 border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('vehicles.search_placeholder') }}" class="form-input sm:max-w-xs">
            <select wire:model.live="status" class="form-input sm:w-48">
                <option value="stock">{{ __('vehicles.in_stock') }}</option>
                <option value="">{{ __('app.all') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
            <select wire:model.live="brand" class="form-input sm:w-44">
                <option value="">{{ __('vehicles.all_brands') }}</option>
                @foreach ($brands as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th class="hidden sm:table-cell">{{ __('vehicles.vin') }}</th>
                    <th class="hidden md:table-cell">{{ __('vehicles.plate') }}</th>
                    <th class="hidden md:table-cell">{{ __('vehicles.color') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th class="hidden sm:table-cell">{{ __('vehicles.age') }}</th>
                    <th>{{ __('vehicles.asking_price') }}</th>
                    @if ($canViewCost)
                        <th class="hidden md:table-cell">{{ __('vehicles.total_cost') }}</th>
                    @endif
                    <th class="hidden sm:table-cell"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($vehicles as $vehicle)
                    @php($age = $vehicle->status->isInStock() ? $vehicle->daysInStock() : null)
                    <tr wire:key="v-{{ $vehicle->id }}">
                        <td class="font-medium"><a href="{{ route('vehicles.show', $vehicle) }}" wire:navigate class="hover:text-brand-700 hover:underline">{{ $vehicle->title() }}</a><span class="num block font-mono text-xs font-normal text-gray-500 sm:hidden">{{ $vehicle->vin }}</span></td>
                        <td class="num hidden font-mono text-xs sm:table-cell">{{ $vehicle->vin }}</td>
                        <td class="num hidden md:table-cell">{{ $vehicle->plate_no }}</td>
                        <td class="hidden md:table-cell">{{ $vehicle->color?->name }}</td>
                        <td><x-ui.badge :color="$vehicle->status->color()">{{ $vehicle->status->label() }}</x-ui.badge> <x-ownership-badge :vehicle="$vehicle" /></td>
                        <td class="hidden sm:table-cell">
                            @if ($age !== null)
                                <span @class(['num', 'font-semibold text-red-700' => $age > $staleCritical, 'font-semibold text-yellow-700' => $age > $staleWarning && $age <= $staleCritical])>
                                    {{ __('vehicles.days', ['count' => $age]) }}
                                </span>
                            @endif
                        </td>
                        <td class="num">{{ $vehicle->asking_price !== null ? \App\Support\Money::format($vehicle->asking_price) : '—' }}</td>
                        @if ($canViewCost)
                            <td class="num hidden md:table-cell">{{ \App\Support\Money::format($vehicle->total_cost) }}</td>
                        @endif
                        <td class="hidden text-end sm:table-cell">
                            <x-ui.button variant="ghost" size="sm" icon="eye" :href="route('vehicles.show', $vehicle)" wire:navigate>{{ __('app.view') }}</x-ui.button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $vehicles->links() }}</div>
    </x-ui.card>
</div>
