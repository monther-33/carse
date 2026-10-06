<div class="space-y-4">
    <div class="rounded-md border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-900">
        <p class="font-semibold">{{ __('system.intro_title') }}</p>
        <p class="mt-1">{{ __('system.intro') }}</p>
    </div>

    <form wire:submit="save" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($modules as $module => $permissions)
                <x-ui.card :title="__('permissions.modules.'.$module)">
                    <ul class="space-y-2">
                        @foreach ($permissions as $permission)
                            @php($action = \Illuminate\Support\Str::after($permission, '.'))
                            <li>
                                <label class="flex cursor-pointer items-center justify-between gap-3 text-sm">
                                    <span @class(['font-semibold text-red-700' => in_array($permission, $locked, true)])>{{ __('permissions.actions.'.$module.'.'.$action) }}</span>
                                    <span class="flex items-center gap-2">
                                        @if (in_array($permission, $locked, true))
                                            <x-ui.icon name="lock" class="h-4 w-4 text-red-600" />
                                        @endif
                                        <input type="checkbox" value="{{ $permission }}" wire:model.live="locked" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    </span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endforeach
        </div>

        <div class="sticky bottom-0 flex items-center justify-between gap-3 rounded-md border border-gray-200 bg-white p-3 shadow">
            <span class="text-sm text-gray-600">{{ __('system.count', ['count' => count($locked)]) }}</span>
            <x-ui.button type="submit" icon="lock" wire:loading.attr="disabled">{{ __('system.save') }}</x-ui.button>
        </div>
    </form>
</div>
