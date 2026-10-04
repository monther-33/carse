{{-- Vehicle descriptive fields bound to "{$prefix}.x". Expects $brands, $brandModels (models of the chosen brand), $colors, $locations. --}}
@php($p = $prefix)
<x-ui.field :label="__('vehicles.brand')" error="{{ $p }}.brand_id" required>
    <select wire:model.live="{{ $p }}.brand_id" class="form-input">
        <option value="">—</option>
        @foreach ($brands as $b)
            <option value="{{ $b->id }}">{{ $b->name }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.model')" error="{{ $p }}.model_id" required>
    <select wire:model="{{ $p }}.model_id" class="form-input">
        <option value="">—</option>
        @foreach ($brandModels as $m)
            <option value="{{ $m->id }}">{{ $m->name }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.trim')" error="{{ $p }}.trim">
    <input type="text" wire:model="{{ $p }}.trim" class="form-input">
</x-ui.field>
<x-ui.field :label="__('vehicles.year')" error="{{ $p }}.year" required>
    <input type="number" dir="ltr" wire:model="{{ $p }}.year" class="form-input">
</x-ui.field>
<x-ui.field :label="__('vehicles.color')" error="{{ $p }}.color_id">
    <select wire:model="{{ $p }}.color_id" class="form-input">
        <option value="">—</option>
        @foreach ($colors as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.plate')" error="{{ $p }}.plate_no">
    <input type="text" dir="ltr" wire:model="{{ $p }}.plate_no" class="form-input">
</x-ui.field>
<x-ui.field :label="__('vehicles.mileage')" error="{{ $p }}.mileage">
    <input type="number" dir="ltr" wire:model="{{ $p }}.mileage" class="form-input">
</x-ui.field>
<x-ui.field :label="__('vehicles.condition')" error="{{ $p }}.condition" required>
    <select wire:model="{{ $p }}.condition" class="form-input">
        @foreach (\App\Enums\VehicleCondition::cases() as $case)
            <option value="{{ $case->value }}">{{ $case->label() }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.fuel')" error="{{ $p }}.fuel">
    <select wire:model="{{ $p }}.fuel" class="form-input">
        <option value="">—</option>
        @foreach (\App\Enums\FuelType::cases() as $case)
            <option value="{{ $case->value }}">{{ $case->label() }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.transmission')" error="{{ $p }}.transmission">
    <select wire:model="{{ $p }}.transmission" class="form-input">
        <option value="">—</option>
        @foreach (\App\Enums\Transmission::cases() as $case)
            <option value="{{ $case->value }}">{{ $case->label() }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.origin')" error="{{ $p }}.origin">
    <input type="text" wire:model="{{ $p }}.origin" class="form-input">
</x-ui.field>
<x-ui.field :label="__('vehicles.location')" error="{{ $p }}.location_id">
    <select wire:model="{{ $p }}.location_id" class="form-input">
        <option value="">—</option>
        @foreach ($locations as $l)
            <option value="{{ $l->id }}">{{ $l->name }}</option>
        @endforeach
    </select>
</x-ui.field>
<x-ui.field :label="__('vehicles.asking_price')" error="{{ $p }}.asking_price">
    <input type="text" inputmode="decimal" dir="ltr" wire:model="{{ $p }}.asking_price" class="form-input">
</x-ui.field>
<x-ui.field :label="__('vehicles.min_price')" error="{{ $p }}.min_price">
    <input type="text" inputmode="decimal" dir="ltr" wire:model="{{ $p }}.min_price" class="form-input">
</x-ui.field>
