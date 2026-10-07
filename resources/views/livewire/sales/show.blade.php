<div class="space-y-4">
    @error('document')<div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror
    @include('livewire.sales.partials.credit-warning', ['warning' => $creditWarning])
    @include('livewire.sales.partials.net-price-warning', ['warnings' => $netPriceWarnings])

    <x-ui.card>
        <div class="flex flex-wrap items-start justify-between gap-4">
            <dl class="grid flex-1 grid-cols-2 gap-x-6 gap-y-3 text-sm md:grid-cols-4">
                <div><dt class="text-xs text-gray-500">{{ __('sales.customer') }}</dt><dd class="font-medium">{{ $invoice->party->name }} <span class="num text-gray-500">{{ $invoice->party->phone }}</span></dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('app.fields.date') }}</dt><dd class="num">{{ $invoice->date->format('Y-m-d') }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('sales.payment_type') }}</dt><dd>{{ $invoice->payment_type->label() }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('app.fields.status') }}</dt><dd>
                    <x-ui.badge :color="$invoice->status->color()">{{ $invoice->status->label() }}</x-ui.badge>
                    @if ($invoice->delivered_at)<x-ui.badge color="blue">{{ __('sales.delivered') }}</x-ui.badge>@endif
                </dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('sales.salesperson') }}</dt><dd>{{ $invoice->salesperson->name }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('app.fields.currency') }}</dt><dd>{{ $invoice->currency->code }} <span class="num text-gray-500">× {{ rtrim(rtrim($invoice->rate, '0'), '.') }}</span></dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('documents.entry') }}</dt><dd class="num font-mono text-xs">{{ $invoice->journalEntry?->number ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-500">{{ __('documents.approved_by') }}</dt><dd>{{ $invoice->approver?->name ?? '—' }}</dd></div>
            </dl>
            <div class="flex flex-wrap gap-2">
                @can('update', $invoice)
                    <x-ui.button variant="secondary" icon="pencil" :href="route('sales.edit', $invoice)" wire:navigate>{{ __('app.edit') }}</x-ui.button>
                @endcan
                @can('delete', $invoice)
                    <x-ui.button variant="secondary" icon="trash" wire:click="delete" wire:confirm="{{ __('documents.confirm_delete_draft') }}">{{ __('app.delete') }}</x-ui.button>
                @endcan
                @can('approve', $invoice)
                    <x-ui.button icon="check" wire:click="approve" wire:confirm="{{ __('documents.confirm_approve') }}">{{ __('documents.approve') }}</x-ui.button>
                @endcan
                @can('deliver', $invoice)
                    <x-ui.button variant="secondary" icon="car" wire:click="deliver" wire:confirm="{{ __('sales.confirm_deliver') }}">{{ __('sales.deliver') }}</x-ui.button>
                @endcan
                @can('cancel', $invoice)
                    <x-ui.button variant="danger" icon="x" wire:click="openCancel">{{ __('documents.cancel_document') }}</x-ui.button>
                @endcan
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2 border-t border-gray-100 pt-4">
            @if ($invoice->isDraft())
                <x-ui.button size="sm" variant="secondary" icon="printer" :href="route('print.sales', [$invoice, 'quotation'])" target="_blank">{{ __('print.quotation') }}</x-ui.button>
            @elseif ($invoice->isPosted())
                <x-ui.button size="sm" variant="secondary" icon="printer" :href="route('print.sales', [$invoice, 'invoice'])" target="_blank">{{ __('print.invoice') }}</x-ui.button>
                <x-ui.button size="sm" variant="secondary" icon="printer" :href="route('print.sales', [$invoice, 'contract'])" target="_blank">{{ __('print.contract') }}</x-ui.button>
                @if ($invoice->delivered_at)
                    <x-ui.button size="sm" variant="secondary" icon="printer" :href="route('print.sales', [$invoice, 'delivery'])" target="_blank">{{ __('print.delivery') }}</x-ui.button>
                @endif
                @if ($invoice->installmentPlan)
                    <x-ui.button size="sm" variant="secondary" icon="printer" :href="route('print.sales', [$invoice, 'schedule'])" target="_blank">{{ __('print.schedule') }}</x-ui.button>
                @endif
            @endif
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
                    <th>{{ __('sales.price') }}</th>
                    <th>{{ __('documents.discount') }}</th>
                    <th>{{ __('purchases.net') }}</th>
                    @if ($canViewCost)
                        <th>{{ __('sales.cost') }}</th>
                        <th>{{ __('sales.commission') }}</th>
                        <th>{{ __('sales.profit') }}</th>
                    @endif
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach ($invoice->items as $item)
                    <tr wire:key="sit-{{ $item->id }}">
                        <td>
                            <a href="{{ route('vehicles.show', $item->vehicle) }}" wire:navigate class="text-brand-700 hover:underline">{{ $item->vehicle->title() }}</a>
                            <span class="block num font-mono text-xs text-gray-500">{{ $item->vehicle->vin }}</span>
                        </td>
                        <td class="num">{{ \App\Support\Money::format($item->price) }}</td>
                        <td class="num">{{ \App\Support\Money::format($item->discount) }}</td>
                        <td class="num">{{ \App\Support\Money::format($item->net) }}</td>
                        @if ($canViewCost)
                            <td class="num">{{ $item->cost_snapshot !== null ? \App\Support\Money::format($item->cost_snapshot) : '—' }}</td>
                            <td class="num">{{ \App\Support\Money::format($item->commission) }}</td>
                            <td class="num font-semibold">{{ $item->profit() !== null ? \App\Support\Money::format($item->profit()) : '—' }}</td>
                        @endif
                        <td class="text-end">
                            @if ($item->returnDocument)
                                <x-ui.badge color="red">{{ __('purchases.returned', ['number' => $item->returnDocument->number]) }}</x-ui.badge>
                            @elseif (auth()->user()->can('returnItem', $invoice) && $item->vehicle->status === \App\Enums\VehicleStatus::Sold)
                                <x-ui.button variant="ghost" size="sm" icon="return" wire:click="openReturn({{ $item->id }})">{{ __('purchases.return') }}</x-ui.button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <dl class="grid grid-cols-2 gap-3 border-t border-gray-200 p-4 text-sm md:grid-cols-4">
            <div><dt class="text-xs text-gray-500">{{ __('documents.total') }}</dt><dd class="num font-semibold">{{ \App\Support\Money::format($invoice->total) }} {{ $invoice->currency->code }}</dd></div>
            <div><dt class="text-xs text-gray-500">{{ __('sales.trade_in') }}</dt><dd class="num">{{ \App\Support\Money::format($invoice->trade_in_value) }}</dd></div>
            <div><dt class="text-xs text-gray-500">{{ __('sales.deposit') }}</dt><dd class="num">{{ \App\Support\Money::format($invoice->deposit_applied) }}</dd></div>
            <div><dt class="text-xs text-gray-500">{{ __('sales.paid_at_sale') }}</dt><dd class="num">{{ \App\Support\Money::format($invoice->paid) }}</dd></div>
        </dl>
    </x-ui.card>

    @if ($invoice->tradeIn)
        <x-ui.card :title="__('sales.trade_in')">
            <p class="text-sm">
                <a href="{{ route('vehicles.show', $invoice->tradeIn->vehicle) }}" wire:navigate class="text-brand-700 hover:underline">{{ $invoice->tradeIn->vehicle->title() }}</a>
                <span class="num font-mono text-xs text-gray-500">{{ $invoice->tradeIn->vehicle->vin }}</span>
                — {{ __('sales.trade_in_value') }}: <strong class="num">{{ \App\Support\Money::format($invoice->tradeIn->value) }}</strong>
            </p>
        </x-ui.card>
    @endif

    @if ($invoice->installmentPlan)
        @php($plan = $invoice->installmentPlan)
        <x-ui.card :title="__('sales.schedule')" :padding="false">
            <div class="flex flex-wrap gap-6 border-b border-gray-200 p-4 text-sm">
                <span>{{ __('sales.down_payment') }}: <strong class="num">{{ \App\Support\Money::format($plan->down_payment) }}</strong></span>
                <span>{{ __('sales.financed') }}: <strong class="num">{{ \App\Support\Money::format($plan->financed_amount) }}</strong></span>
                <span>{{ __('sales.months') }}: <strong class="num">{{ $plan->months }}</strong></span>
                <span>{{ __('sales.guarantor') }}: <strong>{{ $plan->guarantor?->name }}</strong> <span class="num">{{ $plan->guarantor?->phone }}</span></span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead><tr>
                        <th>#</th><th>{{ __('sales.due_date') }}</th><th>{{ __('documents.amount') }}</th><th>{{ __('documents.paid') }}</th><th>{{ __('app.fields.status') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach ($plan->installments as $inst)
                        <tr @class(['bg-red-50' => $inst->isOverdue()])>
                            <td class="num">{{ $inst->sequence }}</td>
                            <td class="num">{{ $inst->due_date->format('Y-m-d') }}</td>
                            <td class="num">{{ \App\Support\Money::format($inst->amount) }}</td>
                            <td class="num">{{ \App\Support\Money::format($inst->paid_amount) }}</td>
                            <td><x-ui.badge :color="$inst->isOverdue() ? 'red' : ($inst->status === \App\Enums\InstallmentStatus::Paid ? 'green' : 'gray')">{{ $inst->isOverdue() ? __('installments.overdue') : $inst->status->label() }}</x-ui.badge></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    @endif

    @if ($vouchers->isNotEmpty())
        <x-ui.card :title="__('sales.receipts')" :padding="false">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <tbody class="divide-y divide-gray-100">
                    @foreach ($vouchers as $voucher)
                        <tr>
                            <td class="num font-mono text-xs">{{ $voucher->displayNumber() }}</td>
                            <td class="num">{{ $voucher->date->format('Y-m-d') }}</td>
                            <td>{{ $voucher->cashbox->name }}</td>
                            <td>{{ $voucher->description }}</td>
                            <td class="num">{{ \App\Support\Money::format($voucher->amount) }}</td>
                            <td><x-ui.badge :color="$voucher->status->color()">{{ $voucher->status->label() }}</x-ui.badge></td>
                            <td class="text-end">
                                @if ($voucher->isPosted())
                                    <x-ui.button variant="ghost" size="sm" icon="printer" :href="route('print.voucher', $voucher)" target="_blank" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    @endif

    <x-ui.modal wire:model="showCancel" :title="__('documents.cancel_document')" max-width="md">
        <p class="mb-3 text-sm text-gray-600">{{ __('sales.cancel_hint') }}</p>
        <x-ui.field :label="__('documents.cancel_reason')" for="reason" error="reason" required>
            <input id="reason" type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="cancel">{{ __('documents.confirm_cancel') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal wire:model="showReturn" :title="__('purchases.return')" max-width="md">
        <p class="mb-3 text-sm text-gray-600">{{ __('sales.return_hint') }}</p>
        <x-ui.field :label="__('documents.cancel_reason')" for="return-reason" error="reason" required>
            <input id="return-reason" type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="returnItem">{{ __('purchases.return') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
