<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Products\ProductList;
use App\Livewire\Suppliers\SupplierList;
use App\Livewire\Purchases\PurchaseList;
use App\Livewire\Sales\SaleList;
use App\Livewire\Dashboard\DashboardView;

Route::get('/dashboard', DashboardView::class)->name('dashboard');
Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/sales', SaleList::class)->name('sales.index');
Route::get('/purchases', PurchaseList::class)->name('purchases.index');
Route::get('/suppliers', SupplierList::class)->name('suppliers.index');
Route::get('/products', ProductList::class)->name('products.index');


// Route::get('/', function () {
//     return view('welcome');
// });