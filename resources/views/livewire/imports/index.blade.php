<div class="space-y-4">
    <x-ui.card :title="__('imports.title')">
        <div class="space-y-4">
            <div class="flex flex-wrap gap-2">
                @foreach ($kinds as $k)
                    <button type="button" wire:click="$set('kind', '{{ $k }}')"
                            @class([
                                'rounded-md px-4 py-2 text-sm font-medium transition',
                                'bg-brand-700 text-white' => $kind === $k,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200' => $kind !== $k,
                            ])>{{ __('imports.kinds.'.$k) }}</button>
                @endforeach
            </div>

            <div class="rounded-md bg-blue-50 p-3 text-sm text-blue-900 space-y-1">
                <p>{{ __('imports.help.'.$kind) }}</p>
                <p class="text-xs">
                    {{ __('imports.columns_hint') }}
                    @foreach ($importer->columns() as $column => $required)
                        <span @class(['font-semibold' => $required])>{{ __('imports.columns.'.$column) }}{{ $required ? ' *' : '' }}</span>@if (! $loop->last)، @endif
                    @endforeach
                </p>
            </div>

            <form wire:submit="check" class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-end">
                <x-ui.field :label="__('imports.file')" error="file" class="sm:col-span-2" required>
                    <input type="file" wire:model="file" accept=".xlsx,.xls,.csv" class="form-input">
                </x-ui.field>
                @if ($importer->isFinancial())
                    <x-ui.field :label="__('imports.date')" error="date" :hint="__('imports.date_hint')" required>
                        <input type="date" wire:model.live="date" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('app.fields.description')" error="description">
                        <input type="text" wire:model="description" class="form-input">
                    </x-ui.field>
                @endif
                <div class="flex flex-wrap gap-2 sm:col-span-4">
                    <x-ui.button variant="secondary" icon="document" wire:click="template" type="button">{{ __('imports.download_template') }}</x-ui.button>
                    <x-ui.button type="submit" icon="search" wire:loading.attr="disabled" wire:target="check,file">{{ __('imports.check') }}</x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>

    @if ($preview !== null)
        <x-ui.card :title="__('imports.preview')" :padding="false">
            <div class="space-y-3 p-4">
                <ul class="list-disc ps-5 text-sm text-gray-700">
                    @foreach ($preview['summary'] as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>

                @if ($preview['errors'] !== [])
                    <div class="rounded-md bg-red-50 p-3 text-sm text-red-800">
                        <p class="font-semibold">{{ __('imports.errors_title', ['count' => count($preview['errors'])]) }}</p>
                        <ul class="mt-2 max-h-64 space-y-1 overflow-y-auto">
                            @foreach ($preview['errors'] as $error)
                                <li>{{ __('imports.row', ['row' => $error['row']]) }}: {{ $error['message'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <x-ui.badge color="green">{{ __('imports.ready') }}</x-ui.badge>
                        <x-ui.button icon="check" wire:click="import" wire:confirm="{{ __('imports.confirm') }}" wire:loading.attr="disabled">{{ __('imports.run') }}</x-ui.button>
                    </div>
                @endif
                @error('file')<p class="rounded-md bg-red-50 p-2 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>

            @if ($preview['rows'] !== [])
                <div class="overflow-x-auto">
                    <table class="table-base">
                        <thead><tr>
                            <th>{{ __('imports.row_number') }}</th>
                            @foreach ($importer->previewColumns() as $column)
                                <th>{{ __('imports.columns.'.$column) }}</th>
                            @endforeach
                        </tr></thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach (array_slice($preview['rows'], 0, 200) as $row)
                            <tr>
                                <td class="num text-xs text-gray-500">{{ $row['row'] }}</td>
                                @foreach ($importer->previewColumns() as $column)
                                    <td @class(['num' => in_array($column, ['cost', 'asking_price', 'debit', 'credit', 'rate', 'base', 'credit_limit'], true)])>{{ $row[$column] ?? '' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if (count($preview['rows']) > 200)
                    <p class="p-4 text-xs text-gray-500">{{ __('imports.rows_shown', ['shown' => 200, 'total' => count($preview['rows'])]) }}</p>
                @endif
            @endif
        </x-ui.card>
    @endif

    @if ($stocks !== null)
        <x-ui.card :title="__('imports.opening_stocks')" :padding="false">
            @error('stock')<p class="m-4 rounded-md bg-red-50 p-2 text-sm text-red-700">{{ $message }}</p>@enderror
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead><tr>
                        <th>{{ __('documents.number') }}</th>
                        <th>{{ __('app.fields.date') }}</th>
                        <th>{{ __('app.fields.description') }}</th>
                        <th>{{ __('imports.vehicles_count') }}</th>
                        <th>{{ __('imports.columns.cost') }}</th>
                        <th>{{ __('documents.created_by') }}</th>
                        <th>{{ __('app.fields.status') }}</th>
                        <th></th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse ($stocks as $stock)
                        <tr wire:key="os-{{ $stock->id }}">
                            <td class="num font-mono text-xs">{{ $stock->displayNumber() }}</td>
                            <td class="num">{{ $stock->date->format('Y-m-d') }}</td>
                            <td>
                                {{ $stock->description }}
                                <details class="text-xs text-gray-500">
                                    <summary class="cursor-pointer">{{ __('imports.show_vehicles') }}</summary>
                                    <ul class="mt-1 space-y-0.5">
                                        @foreach ($stock->items as $item)
                                            <li class="font-mono" dir="ltr">{{ $item->vehicle->vin }} — {{ \App\Support\Money::format($item->cost) }}</li>
                                        @endforeach
                                    </ul>
                                </details>
                            </td>
                            <td class="num">{{ $stock->items->count() }}</td>
                            <td class="num">{{ \App\Support\Money::format(\App\Support\Money::sum($stock->items->pluck('cost')->all())) }}</td>
                            <td class="text-xs">{{ $stock->creator?->name }}</td>
                            <td><x-ui.badge :color="$stock->status->color()">{{ $stock->status->label() }}</x-ui.badge></td>
                            <td class="text-end whitespace-nowrap">
                                @can('delete', $stock)
                                    <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $stock->id }})" wire:confirm="{{ __('documents.confirm_delete_draft') }}" />
                                @endcan
                                @can('approve', $stock)
                                    <x-ui.button size="sm" icon="check" wire:click="approve({{ $stock->id }})" wire:confirm="{{ __('documents.confirm_approve') }}">{{ __('documents.approve') }}</x-ui.button>
                                @endcan
                                @can('cancel', $stock)
                                    <x-ui.button variant="ghost" size="sm" icon="x" wire:click="openCancel({{ $stock->id }})">{{ __('documents.cancel_document') }}</x-ui.button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $stocks->links() }}</div>
            <p class="px-4 pb-4 text-xs text-gray-500">{{ __('imports.balances_hint') }} <a href="{{ route('journals.index') }}" wire:navigate class="text-brand-700 underline">{{ __('app.nav.journals') }}</a></p>
        </x-ui.card>
    @endif

    <x-ui.modal wire:model="showCancel" :title="__('documents.cancel_document')" max-width="md">
        <x-ui.field :label="__('documents.cancel_reason')" error="reason" required>
            <input type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="cancel">{{ __('documents.confirm_cancel') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
