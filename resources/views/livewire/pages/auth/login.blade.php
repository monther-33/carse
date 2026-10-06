<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="username" class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('app.fields.username') }}</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-gray-400">
                    <x-ui.icon name="user" class="h-5 w-5" />
                </span>
                <input wire:model="form.username" id="username" name="username" type="text" dir="ltr" required autofocus
                       autocomplete="username" autocapitalize="none" spellcheck="false"
                       class="block w-full rounded-lg border-gray-300 py-2.5 ps-10 text-start shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
            <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-gray-400">
                    <x-ui.icon name="lock" class="h-5 w-5" />
                </span>
                <input wire:model="form.password" id="password" name="password" :type="show ? 'text' : 'password'" type="password" dir="ltr" required
                       autocomplete="current-password"
                       class="block w-full rounded-lg border-gray-300 py-2.5 pe-10 ps-10 text-start shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <button type="button" @click="show = ! show" class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-gray-600"
                        :aria-label="show ? @js(__('app.auth.hide_password')) : @js(__('app.auth.show_password'))">
                    <x-ui.icon name="eye" class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <label for="remember" class="inline-flex cursor-pointer items-center gap-2">
            <input wire:model="form.remember" id="remember" type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
            <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>

        <button type="submit" wire:loading.attr="disabled"
                class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-60">
            <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
            <span wire:loading wire:target="login">{{ __('app.auth.signing_in') }}</span>
        </button>

        <p class="text-center text-xs text-gray-500">{{ __('app.auth.forgot_hint') }}</p>
    </form>
</div>
