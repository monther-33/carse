<form wire:submit="save" class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="space-y-4 xl:col-span-2">
        @error('document')<div class="rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

        <x-ui.card :title="__('sales.customer_and_car')">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-ui.field :label="__('sales.customer')" error="party_id" required>
                    <livewire:pickers.party-picker wire:model.live="party_id" kind="customer" :allow-create="true" />
                </x-ui.field>
                <x-ui.field :label="__('app.fields.date')" for="date" error="date" required>
                    <input id="date" type="date" wire:model="date" class="form-input">
                </x-ui.field>
                @if ($reservations->isNotEmpty())
                    <x-ui.field :label="__('sales.reservation')" for="reservation_id" error="reservation_id" class="sm:col-span-2">
                        <select id="reservation_id" wire:model.live="reservation_id" class="form-input">
                            <option value="">—</option>
                            @foreach ($reservations as $r)
                                <option value="{{ $r->id }}">{{ $r->number }} — {{ $r->vehicle->vin }} — {{ \App\Support\Money::format($r->deposit) }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                @endif
                <x-ui.field :label="__('sales.add_vehicle')" class="sm:col-span-2" :hint="__('sales.add_vehicle_hint')">
                    <livewire:pickers.vehicle-picker wire:model.live="pickVehicleId" :statuses="['available', 'reserved']" />
                </x-ui.field>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="table-base">
                    <thead><tr>
                        <th>{{ __('vehicles.vehicle') }}</th>
                        <th>{{ __('vehicles.asking_price') }}</th>
                        <th>{{ __('vehicles.min_price') }}</th>
                        <th>{{ __('sales.price') }}</th>
                        <th></th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse ($items as $i => $item)
                        @php($v = $vehicles[$item['vehicle_id']] ?? null)
                        <tr wire:key="si-{{ $item['vehicle_id'] }}">
                            <td>
                                {{ $v?->title() }}
                                <span class="block num font-mono text-xs text-gray-500">{{ $v?->vin }}</span>
                                @if ($v) <x-ui.badge :color="$v->status->color()">{{ $v->status->label() }}</x-ui.badge> @endif
                            </td>
                            <td class="num">{{ $v?->asking_price !== null ? \App\Support\Money::format($v->asking_price) : '—' }}</td>
                            <td class="num">{{ $v?->min_price !== null ? \App\Support\Money::format($v->min_price) : '—' }}</td>
                            <td>
                                <input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="items.{{ $i }}.price" class="form-input w-36">
                                @error('items.'.$i.'.price')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </td>
                            <td class="text-end"><x-ui.button variant="ghost" size="sm" icon="trash" wire:click="removeItem({{ $i }})" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-sm text-gray-500">{{ __('sales.no_vehicle_yet') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
                @error('items')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </x-ui.card>

        <x-ui.card :title="__('sales.trade_in')">
            <x-slot:actions>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.live="hasTradeIn" class="rounded border-gray-300 text-brand-600">
                    {{ __('sales.has_trade_in') }}
                </label>
            </x-slot:actions>
            @if ($hasTradeIn)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <x-ui.field :label="__('vehicles.vin')" error="tradeIn.vin" required>
                        <input type="text" dir="ltr" wire:model="tradeIn.vin" class="form-input font-mono uppercase">
                    </x-ui.field>
                    <x-ui.field :label="__('sales.trade_in_value')" error="tradeIn.value" required>
                        <input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="tradeIn.value" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('purchases.entry_status')" error="tradeIn.entry_status" required>
                        <select wire:model="tradeIn.entry_status" class="form-input">
                            <option value="in_preparation">{{ __('enums.vehicle_status.in_preparation') }}</option>
                            <option value="available">{{ __('enums.vehicle_status.available') }}</option>
                        </select>
                    </x-ui.field>
                    @include('livewire.vehicles.partials.fields', ['prefix' => 'tradeIn', 'brandModels' => $tradeInModels])
                </div>
            @else
                <p class="text-sm text-gray-500">{{ __('sales.trade_in_hint') }}</p>
            @endif
        </x-ui.card>

        <x-ui.card :title="__('sales.payment')">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-ui.field :label="__('sales.payment_type')" for="payment_type" error="payment_type" required>
                    <select id="payment_type" wire:model.live="payment_type" class="form-input">
                        @foreach ($paymentTypes as $pt)
                            <option value="{{ $pt->value }}">{{ $pt->label() }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
                <x-ui.field :label="__('app.fields.currency')" for="currency_id" error="currency_id">
                    <select id="currency_id" wire:model.live="currency_id" class="form-input" @disabled($reservation_id)>
                        @foreach ($currencies as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
                <x-ui.field :label="__('documents.rate')" for="rate" error="rate">
                    <input id="rate" type="text" dir="ltr" wire:model="rate" class="form-input" @disabled($currencies->firstWhere('id', $currency_id)?->is_base)>
                </x-ui.field>
                <x-ui.field :label="__('documents.discount')" for="discount" error="discount" :hint="__('sales.discount_hint', ['limit' => \App\Support\Money::format(auth()->user()->max_discount)])">
                    <input id="discount" type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="discount" class="form-input">
                </x-ui.field>
                @if ($salespeople->isNotEmpty())
                    <x-ui.field :label="__('sales.salesperson')" for="salesperson_id" error="salesperson_id">
                        <select id="salesperson_id" wire:model="salesperson_id" class="form-input">
                            @foreach ($salespeople as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                @endif
            </div>

            <div class="mt-4 space-y-2">
                <p class="text-sm font-medium text-gray-700">
                    {{ $payment_type === 'installment' ? __('sales.down_payment') : __('sales.paid_now') }}
                    @if (in_array($payment_type, ['cash', 'transfer']))
                        <button type="button" wire:click="payInFull" class="ms-2 text-xs text-brand-700 hover:underline">{{ __('sales.pay_in_full') }}</button>
                    @endif
                </p>
                @foreach ($payments as $p => $payment)
                    <div class="flex flex-wrap items-start gap-2" wire:key="pay-{{ $p }}">
                        <select wire:model="payments.{{ $p }}.cashbox_id" class="form-input w-56">
                            <option value="">{{ __('documents.cashbox') }}</option>
                            @foreach ($cashboxes as $cb)
                                <option value="{{ $cb->id }}">{{ $cb->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="payments.{{ $p }}.amount" placeholder="0.000" class="form-input w-40">
                        @if (count($payments) > 1)
                            <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="removePayment({{ $p }})" />
                        @endif
                        @error('payments.'.$p.'.cashbox_id')<p class="w-full text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                @endforeach
                @if ($payment_type === 'mixed')
                    <x-ui.button variant="secondary" size="sm" icon="plus" wire:click="addPayment">{{ __('sales.add_payment') }}</x-ui.button>
                @endif
            </div>

            @if ($payment_type === 'installment')
                <div class="mt-4 grid grid-cols-1 gap-4 border-t border-gray-200 pt-4 sm:grid-cols-3">
                    <x-ui.field :label="__('sales.months')" for="months" error="months" required>
                        <input id="months" type="number" min="1" max="120" dir="ltr" wire:model.live.debounce.500ms="months" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('sales.start_date')" for="start_date" error="start_date" required>
                        <input id="start_date" type="date" wire:model.live="start_date" class="form-input">
                    </x-ui.field>
                    <x-ui.field :label="__('sales.guarantor')" for="guarantor_id" error="guarantor_id">
                        <select id="guarantor_id" wire:model.live="guarantor_id" class="form-input">
                            <option value="">{{ __('sales.new_guarantor') }}</option>
                            @foreach ($guarantors as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                    @unless ($guarantor_id)
                        <x-ui.field :label="__('sales.guarantor_name')" error="guarantor.name" required>
                            <input type="text" wire:model="guarantor.name" class="form-input">
                        </x-ui.field>
                        <x-ui.field :label="__('app.fields.phone')" error="guarantor.phone">
                            <input type="text" dir="ltr" wire:model="guarantor.phone" class="form-input">
                        </x-ui.field>
                        <x-ui.field :label="__('parties.national_id')" error="guarantor.national_id">
                            <input type="text" dir="ltr" wire:model="guarantor.national_id" class="form-input">
                        </x-ui.field>
                        <x-ui.field :label="__('sales.relation')" error="guarantor.relation">
                            <input type="text" wire:model="guarantor.relation" class="form-input">
                        </x-ui.field>
                    @endunless
                </div>
            @endif

            <x-ui.field :label="__('app.fields.notes')" for="notes" class="mt-4">
                <input id="notes" type="text" wire:model="notes" class="form-input">
            </x-ui.field>
        </x-ui.card>
    </div>

    <div class="space-y-4">
        <x-ui.card :title="__('sales.summary')" class="xl:sticky xl:top-20">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt>{{ __('documents.subtotal') }}</dt><dd class="num">{{ \App\Support\Money::format($terms->subtotal) }}</dd></div>
                <div class="flex justify-between"><dt>{{ __('documents.discount') }}</dt><dd class="num">− {{ \App\Support\Money::format($terms->discount) }}</dd></div>
                <div class="flex justify-between border-t pt-2 font-semibold"><dt>{{ __('documents.total') }}</dt><dd class="num">{{ \App\Support\Money::format($terms->total) }}</dd></div>
                @if ($terms->tradeIn->isPositive())
                    <div class="flex justify-between"><dt>{{ __('sales.trade_in') }}</dt><dd class="num">− {{ \App\Support\Money::format($terms->tradeIn) }}</dd></div>
                @endif
                @if ($terms->deposit->isPositive())
                    <div class="flex justify-between"><dt>{{ __('sales.deposit') }}</dt><dd class="num">− {{ \App\Support\Money::format($terms->deposit) }}</dd></div>
                @endif
                <div class="flex justify-between font-semibold"><dt>{{ __('sales.due') }}</dt><dd class="num">{{ \App\Support\Money::format($terms->due) }}</dd></div>
                <div class="flex justify-between"><dt>{{ $payment_type === 'installment' ? __('sales.down_payment') : __('sales.paid_now') }}</dt><dd class="num">− {{ \App\Support\Money::format($terms->paid) }}</dd></div>
                <div class="flex justify-between border-t pt-2 text-base font-bold text-brand-700">
                    <dt>{{ $payment_type === 'installment' ? __('sales.financed') : __('sales.remaining') }}</dt>
                    <dd class="num">{{ \App\Support\Money::format($terms->due->minus($terms->paid)) }}</dd>
                </div>
            </dl>

            @if ($schedule)
                <div class="mt-4 max-h-64 overflow-y-auto border-t pt-3">
                    <p class="mb-2 text-xs font-semibold text-gray-600">{{ __('sales.schedule') }}</p>
                    <table class="w-full text-xs">
                        @foreach ($schedule as $row)
                            <tr class="border-b border-gray-100">
                                <td class="py-1 num">{{ $row['sequence'] }}</td>
                                <td class="py-1 num">{{ $row['due_date']->format('Y-m-d') }}</td>
                                <td class="py-1 num text-end">{{ \App\Support\Money::format($row['amount']) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif

            <div class="mt-4 flex flex-col gap-2">
                <x-ui.button type="submit" icon="check" wire:loading.attr="disabled">{{ __('sales.save') }}</x-ui.button>
                <x-ui.button variant="secondary" :href="$invoice ? route('sales.show', $invoice) : route('sales.index')" wire:navigate>{{ __('app.cancel') }}</x-ui.button>
            </div>
        </x-ui.card>
    </div>
</form>
