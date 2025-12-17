<?php

use App\Livewire\Dashboard\DashboardView;
use App\Livewire\Products\ProductList;
use App\Livewire\Purchases\PurchaseList;
use App\Livewire\Sales\CreateSale;
use App\Livewire\Sales\SaleList;
use App\Livewire\Sales\SalesReport;
use App\Livewire\Suppliers\SupplierList;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Dashboard - accessible by admin and manager only
    Route::middleware('role:admin,manager')->group(function () {
    });
    Route::get('/dashboard', DashboardView::class)->name('dashboard');

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
    Route::get('/sales/create', CreateSale::class)->name('sales.create');

    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/sales-report', SalesReport::class)->name('sales-report');
    });

    Route::get('/sales/{saleId}/receipt', \App\Livewire\Sales\PrintReceipt::class)->name('sales.receipt');
});

Route::get('/receipt/{sale}/{token}', function(\App\Models\Sale $sale, $token) {
    // Verify token to prevent unauthorized access
    if (!$sale->verifyReceiptToken($token)) {
        abort(403, 'Invalid receipt link');
    }
    
    // Load relationships
    $sale->load(['saleItems.product', 'user']);
    
    return view('receipts.digital', compact('sale'));
})->name('receipt.digital');

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile.edit');

require __DIR__.'/auth.php';
