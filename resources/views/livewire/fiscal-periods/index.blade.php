<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\FiscalPeriod::class)
                <x-ui.button icon="plus" wire:click="generate" wire:confirm="{{ __('app.periods.confirm_generate', ['year' => $year]) }}">
                    {{ __('app.periods.generate', ['year' => $year]) }}
                </x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="flex items-center gap-3 border-b border-gray-200 p-4">
            <label for="year" class="text-sm text-gray-600">{{ __('app.periods.year') }}</label>
            <input id="year" type="number" dir="ltr" wire:model.live.debounce.500ms="year" class="form-input w-28">
            @error('year')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.name') }}</th>
                    <th>{{ __('app.periods.start') }}</th>
                    <th>{{ __('app.periods.end') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th>{{ __('app.periods.closed_by') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($periods as $period)
                    <tr wire:key="p-{{ $period->id }}">
                        <td class="num font-medium">{{ $period->name }}</td>
                        <td class="num">{{ $period->start_date->format('Y-m-d') }}</td>
                        <td class="num">{{ $period->end_date->format('Y-m-d') }}</td>
                        <td>
                            <x-ui.badge :color="$period->is_closed ? 'red' : 'green'">
                                {{ $period->is_closed ? __('app.periods.closed') : __('app.periods.open') }}
                            </x-ui.badge>
                        </td>
                        <td class="text-xs text-gray-600">
                            @if ($period->is_closed)
                                {{ $period->closer?->name }} — <span class="num">{{ $period->closed_at?->format('Y-m-d H:i') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @can('close', $period)
                                <x-ui.button variant="danger" size="sm" icon="lock" wire:click="close({{ $period->id }})"
                                             wire:confirm="{{ __('app.periods.confirm_close', ['name' => $period->name]) }}">
                                    {{ __('app.periods.close') }}
                                </x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-gray-500 py-8">{{ __('app.periods.none') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</div>
