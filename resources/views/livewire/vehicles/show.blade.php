<div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="space-y-4 xl:col-span-2">
        <x-ui.card :title="__('vehicles.card')">
            <x-slot:actions>
                <x-ui.badge :color="$vehicle->status->color()">{{ $vehicle->status->label() }}</x-ui.badge>
                @if ($canUpdate)
                    <x-ui.button size="sm" variant="secondary" icon="pencil" wire:click="edit">{{ __('app.edit') }}</x-ui.button>
                @endif
            </x-slot:actions>

            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm md:grid-cols-3">
                @foreach ([
                    'vehicles.vin' => $vehicle->vin,
                    'vehicles.plate' => $vehicle->plate_no,
                    'vehicles.brand' => $vehicle->brand->name,
                    'vehicles.model' => $vehicle->carModel->name,
                    'vehicles.trim' => $vehicle->trim,
                    'vehicles.year' => $vehicle->year,
                    'vehicles.color' => $vehicle->color?->name,
                    'vehicles.mileage' => $vehicle->mileage !== null ? number_format($vehicle->mileage) : null,
                    'vehicles.fuel' => $vehicle->fuel?->label(),
                    'vehicles.transmission' => $vehicle->transmission?->label(),
                    'vehicles.condition' => $vehicle->condition->label(),
                    'vehicles.origin' => $vehicle->origin,
                    'vehicles.location' => $vehicle->location?->name,
                    'vehicles.received_at' => $vehicle->received_at?->format('Y-m-d'),
                    'vehicles.asking_price' => $vehicle->asking_price !== null ? \App\Support\Money::format($vehicle->asking_price) : null,
                    'vehicles.min_price' => $vehicle->min_price !== null ? \App\Support\Money::format($vehicle->min_price) : null,
                ] as $label => $value)
                    <div>
                        <dt class="text-xs text-gray-500">{{ __($label) }}</dt>
                        <dd @class(['font-medium', 'num font-mono' => in_array($label, ['vehicles.vin', 'vehicles.plate'])])>{{ $value ?? '—' }}</dd>
                    </div>
                @endforeach
            </dl>
            @if ($vehicle->notes)
                <p class="mt-4 rounded-md bg-gray-50 p-3 text-sm text-gray-700">{{ $vehicle->notes }}</p>
            @endif
        </x-ui.card>

        <x-ui.card :title="__('vehicles.photos')">
            @php($gallery = $vehicle->getMedia('photos'))
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @forelse ($gallery as $photo)
                    <div class="group relative overflow-hidden rounded-md ring-1 ring-gray-200" wire:key="ph-{{ $photo->id }}">
                        <a href="{{ $photo->getUrl() }}" target="_blank">
                            <img src="{{ $photo->hasGeneratedConversion('thumb') ? $photo->getUrl('thumb') : $photo->getUrl() }}" class="h-32 w-full object-cover" alt="">
                        </a>
                        @if ($canUpdate)
                            <div class="absolute inset-x-0 bottom-0 flex justify-between bg-black/50 p-1 opacity-0 transition group-hover:opacity-100">
                                <span>
                                    <button type="button" wire:click="movePhoto({{ $photo->id }}, -1)" class="text-white" title="{{ __('vehicles.move_earlier') }}"><x-ui.icon name="arrow-up" class="h-4 w-4" /></button>
                                    <button type="button" wire:click="movePhoto({{ $photo->id }}, 1)" class="text-white" title="{{ __('vehicles.move_later') }}"><x-ui.icon name="arrow-down" class="h-4 w-4" /></button>
                                </span>
                                <button type="button" wire:click="deleteMedia({{ $photo->id }})" wire:confirm="{{ __('app.confirm_delete') }}" class="text-white"><x-ui.icon name="trash" class="h-4 w-4" /></button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="col-span-full text-sm text-gray-500">{{ __('vehicles.no_photos') }}</p>
                @endforelse
            </div>
            @if ($canUpdate)
                <form wire:submit="uploadPhotos" class="mt-4 flex flex-wrap items-center gap-3">
                    <input type="file" multiple accept="image/*" wire:model="photos" class="text-sm">
                    <x-ui.button type="submit" size="sm" icon="plus" wire:loading.attr="disabled">{{ __('vehicles.upload') }}</x-ui.button>
                    @error('photos.*')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                </form>
            @endif
        </x-ui.card>

        <x-ui.card :title="__('vehicles.documents')">
            <ul class="divide-y divide-gray-100 text-sm">
                @forelse ($vehicle->getMedia('documents') as $doc)
                    <li class="flex items-center justify-between py-2" wire:key="doc-{{ $doc->id }}">
                        <a href="{{ $doc->getUrl() }}" target="_blank" class="text-brand-700 hover:underline">
                            <x-ui.icon name="document" class="inline h-4 w-4" /> {{ $doc->name }}
                        </a>
                        @if ($canUpdate)
                            <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="deleteMedia({{ $doc->id }})" wire:confirm="{{ __('app.confirm_delete') }}" />
                        @endif
                    </li>
                @empty
                    <li class="py-2 text-gray-500">{{ __('app.no_records') }}</li>
                @endforelse
            </ul>
            @if ($canUpdate)
                <form wire:submit="uploadDocuments" class="mt-4 flex flex-wrap items-center gap-3">
                    <input type="file" multiple wire:model="documents" class="text-sm">
                    <x-ui.button type="submit" size="sm" icon="plus">{{ __('vehicles.upload') }}</x-ui.button>
                    @error('documents.*')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                </form>
            @endif
        </x-ui.card>
    </div>

    <div class="space-y-4">
        @if ($canUpdate && $vehicle->status->manualTargets())
            <x-ui.card :title="__('vehicles.change_status')">
                <div class="space-y-3">
                    <input type="text" wire:model="statusNote" placeholder="{{ __('vehicles.status_note') }}" class="form-input">
                    @foreach ($vehicle->status->manualTargets() as $target)
                        <x-ui.button class="w-full" icon="check" wire:click="moveTo('{{ $target->value }}')"
                                     wire:confirm="{{ __('vehicles.confirm_status', ['status' => $target->label()]) }}">
                            {{ __('vehicles.move_to', ['status' => $target->label()]) }}
                        </x-ui.button>
                    @endforeach
                    @error('status')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </x-ui.card>
        @endif

        @if ($canViewCost)
            <x-ui.card :title="__('vehicles.cost')">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt>{{ __('vehicles.purchase_cost') }}</dt><dd class="num">{{ \App\Support\Money::format($vehicle->purchase_cost) }}</dd></div>
                    <div class="flex justify-between"><dt>{{ __('vehicles.extra_cost') }}</dt><dd class="num">{{ \App\Support\Money::format($vehicle->extra_cost) }}</dd></div>
                    <div class="flex justify-between border-t pt-2 font-semibold"><dt>{{ __('vehicles.total_cost') }}</dt><dd class="num">{{ \App\Support\Money::format($vehicle->total_cost) }}</dd></div>
                </dl>
                @if ($vehicle->purchaseInvoice)
                    <p class="mt-3 text-xs text-gray-500">
                        {{ __('vehicles.bought_from', ['party' => $vehicle->purchaseInvoice->party->name]) }}
                        <a href="{{ route('purchases.show', $vehicle->purchaseInvoice) }}" wire:navigate class="num text-brand-700 hover:underline">{{ $vehicle->purchaseInvoice->displayNumber() }}</a>
                    </p>
                @endif
                @if ($costs->isNotEmpty())
                    <ul class="mt-3 divide-y divide-gray-100 border-t text-xs">
                        @foreach ($costs as $cost)
                            <li class="flex justify-between py-1.5">
                                <span>{{ $cost->description }} @if ($cost->to_cost_of_sales)<x-ui.badge>{{ __('vehicles.after_sale') }}</x-ui.badge>@endif</span>
                                <span class="num">{{ \App\Support\Money::format($cost->amount) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        @endif

        <x-ui.card :title="__('vehicles.history')" :padding="false">
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach ($logs as $log)
                    <li class="px-4 py-2.5">
                        <div class="flex items-center justify-between">
                            <span>{{ $log->from_status?->label() ?? '—' }} ← <strong>{{ $log->to_status->label() }}</strong></span>
                            <span class="num text-xs text-gray-500">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $log->user?->name }} @if ($log->note)· {{ $log->note }}@endif</p>
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    </div>

    <x-ui.modal wire:model="showEdit" :title="__('vehicles.edit')" max-width="4xl">
        <form wire:submit="save" id="vehicle-form" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @include('livewire.vehicles.partials.fields', ['prefix' => 'form', 'brandModels' => $models])
            <x-ui.field :label="__('app.fields.notes')" for="v-notes" error="form.notes" class="sm:col-span-3">
                <textarea id="v-notes" rows="2" wire:model="form.notes" class="form-input"></textarea>
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="vehicle-form">{{ __('app.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
