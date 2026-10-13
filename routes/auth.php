<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// No self-registration: users are created by the admin (Users screen).

Route::middleware('guest')->group(function () {
    Volt::route('login', 'pages.auth.login')
        ->name('login');

    // No password reset by email (users have no email): the admin sets a new password.
});

Route::middleware('auth')->group(function () {
    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});

// Outside "auth": logging out of a session that already expired just lands on the sign-in page.
Route::post('logout', function (Logout $logout) {
    $logout();

    return redirect()->route('login');
})->name('logout');
