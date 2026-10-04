<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Reservation::class)
                <x-ui.button icon="plus" wire:click="create">{{ __('reservations.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="border-b border-gray-200 p-4">
            <select wire:model.live="status" class="form-input sm:w-44">
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
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th>{{ __('sales.customer') }}</th>
                    <th>{{ __('sales.deposit') }}</th>
                    <th>{{ __('reservations.remaining') }}</th>
                    <th>{{ __('reservations.expires_at') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($reservations as $r)
                    @php($active = $r->status === \App\Enums\ReservationStatus::Active)
                    @php($expiringSoon = $active && $r->expires_at->lte(today()->addDays(2)))
                    @php($left = $remaining[$r->id])
                    <tr wire:key="res-{{ $r->id }}">
                        <td class="num font-mono text-xs">{{ $r->number }}</td>
                        <td>{{ $r->vehicle->title() }} <span class="block num font-mono text-xs text-gray-500">{{ $r->vehicle->vin }}</span></td>
                        <td>{{ $r->party->name }} <span class="block text-xs text-gray-500">{{ $r->salesperson->name }}</span></td>
                        <td class="num">
                            {{ \App\Support\Money::format($r->deposit) }} {{ $r->currency->code }}
                            @if ($r->voucher?->isDraft())<x-ui.badge color="yellow">{{ __('reservations.deposit_pending') }}</x-ui.badge>@endif
                            @if (\App\Support\Money::of($r->forfeited_amount)->isPositive())
                                <span class="block text-xs text-red-700">{{ __('reservations.forfeited', ['amount' => \App\Support\Money::format($r->forfeited_amount)]) }}</span>
                            @endif
                        </td>
                        <td class="num font-medium">{{ $r->status === \App\Enums\ReservationStatus::Converted ? '—' : \App\Support\Money::format($left) }}</td>
                        <td @class(['num', 'font-semibold text-red-700' => $expiringSoon])>{{ $r->expires_at->format('Y-m-d') }}</td>
                        <td><x-ui.badge :color="$active ? 'blue' : 'gray'">{{ $r->status->label() }}</x-ui.badge></td>
                        <td class="text-end whitespace-nowrap">
                            @if ($active)
                                @can('create', \App\Models\SalesInvoice::class)
                                    <x-ui.button size="sm" icon="tag" :href="route('sales.create', ['reservation' => $r->id])" wire:navigate>{{ __('reservations.sell') }}</x-ui.button>
                                @endcan
                                @can('manage', $r)
                                    <x-ui.button variant="ghost" size="sm" icon="plus" wire:click="open('top_up', {{ $r->id }})">{{ __('reservations.top_up') }}</x-ui.button>
                                    <x-ui.button variant="ghost" size="sm" icon="calendar" wire:click="open('extend', {{ $r->id }})">{{ __('reservations.extend') }}</x-ui.button>
                                    <x-ui.button variant="ghost" size="sm" icon="car" wire:click="open('vehicle', {{ $r->id }})">{{ __('reservations.change_vehicle') }}</x-ui.button>
                                @endcan
                                @can('cancel', $r)
                                    <x-ui.button variant="ghost" size="sm" icon="x" wire:click="open('cancel', {{ $r->id }})">{{ __('documents.cancel_document') }}</x-ui.button>
                                @endcan
                            @elseif ($left->isPositive())
                                @can('settle', $r)
                                    <x-ui.button variant="ghost" size="sm" icon="cash" wire:click="open('settle', {{ $r->id }})">{{ __('reservations.settle') }}</x-ui.button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $reservations->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="__('reservations.new')">
        <form wire:submit="save" id="res-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('vehicles.vehicle')" error="form.vehicle_id" class="sm:col-span-2" required>
                <livewire:pickers.vehicle-picker wire:model="form.vehicle_id" :statuses="['available']" />
            </x-ui.field>
            <x-ui.field :label="__('sales.customer')" error="form.party_id" class="sm:col-span-2" required>
                <livewire:pickers.party-picker wire:model="form.party_id" kind="customer" :allow-create="true" />
            </x-ui.field>
            <x-ui.field :label="__('documents.cashbox')" for="r-cb" error="form.cashbox_id" required>
                <select id="r-cb" wire:model="form.cashbox_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($cashboxes as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->name }} ({{ $cb->currency->code }})</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('sales.deposit')" for="r-dep" error="form.deposit" required>
                <input id="r-dep" type="text" dir="ltr" inputmode="decimal" wire:model="form.deposit" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('reservations.expires_at')" for="r-exp" error="form.expires_at" required>
                <input id="r-exp" type="date" wire:model="form.expires_at" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.notes')" for="r-notes" error="form.notes">
                <input id="r-notes" type="text" wire:model="form.notes" class="form-input">
            </x-ui.field>
            <p class="text-xs text-gray-500 sm:col-span-2">{{ __('reservations.hint') }}</p>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="res-form" wire:loading.attr="disabled">{{ __('reservations.reserve') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    @if ($action && $target)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-gray-900/50 px-4 py-10" wire:key="dlg-{{ $action }}-{{ $target->id }}">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h3 class="text-base font-semibold">{{ __('reservations.actions.'.$action) }} — <span class="num">{{ $target->number }}</span></h3>
                    <button type="button" wire:click="closeDialog" class="text-gray-400 hover:text-gray-600"><x-ui.icon name="x" class="h-5 w-5" /></button>
                </div>
                <form wire:submit="submit" class="space-y-4 px-6 py-5">
                    @error('input')<p class="rounded-md bg-red-50 p-2 text-sm text-red-700">{{ $message }}</p>@enderror

                    @if ($action === 'extend')
                        <x-ui.field :label="__('reservations.new_expiry')" error="input.expires_at" required>
                            <input type="date" wire:model="input.expires_at" class="form-input">
                        </x-ui.field>
                    @elseif ($action === 'vehicle')
                        <p class="text-sm text-gray-600">{{ __('reservations.current_vehicle') }}: {{ $target->vehicle->vin }}</p>
                        <x-ui.field :label="__('reservations.new_vehicle')" error="input.vehicle_id" required>
                            <livewire:pickers.vehicle-picker wire:model="input.vehicle_id" :statuses="['available']" />
                        </x-ui.field>
                    @elseif ($action === 'top_up')
                        <x-ui.field :label="__('documents.amount')" error="input.amount" required>
                            <input type="text" dir="ltr" inputmode="decimal" wire:model="input.amount" class="form-input">
                        </x-ui.field>
                        <x-ui.field :label="__('documents.cashbox')" error="input.cashbox_id" required>
                            <select wire:model="input.cashbox_id" class="form-input">
                                <option value="">—</option>
                                @foreach ($cashboxes->where('currency_id', $target->currency_id) as $cb)
                                    <option value="{{ $cb->id }}">{{ $cb->name }}</option>
                                @endforeach
                            </select>
                        </x-ui.field>
                    @else
                        <p class="rounded-md bg-blue-50 p-3 text-sm text-blue-800">
                            {{ __('reservations.settle_hint', ['available' => \App\Support\Money::format($input['limit']), 'currency' => $target->currency->code]) }}
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.field :label="__('reservations.refund_amount')" error="input.refund">
                                <input type="text" dir="ltr" inputmode="decimal" wire:model="input.refund" placeholder="0" class="form-input">
                            </x-ui.field>
                            <x-ui.field :label="__('reservations.refund_cashbox')" error="input.cashbox_id">
                                <select wire:model="input.cashbox_id" class="form-input">
                                    <option value="">—</option>
                                    @foreach ($cashboxes->where('currency_id', $target->currency_id) as $cb)
                                        <option value="{{ $cb->id }}">{{ $cb->name }}</option>
                                    @endforeach
                                </select>
                            </x-ui.field>
                            <x-ui.field :label="__('reservations.forfeit_amount')" error="input.forfeit" class="col-span-2">
                                <input type="text" dir="ltr" inputmode="decimal" wire:model="input.forfeit" placeholder="0" class="form-input">
                            </x-ui.field>
                        </div>
                        <x-ui.field :label="__('documents.cancel_reason')" error="input.reason" :required="$action === 'cancel'">
                            <input type="text" wire:model="input.reason" class="form-input">
                        </x-ui.field>
                        <p class="text-xs text-gray-500">{{ __('reservations.rest_stays_credit') }}</p>
                    @endif

                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <x-ui.button variant="secondary" wire:click="closeDialog">{{ __('app.close') }}</x-ui.button>
                        <x-ui.button type="submit" :variant="$action === 'cancel' ? 'danger' : 'primary'" wire:loading.attr="disabled">{{ __('reservations.actions.'.$action) }}</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
