<?php

use App\Livewire;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Accounting
    Route::get('accounts', Livewire\Accounts\Index::class)->middleware('can:accounts.view')->name('accounts.index');
    Route::get('cashboxes', Livewire\Cashboxes\Index::class)->middleware('can:cashboxes.view')->name('cashboxes.index');
    Route::get('periods', Livewire\FiscalPeriods\Index::class)->middleware('can:periods.view')->name('periods.index');

    // Setup
    Route::get('currencies', Livewire\Currencies\Index::class)->middleware('can_any:currencies.manage,exchange_rates.manage')->name('currencies.index');
    Route::middleware('can:references.manage')->prefix('references')->name('references.')->group(function () {
        Route::get('brands', Livewire\References\Brands::class)->name('brands');
        Route::get('colors', Livewire\References\Colors::class)->name('colors');
        Route::get('locations', Livewire\References\Locations::class)->name('locations');
    });
    Route::get('branches', Livewire\Branches\Index::class)->middleware('can:branches.manage')->name('branches.index');
    Route::get('settings', Livewire\Settings\Index::class)->middleware('can:settings.manage')->name('settings.index');

    // Administration
    Route::get('users', Livewire\Users\Index::class)->middleware('can:users.manage')->name('users.index');
    Route::get('roles', Livewire\Roles\Index::class)->middleware('can:roles.manage')->name('roles.index');
});

require __DIR__.'/auth.php';
