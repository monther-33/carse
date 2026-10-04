<?php

use App\Http\Controllers\PrintController;
use App\Livewire;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Inventory
    Route::get('vehicles', Livewire\Vehicles\Index::class)->middleware('can:vehicles.view')->name('vehicles.index');
    Route::get('vehicles/{vehicle}', Livewire\Vehicles\Show::class)->middleware('can:vehicles.view')->name('vehicles.show');

    // Parties
    Route::get('parties', Livewire\Parties\Index::class)->middleware('can:parties.view')->name('parties.index');
    Route::get('parties/{party}/statement', Livewire\Parties\Statement::class)->name('parties.statement');

    // Purchases
    Route::middleware('can:purchases.view')->group(function () {
        Route::get('purchases', Livewire\Purchases\Index::class)->name('purchases.index');
        Route::get('purchases/create', Livewire\Purchases\Form::class)->middleware('can:purchases.create')->name('purchases.create');
        Route::get('purchases/{invoice}/edit', Livewire\Purchases\Form::class)->middleware('can:purchases.create')->name('purchases.edit');
        Route::get('purchases/{invoice}', Livewire\Purchases\Show::class)->name('purchases.show');
    });

    // Sales
    Route::middleware('can_any:sales.view,sales.view_all')->group(function () {
        Route::get('sales', Livewire\Sales\Index::class)->name('sales.index');
        Route::get('sales/create', Livewire\Sales\Form::class)->middleware('can:sales.create')->name('sales.create');
        Route::get('sales/{invoice}/edit', Livewire\Sales\Form::class)->middleware('can:sales.create')->name('sales.edit');
        Route::get('sales/{invoice}', Livewire\Sales\Show::class)->name('sales.show');
    });
    Route::get('reservations', Livewire\Reservations\Index::class)->middleware('can:reservations.view')->name('reservations.index');
    Route::get('installments', Livewire\Installments\Index::class)->middleware('can_any:vouchers.view,sales.view_all')->name('installments.index');
    Route::get('commissions', Livewire\Commissions\Index::class)->middleware('can:commissions.view')->name('commissions.index');

    // Printing (A4 PDF with the showroom letterhead)
    Route::prefix('print')->name('print.')->group(function () {
        Route::get('sales/{invoice}/{document}', [PrintController::class, 'sales'])
            ->whereIn('document', ['quotation', 'invoice', 'contract', 'delivery', 'schedule'])->name('sales');
        Route::get('vouchers/{voucher}', [PrintController::class, 'voucher'])->name('voucher');
        Route::get('parties/{party}/statement', [PrintController::class, 'statement'])->name('statement');
    });

    // Finance
    Route::get('expenses', Livewire\Expenses\Index::class)->middleware('can:expenses.view')->name('expenses.index');
    Route::get('expense-categories', Livewire\Expenses\Categories::class)->middleware('can:accounts.manage')->name('expense-categories.index');
    Route::get('vouchers', Livewire\Vouchers\Index::class)->middleware('can:vouchers.view')->name('vouchers.index');

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
