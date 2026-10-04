<x-app-layout :title="__('Profile')">
    <div class="space-y-6 max-w-3xl">
        <x-ui.card>
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
