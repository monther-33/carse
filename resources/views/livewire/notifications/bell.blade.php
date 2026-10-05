<div x-data="{ open: false }" class="relative" wire:poll.300s @notifications-changed.window="$wire.$refresh()">
    <button type="button" @click="open = ! open" class="relative rounded-md p-2 text-gray-500 hover:bg-gray-100" aria-label="{{ __('app.nav.notifications') }}">
        <x-ui.icon name="bell" class="h-6 w-6" />
        @if ($unread > 0)
            <span class="num absolute -top-0.5 end-0 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs font-bold text-white">{{ $unread > 99 ? '99+' : $unread }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.outside="open = false" x-transition
         class="absolute end-0 z-50 mt-2 w-80 rounded-md border border-gray-200 bg-white shadow-lg">
        <div class="border-b border-gray-100 px-4 py-2 text-sm font-semibold text-gray-700">{{ __('app.nav.notifications') }}</div>
        <div class="max-h-96 divide-y divide-gray-100 overflow-y-auto">
            @forelse ($latest as $notification)
                @foreach (method_exists($notification->type, 'lines') ? $notification->type::lines($notification->data) : [] as $line)
                    <a href="{{ $line['url'] }}" wire:navigate class="flex items-start gap-2 px-4 py-2 text-sm hover:bg-gray-50">
                        <span @class(['mt-1.5 h-2 w-2 shrink-0 rounded-full', 'bg-red-500' => $line['color'] === 'red', 'bg-yellow-500' => $line['color'] !== 'red'])></span>
                        <span>{{ $line['text'] }}</span>
                    </a>
                @endforeach
            @empty
                <p class="px-4 py-6 text-center text-sm text-gray-500">{{ __('notifications.none') }}</p>
            @endforelse
        </div>
        <a href="{{ route('notifications.index') }}" wire:navigate class="block border-t border-gray-100 px-4 py-2 text-center text-sm text-brand-700 hover:bg-gray-50">{{ __('notifications.view_all') }}</a>
    </div>
</div>
