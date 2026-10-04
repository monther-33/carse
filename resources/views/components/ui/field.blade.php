@props(['label', 'for' => null, 'error' => null, 'hint' => null, 'required' => false])
<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    <label @if ($for) for="{{ $for }}" @endif class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ($required)<span class="text-red-600">*</span>@endif
    </label>
    {{ $slot }}
    @if ($hint)
        <p class="text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @if ($error)
        @error($error)
            <p class="text-xs text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
