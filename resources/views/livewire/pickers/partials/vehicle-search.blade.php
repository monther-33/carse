{{-- Vehicle search modal (App\Livewire\Concerns\SearchesVehicles). Expects $vs (null when closed) and $chooses (bool). --}}
<x-ui.modal wire:model="showSearch" :title="__('vehicle_search.title')" max-width="4xl">
    @if ($vs)
        @php($p = $vs['preview'])
        @if ($p)
            {{-- Details --}}
            <div class="space-y-4">
                <button type="button" wire:click="closePreview" class="inline-flex items-center gap-1 text-sm text-brand-700 hover:underline">
                    <x-ui.icon name="chevron" class="h-4 w-4 ltr:rotate-180" /> {{ __('vehicle_search.back') }}
                </button>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="space-y-2">
                        @php($photos = $p->getMedia('photos'))
                        @if ($photos->isNotEmpty())
                            <img src="{{ $photos->first()->getUrl('thumb') }}" alt="{{ $p->title() }}" class="w-full rounded-md border border-gray-200 object-cover">
                            @if ($photos->count() > 1)
                                <div class="grid grid-cols-4 gap-1">
                                    @foreach ($photos->skip(1)->take(4) as $photo)
                                        <img src="{{ $photo->getUrl('thumb') }}" alt="" class="h-12 w-full rounded object-cover">
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="flex h-32 items-center justify-center rounded-md border border-dashed border-gray-300 text-gray-400">
                                <x-ui.icon name="car" class="h-10 w-10" />
                            </div>
                        @endif
                    </div>

                    <div class="sm:col-span-2 space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="text-lg font-semibold">{{ $p->title() }}</h4>
                            <x-ui.badge :color="$p->status->color()">{{ $p->status->label() }}</x-ui.badge>
                        </div>
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <dt class="text-gray-500">{{ __('vehicles.vin') }}</dt><dd class="num font-mono">{{ $p->vin }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.plate') }}</dt><dd class="num">{{ $p->plate_no ?? '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.color') }}</dt><dd>{{ $p->color?->name ?? '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.mileage') }}</dt><dd class="num">{{ $p->mileage !== null ? number_format($p->mileage) : '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.condition') }}</dt><dd>{{ $p->condition?->label() }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.fuel') }} / {{ __('vehicles.transmission') }}</dt><dd>{{ $p->fuel?->label() ?? '—' }} / {{ $p->transmission?->label() ?? '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.origin') }}</dt><dd>{{ $p->origin ?? '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.location') }}</dt><dd>{{ $p->location?->name ?? '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.received_at') }}</dt>
                            <dd><span class="num">{{ $p->received_at?->format('Y-m-d') ?? '—' }}</span>
                                @if ($p->status->isInStock() && $p->daysInStock() !== null)<span class="text-xs text-gray-500"> ({{ __('vehicle_search.days', ['days' => $p->daysInStock()]) }})</span>@endif
                            </dd>
                            <dt class="text-gray-500">{{ __('vehicles.asking_price') }}</dt><dd class="num font-semibold">{{ $p->asking_price !== null ? \App\Support\Money::format($p->asking_price) : '—' }}</dd>
                            <dt class="text-gray-500">{{ __('vehicles.min_price') }}</dt><dd class="num">{{ $p->min_price !== null ? \App\Support\Money::format($p->min_price) : '—' }}</dd>
                            @if ($vs['canSeeCost'])
                                <dt class="text-gray-500">{{ __('vehicles.total_cost') }}</dt><dd class="num">{{ \App\Support\Money::format($p->total_cost) }}</dd>
                            @endif
                        </dl>
                        @if ($p->notes)
                            <p class="rounded-md bg-gray-50 p-2 text-sm text-gray-600">{{ $p->notes }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 pt-3">
                    @can('vehicles.view')
                        <x-ui.button variant="secondary" icon="car" :href="route('vehicles.show', $p)" target="_blank">{{ __('vehicle_search.open_card') }}</x-ui.button>
                    @endcan
                    @if ($chooses)
                        <x-ui.button icon="check" wire:click="choose({{ $p->id }})">{{ __('vehicle_search.choose') }}</x-ui.button>
                    @endif
                </div>
            </div>
        @else
            {{-- Filters + results --}}
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <input type="search" wire:model.live.debounce.300ms="filter.q" placeholder="{{ __('vehicles.search_placeholder') }}"
                           class="form-input col-span-2" autocomplete="off" x-init="$nextTick(() => $el.focus())">
                    <select wire:model.live="filter.brand_id" class="form-input">
                        <option value="">{{ __('vehicles.brand') }}: {{ __('app.all') }}</option>
                        @foreach ($vs['brands'] as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filter.model_id" class="form-input" @disabled($vs['models']->isEmpty())>
                        <option value="">{{ __('vehicles.model') }}: {{ __('app.all') }}</option>
                        @foreach ($vs['models'] as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filter.color_id" class="form-input">
                        <option value="">{{ __('vehicles.color') }}: {{ __('app.all') }}</option>
                        @foreach ($vs['colors'] as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @if (count($vs['statuses']) > 1)
                        <select wire:model.live="filter.status" class="form-input">
                            <option value="">{{ __('app.fields.status') }}: {{ __('app.all') }}</option>
                            @foreach ($vs['statuses'] as $s)
                                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    @endif
                    <div class="col-span-2 flex gap-2 sm:col-span-1">
                        <input type="number" dir="ltr" wire:model.live.debounce.500ms="filter.year_from" placeholder="{{ __('vehicle_search.year_from') }}" class="form-input min-w-0">
                        <input type="number" dir="ltr" wire:model.live.debounce.500ms="filter.year_to" placeholder="{{ __('vehicle_search.year_to') }}" class="form-input min-w-0">
                    </div>
                    <input type="text" inputmode="decimal" dir="ltr" wire:model.live.debounce.500ms="filter.price_max" placeholder="{{ __('vehicle_search.price_max') }}" class="form-input">
                    <button type="button" wire:click="resetFilters" class="text-sm text-gray-500 hover:text-brand-700">{{ __('vehicle_search.reset') }}</button>
                </div>

                <div class="max-h-[55vh] overflow-auto rounded-md border border-gray-200">
                    <table class="table-base">
                        <thead class="sticky top-0"><tr>
                            <th>{{ __('vehicles.vehicle') }}</th>
                            <th class="hidden sm:table-cell">{{ __('vehicles.vin') }}</th>
                            <th class="hidden sm:table-cell">{{ __('vehicles.color') }}</th>
                            <th class="hidden sm:table-cell">{{ __('vehicles.mileage') }}</th>
                            <th>{{ __('vehicles.asking_price') }}</th>
                            @if ($vs['canSeeCost'])<th class="hidden md:table-cell">{{ __('vehicles.total_cost') }}</th>@endif
                            <th class="hidden sm:table-cell">{{ __('app.fields.status') }}</th>
                            <th></th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($vs['results'] as $v)
                            <tr wire:key="vs-{{ $v->id }}">
                                <td class="font-medium"><button type="button" wire:click="preview({{ $v->id }})" class="text-start hover:text-brand-700 hover:underline">{{ $v->title() }}</button><span class="num block font-mono text-xs font-normal text-gray-500 sm:hidden">{{ $v->vin }}</span></td>
                                <td class="num hidden font-mono text-xs sm:table-cell">{{ $v->vin }}@if ($v->plate_no)<br><span class="text-gray-500">{{ $v->plate_no }}</span>@endif</td>
                                <td class="hidden sm:table-cell">{{ $v->color?->name }}</td>
                                <td class="num hidden sm:table-cell">{{ $v->mileage !== null ? number_format($v->mileage) : '' }}</td>
                                <td class="num">{{ $v->asking_price !== null ? \App\Support\Money::format($v->asking_price) : '' }}</td>
                                @if ($vs['canSeeCost'])<td class="num hidden md:table-cell">{{ \App\Support\Money::format($v->total_cost) }}</td>@endif
                                <td class="hidden sm:table-cell"><x-ui.badge :color="$v->status->color()">{{ $v->status->label() }}</x-ui.badge></td>
                                <td class="whitespace-nowrap text-end">
                                    <x-ui.button variant="ghost" size="sm" icon="eye" wire:click="preview({{ $v->id }})" :title="__('vehicle_search.details')" class="hidden sm:inline-flex" />
                                    @if ($chooses)
                                        <x-ui.button size="sm" wire:click="choose({{ $v->id }})">{{ __('vehicle_search.choose') }}</x-ui.button>
                                    @else
                                        <x-ui.button size="sm" variant="secondary" :href="route('vehicles.show', $v)">{{ __('vehicle_search.open_card') }}</x-ui.button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($vs['hasMore'])
                    <div class="text-center">
                        <x-ui.button variant="secondary" size="sm" wire:click="loadMore">{{ __('vehicle_search.more') }}</x-ui.button>
                    </div>
                @endif
            </div>
        @endif
    @endif
</x-ui.modal>
