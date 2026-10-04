@props(['title' => null, 'padding' => true])
<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm ring-1 ring-gray-200']) }}>
    @if ($title || isset($actions))
        <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 sm:px-6">
            <h2 class="text-base font-semibold text-gray-800">{{ $title }}</h2>
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div @class(['p-4 sm:p-6' => $padding])>
        {{ $slot }}
    </div>
</div>
