<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Products\ProductList;
use App\Livewire\Suppliers\SupplierList;
use App\Livewire\Purchases\PurchaseList;
use App\Livewire\Sales\SaleList;
use App\Livewire\Dashboard\DashboardView;

Route::middleware(['auth'])->group(function () {
    // Dashboard - accessible by admin and manager only
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/dashboard', DashboardView::class)->name('dashboard');
    });

    // Products - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/products', ProductList::class)->name('products.index');
    });

    // Suppliers - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/suppliers', SupplierList::class)->name('suppliers.index');
    });

    // Purchases - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/purchases', PurchaseList::class)->name('purchases.index');
    });

    // Sales - accessible by all roles (admin, manager, cashier)
    Route::get('/sales', SaleList::class)->name('sales.index');
});

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile.edit');

require __DIR__.'/auth.php';
