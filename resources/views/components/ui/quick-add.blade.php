{{--
    "+" button that opens the quick-create modal and selects the new record in a field of the
    current Livewire component:  <x-ui.quick-add type="color" target="form.color_id" />
    preset: a JavaScript object expression, e.g. "{ brand_id: $wire.get('form.brand_id') }".
--}}
@props(['type', 'target' => null, 'preset' => '{}'])
@if (\App\Livewire\QuickCreate::allowed($type))
    <button type="button" title="{{ __('quick.titles.'.$type) }}" aria-label="{{ __('quick.titles.'.$type) }}"
            x-on:click="$dispatch('open-quick-create', { type: @js($type), owner: $wire.$id, target: @js($target), preset: {{ $preset }} })"
            {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-md border border-gray-300 bg-white px-2.5 text-gray-500 shadow-sm hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700']) }}>
        <x-ui.icon name="plus" class="h-4 w-4" />
    </button>
@endif
