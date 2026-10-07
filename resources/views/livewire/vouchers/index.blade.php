<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Voucher::class)
                <x-ui.button size="sm" icon="plus" wire:click="create('receipt')">{{ __('vouchers.new_receipt') }}</x-ui.button>
                <x-ui.button size="sm" icon="plus" wire:click="create('payment')">{{ __('vouchers.new_payment') }}</x-ui.button>
                <x-ui.button size="sm" variant="secondary" icon="plus" wire:click="create('transfer')">{{ __('vouchers.new_transfer') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="flex flex-wrap gap-3 border-b border-gray-200 p-4">
            <select wire:model.live="type" class="form-input sm:w-40">
                <option value="">{{ __('app.all') }}</option>
                @foreach (['receipt', 'payment', 'transfer'] as $t)
                    <option value="{{ $t }}">{{ __('enums.voucher_type.'.$t) }}</option>
                @endforeach
            </select>
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
                    <th>{{ __('app.fields.type') }}</th>
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('vouchers.party_or_account') }}</th>
                    <th>{{ __('documents.cashbox') }}</th>
                    <th>{{ __('documents.amount') }}</th>
                    <th>{{ __('app.fields.description') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($vouchers as $voucher)
                    <tr wire:key="vo-{{ $voucher->id }}">
                        <td class="num font-mono text-xs">{{ $voucher->displayNumber() }}</td>
                        <td>{{ $voucher->type->label() }}</td>
                        <td class="num">{{ $voucher->date->format('Y-m-d') }}</td>
                        <td>
                            @if ($voucher->type === \App\Enums\VoucherType::Transfer)
                                ← {{ $voucher->toCashbox?->name }}
                            @else
                                {{ $voucher->party?->name ?? $voucher->account?->name }}
                            @endif
                        </td>
                        <td>{{ $voucher->cashbox->name }}</td>
                        <td class="num">{{ \App\Support\Money::format($voucher->amount) }} {{ $voucher->currency->code }}</td>
                        <td class="text-xs text-gray-600">{{ $voucher->description }}</td>
                        <td><x-ui.badge :color="$voucher->status->color()">{{ $voucher->status->label() }}</x-ui.badge></td>
                        <td class="text-end whitespace-nowrap">
                            @if ($voucher->isPosted() && Route::has('print.voucher'))
                                <x-ui.button variant="ghost" size="sm" icon="printer" :href="route('print.voucher', $voucher)" target="_blank" />
                            @endif
                            @can('update', $voucher)
                                <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $voucher->id }})" />
                            @endcan
                            @can('delete', $voucher)
                                <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $voucher->id }})" wire:confirm="{{ __('documents.confirm_delete_draft') }}" />
                            @endcan
                            @can('approve', $voucher)
                                <x-ui.button size="sm" icon="check" wire:click="approve({{ $voucher->id }})" wire:confirm="{{ __('documents.confirm_approve') }}">{{ __('documents.approve') }}</x-ui.button>
                            @endcan
                            @can('cancel', $voucher)
                                <x-ui.button variant="ghost" size="sm" icon="x" wire:click="openCancel({{ $voucher->id }})">{{ __('documents.cancel_document') }}</x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $vouchers->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="__('enums.voucher_type.'.($form['type'] ?? 'receipt'))">
        <form wire:submit="save" id="voucher-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @error('document')<p class="sm:col-span-2 text-sm text-red-600">{{ $message }}</p>@enderror
            @php($isTransfer = ($form['type'] ?? '') === 'transfer')
            @if (! $isTransfer)
                <x-ui.field :label="__('vouchers.purpose')" for="v-purpose" error="form.purpose" required>
                    <select id="v-purpose" wire:model.live="form.purpose" class="form-input">
                        @foreach (array_keys($purposes[$form['type'] ?? 'receipt'] ?? []) as $purpose)
                            <option value="{{ $purpose }}">{{ __('vouchers.purposes.'.$purpose) }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
            @endif
            <x-ui.field :label="__('app.fields.date')" for="v-date" error="form.date" required>
                <input id="v-date" type="date" wire:model="form.date" class="form-input">
            </x-ui.field>
            <x-ui.field :label="$isTransfer ? __('vouchers.from_cashbox') : __('documents.cashbox')" for="v-cb" error="form.cashbox_id" required>
                <select id="v-cb" wire:model.live="form.cashbox_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($cashboxes as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->name }} ({{ $cb->currency->code }})</option>
                    @endforeach
                </select>
            </x-ui.field>
            @if ($isTransfer)
                <x-ui.field :label="__('vouchers.to_cashbox')" for="v-tocb" error="form.to_cashbox_id" required>
                    <select id="v-tocb" wire:model="form.to_cashbox_id" class="form-input">
                        <option value="">—</option>
                        @foreach ($allCashboxes as $cb)
                            <option value="{{ $cb->id }}">{{ $cb->name }} ({{ $cb->currency->code }})</option>
                        @endforeach
                    </select>
                </x-ui.field>
            @else
                @if (($form['purpose'] ?? '') === 'other')
                    <x-ui.field :label="__('app.fields.account')" for="v-acc" error="form.account_id" required>
                        <select id="v-acc" wire:model="form.account_id" class="form-input">
                            <option value="">—</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->label() }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                @endif
                @if (($form['purpose'] ?? '') !== 'commissions')
                    <x-ui.field :label="__('vouchers.party')" error="form.party_id" class="sm:col-span-2">
                        <livewire:pickers.party-picker wire:model.live="form.party_id" :allow-create="true" :kind="in_array($form['purpose'] ?? '', ['supplier', 'supplier_refund']) ? 'supplier' : 'any'" :key="'pp-'.($form['purpose'] ?? '')" />
                    </x-ui.field>
                    @if ($ownerAvailable !== null)
                        <p class="sm:col-span-2 -mt-2 rounded-md bg-brand-50 px-3 py-2 text-sm text-brand-800">{{ __('ownership.available_to_pay') }}: <span class="num font-semibold">{{ $ownerAvailable }}</span></p>
                    @endif
                @endif
                @if ($referenceOptions->isNotEmpty())
                    <x-ui.field :label="__('vouchers.for_invoice')" for="v-ref" error="form.reference_id" class="sm:col-span-2" :hint="__('vouchers.for_invoice_hint')">
                        <select id="v-ref" wire:model="form.reference_id" class="form-input">
                            <option value="">—</option>
                            @foreach ($referenceOptions as $inv)
                                <option value="{{ $inv->id }}">{{ $inv->number }} — {{ $inv->date->format('Y-m-d') }} — {{ \App\Support\Money::format($inv->total) }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                @endif
            @endif
            <x-ui.field :label="__('documents.amount')" for="v-amount" error="form.amount" required>
                <input id="v-amount" type="text" dir="ltr" inputmode="decimal" wire:model="form.amount" class="form-input">
            </x-ui.field>
            @php($cb = $cashboxes->firstWhere('id', $form['cashbox_id'] ?? null))
            @if ($cb && ! $cb->currency->is_base)
                <x-ui.field :label="__('documents.rate')" for="v-rate" error="form.rate" :hint="__('documents.rate_hint')">
                    <input id="v-rate" type="text" dir="ltr" inputmode="decimal" wire:model="form.rate" class="form-input">
                </x-ui.field>
            @endif
            <x-ui.field :label="__('app.fields.description')" for="v-desc" error="form.description" class="sm:col-span-2" required>
                <input id="v-desc" type="text" wire:model="form.description" class="form-input">
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="voucher-form" wire:loading.attr="disabled">{{ __('documents.save_draft') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal wire:model="showCancel" :title="__('documents.cancel_document')" max-width="md">
        <x-ui.field :label="__('documents.cancel_reason')" for="reason" error="reason" required>
            <input id="reason" type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="cancel">{{ __('documents.confirm_cancel') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
