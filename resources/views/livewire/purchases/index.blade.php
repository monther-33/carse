<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\PurchaseInvoice::class)
                <x-ui.button icon="plus" :href="route('purchases.create')" wire:navigate>{{ __('purchases.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="flex flex-wrap gap-3 border-b border-gray-200 p-4">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('purchases.search_placeholder') }}" class="form-input sm:max-w-xs">
            <select wire:model.live="status" class="form-input sm:w-40">
                <option value="">{{ __('app.all') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('documents.number') }}</th>
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('purchases.supplier') }}</th>
                    <th>{{ __('purchases.vehicles_count') }}</th>
                    <th>{{ __('documents.total') }}</th>
                    <th>{{ __('documents.paid') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($invoices as $invoice)
                    <tr wire:key="pi-{{ $invoice->id }}">
                        <td class="num font-mono text-xs">{{ $invoice->displayNumber() }}</td>
                        <td class="num">{{ $invoice->date->format('Y-m-d') }}</td>
                        <td>{{ $invoice->party->name }}</td>
                        <td class="num">{{ $invoice->items_count }}</td>
                        <td class="num">{{ \App\Support\Money::format($invoice->total) }} {{ $invoice->currency->code }}</td>
                        <td class="num">{{ \App\Support\Money::format($invoice->paid) }}</td>
                        <td><x-ui.badge :color="$invoice->status->color()">{{ $invoice->status->label() }}</x-ui.badge></td>
                        <td class="text-end">
                            <x-ui.button variant="ghost" size="sm" icon="eye" :href="route('purchases.show', $invoice)" wire:navigate>{{ __('app.view') }}</x-ui.button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $invoices->links() }}</div>
    </x-ui.card>
</div>
