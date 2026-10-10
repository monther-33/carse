<div class="space-y-4">
    <x-ui.card>
        <div class="flex flex-wrap items-end gap-3">
            @foreach ($report->filters() as $filter)
                @php($label = __('reports.filters.'.$filter))
                @switch($filter)
                    @case('from') @case('to') @case('as_of')
                        <x-ui.field :label="$label">
                            <input type="date" wire:model.live="filters.{{ $filter }}" class="form-input">
                        </x-ui.field>
                        @break
                    @case('party_id')
                        <x-ui.field :label="$label" class="min-w-56">
                            <livewire:pickers.party-picker wire:model.live="filters.party_id" />
                        </x-ui.field>
                        @break
                    @default
                        @php($choices = $lists[$filter] ?? collect($report->options()[$filter] ?? []))
                        <x-ui.field :label="$label">
                            <select wire:model.live="filters.{{ $filter }}" class="form-input min-w-40">
                                @unless (in_array($filter, ['group_by', 'side'], true))
                                    <option value="">{{ __('app.all') }}</option>
                                @endunless
                                @foreach ($choices as $value => $text)
                                    <option value="{{ $value }}">{{ $text }}</option>
                                @endforeach
                            </select>
                        </x-ui.field>
                @endswitch
            @endforeach

            <div class="ms-auto flex gap-2">
                <x-ui.button size="sm" variant="secondary" icon="printer" :href="route('reports.pdf', ['key' => $report::key(), 's' => $exportState])" target="_blank">PDF</x-ui.button>
                <x-ui.button size="sm" variant="secondary" icon="document" :href="route('reports.excel', ['key' => $report::key(), 's' => $exportState])">Excel</x-ui.button>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card :padding="false">
        @if ($missing)
            <p class="p-6 text-center text-sm text-gray-500">{{ __('reports.choose_filters', ['filters' => collect($missing)->map(fn ($k) => __('reports.filters.'.$k))->join('، ')]) }}</p>
        @else
            <div class="overflow-x-auto" wire:loading.class="opacity-50">
                @include('reports.table', ['columns' => $columns, 'rows' => $rows, 'totals' => $totals])
            </div>
            @foreach ($notes as $note)
                <p class="border-t border-gray-100 px-4 py-2 text-sm text-gray-600">{{ $note }}</p>
            @endforeach
        @endif
    </x-ui.card>
</div>
