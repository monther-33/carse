<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            <x-ui.button variant="secondary" icon="check" wire:click="markAllRead">{{ __('notifications.mark_all_read') }}</x-ui.button>
        </x-slot:actions>

        <ul class="divide-y divide-gray-100">
            @forelse ($notifications as $notification)
                <li wire:key="n-{{ $notification->id }}" @class(['p-4', 'bg-brand-50/40' => $notification->read_at === null])>
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500 num">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
                            @foreach (method_exists($notification->type, 'lines') ? $notification->type::lines($notification->data) : [] as $line)
                                <a href="{{ $line['url'] }}" wire:navigate class="flex items-start gap-2 text-sm hover:underline">
                                    <span @class(['mt-1.5 h-2 w-2 shrink-0 rounded-full', 'bg-red-500' => $line['color'] === 'red', 'bg-yellow-500' => $line['color'] !== 'red'])></span>
                                    <span>{{ $line['text'] }}</span>
                                </a>
                            @endforeach
                        </div>
                        @if ($notification->read_at === null)
                            <x-ui.button variant="ghost" size="sm" icon="check" wire:click="markRead('{{ $notification->id }}')">{{ __('notifications.mark_read') }}</x-ui.button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-gray-500">{{ __('notifications.none') }}</li>
            @endforelse
        </ul>
        <div class="p-4">{{ $notifications->links() }}</div>
    </x-ui.card>
</div>
