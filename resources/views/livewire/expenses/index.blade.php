<div class="space-y-4">
    @if ($dueCount > 0 && ! $dueOnly)
        <div class="flex items-center justify-between rounded-md bg-yellow-50 p-3 text-sm text-yellow-800">
            <span>{{ __('expenses.recurring_due', ['count' => $dueCount]) }}</span>
            <x-ui.button size="sm" variant="secondary" wire:click="$set('dueOnly', true)">{{ __('app.view') }}</x-ui.button>
        </div>
    @endif

    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Expense::class)
                <x-ui.button icon="plus" wire:click="create">{{ __('expenses.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="flex flex-wrap items-center gap-3 border-b border-gray-200 p-4">
            <select wire:model.live="status" class="form-input sm:w-40">
                <option value="">{{ __('app.all') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model.live="dueOnly" class="rounded border-gray-300 text-brand-600">
                {{ __('expenses.due_only') }}
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('documents.number') }}</th>
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('expenses.category') }}</th>
                    <th>{{ __('app.fields.description') }}</th>
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th>{{ __('documents.cashbox') }}</th>
                    <th>{{ __('documents.amount') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($expenses as $expense)
                    <tr wire:key="ex-{{ $expense->id }}">
                        <td class="num font-mono text-xs">{{ $expense->displayNumber() }}</td>
                        <td class="num">{{ $expense->date->format('Y-m-d') }}</td>
                        <td>{{ $expense->category->name }}</td>
                        <td>
                            {{ $expense->description }}
                            @if ($expense->recurs_every_months)
                                <x-ui.badge color="blue">{{ __('expenses.every_months', ['count' => $expense->recurs_every_months]) }}</x-ui.badge>
                            @endif
                            @if ($expense->getFirstMedia('receipt'))
                                <a href="{{ $expense->getFirstMediaUrl('receipt') }}" target="_blank" class="text-brand-600"><x-ui.icon name="document" class="inline h-4 w-4" /></a>
                            @endif
                        </td>
                        <td class="text-xs">
                            @if ($expense->vehicle)
                                <a href="{{ route('vehicles.show', $expense->vehicle) }}" wire:navigate class="text-brand-700 hover:underline">{{ $expense->vehicle->title() }}</a>
                            @endif
                        </td>
                        <td>{{ $expense->cashbox->name }}</td>
                        <td class="num">{{ \App\Support\Money::format($expense->amount) }} {{ $expense->currency->code }}</td>
                        <td><x-ui.badge :color="$expense->status->color()">{{ $expense->status->label() }}</x-ui.badge></td>
                        <td class="text-end whitespace-nowrap">
                            @can('update', $expense)
                                <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $expense->id }})" />
                            @endcan
                            @can('delete', $expense)
                                <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $expense->id }})" wire:confirm="{{ __('documents.confirm_delete_draft') }}" />
                            @endcan
                            @can('approve', $expense)
                                <x-ui.button size="sm" icon="check" wire:click="approve({{ $expense->id }})" wire:confirm="{{ __('documents.confirm_approve') }}">{{ __('documents.approve') }}</x-ui.button>
                            @endcan
                            @if ($expense->recurs_every_months && $expense->isPosted())
                                @can('create', \App\Models\Expense::class)
                                    <x-ui.button variant="ghost" size="sm" icon="plus" wire:click="create({{ $expense->id }})">{{ __('expenses.repeat') }}</x-ui.button>
                                @endcan
                            @endif
                            @can('cancel', $expense)
                                <x-ui.button variant="ghost" size="sm" icon="x" wire:click="openCancel({{ $expense->id }})">{{ __('documents.cancel_document') }}</x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $expenses->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('expenses.edit') : __('expenses.new')">
        <form wire:submit="save" id="expense-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @error('document')<p class="sm:col-span-2 text-sm text-red-600">{{ $message }}</p>@enderror
            <x-ui.field :label="__('app.fields.date')" for="e-date" error="form.date" required>
                <input id="e-date" type="date" wire:model="form.date" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('expenses.category')" for="e-cat" error="form.category_id" required>
                <div class="flex gap-2">
                    <select id="e-cat" wire:model="form.category_id" class="form-input min-w-0 flex-1">
                        <option value="">—</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <x-ui.quick-add type="expense_category" target="form.category_id" />
                </div>
            </x-ui.field>
            <x-ui.field :label="__('documents.cashbox')" for="e-cb" error="form.cashbox_id" required>
                <select id="e-cb" wire:model.live="form.cashbox_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($cashboxes as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->name }} ({{ $cb->currency->code }})</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('documents.amount')" for="e-amount" error="form.amount" required>
                <input id="e-amount" type="text" dir="ltr" inputmode="decimal" wire:model="form.amount" class="form-input">
            </x-ui.field>
            @if ($form['cashbox_id'] ?? null)
                @php($cb = $cashboxes->firstWhere('id', $form['cashbox_id']))
                @if ($cb && ! $cb->currency->is_base)
                    <x-ui.field :label="__('documents.rate')" for="e-rate" error="form.rate" :hint="__('documents.rate_hint')">
                        <input id="e-rate" type="text" dir="ltr" inputmode="decimal" wire:model="form.rate" class="form-input">
                    </x-ui.field>
                @endif
            @endif
            <x-ui.field :label="__('expenses.vehicle')" error="form.vehicle_id" class="sm:col-span-2" :hint="__('expenses.vehicle_hint')">
                <livewire:pickers.vehicle-picker wire:model="form.vehicle_id" />
            </x-ui.field>
            <x-ui.field :label="__('app.fields.description')" for="e-desc" error="form.description" class="sm:col-span-2" required>
                <input id="e-desc" type="text" wire:model="form.description" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('expenses.recurs_every_months')" for="e-rec" error="form.recurs_every_months" :hint="__('expenses.recurs_hint')">
                <input id="e-rec" type="number" min="1" max="24" dir="ltr" wire:model="form.recurs_every_months" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('expenses.receipt')" for="e-receipt" error="receipt">
                <input id="e-receipt" type="file" accept="image/*,application/pdf" wire:model="receipt" class="text-sm">
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="expense-form" wire:loading.attr="disabled">{{ __('documents.save_draft') }}</x-ui.button>
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
