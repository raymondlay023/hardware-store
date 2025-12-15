<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Products\ProductList;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', ProductList::class)->name('products.index');
