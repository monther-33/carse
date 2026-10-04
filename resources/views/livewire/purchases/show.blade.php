<div class="space-y-4">
    @error('document')<div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

    <x-ui.card>
        <div class="flex flex-wrap items-start justify-between gap-4">
            <dl class="grid flex-1 grid-cols-2 gap-x-6 gap-y-3 text-sm md:grid-cols-4">
                <div><dt class="text-xs text-gray-500">{{ __('purchases.supplier') }}</dt><dd class="font-medium">{{ $invoice->party->name }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('app.fields.date') }}</dt><dd class="num">{{ $invoice->date->format('Y-m-d') }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('purchases.source') }}</dt><dd>{{ $invoice->source->label() }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('app.fields.status') }}</dt><dd><x-ui.badge :color="$invoice->status->color()">{{ $invoice->status->label() }}</x-ui.badge></dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('app.fields.currency') }}</dt><dd>{{ $invoice->currency->code }} <span class="num text-gray-500">× {{ rtrim(rtrim($invoice->rate, '0'), '.') }}</span></dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('documents.entry') }}</dt><dd class="num font-mono text-xs">{{ $invoice->journalEntry?->number ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('documents.created_by') }}</dt><dd>{{ $invoice->creator?->name }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('documents.approved_by') }}</dt><dd>{{ $invoice->approver?->name ?? '—' }}</dd></div>
            </dl>
            <div class="flex flex-wrap gap-2">
                @can('update', $invoice)
                    <x-ui.button variant="secondary" icon="pencil" :href="route('purchases.edit', $invoice)" wire:navigate>{{ __('app.edit') }}</x-ui.button>
                @endcan
                @can('delete', $invoice)
                    <x-ui.button variant="secondary" icon="trash" wire:click="delete" wire:confirm="{{ __('documents.confirm_delete_draft') }}">{{ __('app.delete') }}</x-ui.button>
                @endcan
                @can('approve', $invoice)
                    <x-ui.button icon="check" wire:click="approve" wire:confirm="{{ __('documents.confirm_approve') }}">{{ __('documents.approve') }}</x-ui.button>
                @endcan
                @can('cancel', $invoice)
                    <x-ui.button variant="danger" icon="x" wire:click="openCancel">{{ __('documents.cancel_document') }}</x-ui.button>
                @endcan
            </div>
        </div>
        @if ($invoice->isCancelled())
            <p class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                {{ __('documents.cancelled_info', ['user' => $invoice->canceller?->name, 'date' => $invoice->cancelled_at?->format('Y-m-d'), 'reason' => $invoice->cancel_reason]) }}
            </p>
        @endif
    </x-ui.card>

    <x-ui.card :title="__('purchases.vehicles')" :padding="false">
        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th>{{ __('vehicles.vin') }}</th>
                    <th>{{ __('purchases.entry_status') }}</th>
                    <th>{{ __('purchases.price') }}</th>
                    <th>{{ __('documents.discount') }}</th>
                    <th>{{ __('purchases.net') }}</th>
                    <th>{{ __('purchases.cost_base') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach ($invoice->items as $item)
                    <tr wire:key="it-{{ $item->id }}">
                        <td>
                            <a href="{{ route('vehicles.show', $item->vehicle) }}" wire:navigate class="text-brand-700 hover:underline">{{ $item->vehicle->title() }}</a>
                            <x-ui.badge :color="$item->vehicle->status->color()">{{ $item->vehicle->status->label() }}</x-ui.badge>
                        </td>
                        <td class="num font-mono text-xs">{{ $item->vehicle->vin }}</td>
                        <td>{{ $item->entry_status->label() }}</td>
                        <td class="num">{{ \App\Support\Money::format($item->price) }}</td>
                        <td class="num">{{ \App\Support\Money::format($item->discount) }}</td>
                        <td class="num">{{ \App\Support\Money::format($item->net) }}</td>
                        <td class="num">{{ \App\Support\Money::format($item->cost_base) }}</td>
                        <td class="text-end">
                            @if ($item->returnDocument)
                                <x-ui.badge color="red">{{ __('purchases.returned', ['number' => $item->returnDocument->number]) }}</x-ui.badge>
                            @elseif (auth()->user()->can('returnItem', $invoice) && $item->vehicle->status->isInStock())
                                <x-ui.button variant="ghost" size="sm" icon="return" wire:click="openReturn({{ $item->id }})">{{ __('purchases.return') }}</x-ui.button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap justify-end gap-8 border-t border-gray-200 p-4 text-sm">
            <span>{{ __('documents.subtotal') }}: <strong class="num">{{ \App\Support\Money::format($invoice->subtotal) }}</strong></span>
            <span>{{ __('documents.discount') }}: <strong class="num">{{ \App\Support\Money::format($invoice->discount) }}</strong></span>
            <span>{{ __('documents.total') }}: <strong class="num text-brand-700">{{ \App\Support\Money::format($invoice->total) }} {{ $invoice->currency->code }}</strong></span>
            <span>{{ __('documents.paid') }}: <strong class="num">{{ \App\Support\Money::format($invoice->paid) }}</strong></span>
        </div>
    </x-ui.card>

    @if ($vouchers->isNotEmpty())
        <x-ui.card :title="__('purchases.payments')" :padding="false">
            <table class="table-base">
                <tbody class="divide-y divide-gray-100">
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td class="num font-mono text-xs">{{ $voucher->displayNumber() }}</td>
                        <td class="num">{{ $voucher->date->format('Y-m-d') }}</td>
                        <td>{{ $voucher->cashbox->name }}</td>
                        <td class="num">{{ \App\Support\Money::format($voucher->amount) }}</td>
                        <td><x-ui.badge :color="$voucher->status->color()">{{ $voucher->status->label() }}</x-ui.badge></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-ui.card>
    @endif

    <x-ui.modal wire:model="showCancel" :title="__('documents.cancel_document')" max-width="md">
        <p class="mb-3 text-sm text-gray-600">{{ __('purchases.cancel_hint') }}</p>
        <x-ui.field :label="__('documents.cancel_reason')" for="reason" error="reason" required>
            <input id="reason" type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="cancel" wire:loading.attr="disabled">{{ __('documents.confirm_cancel') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal wire:model="showReturn" :title="__('purchases.return')" max-width="md">
        <p class="mb-3 text-sm text-gray-600">{{ __('purchases.return_hint') }}</p>
        <x-ui.field :label="__('documents.cancel_reason')" for="return-reason" error="reason" required>
            <input id="return-reason" type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="returnItem" wire:loading.attr="disabled">{{ __('purchases.return') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
