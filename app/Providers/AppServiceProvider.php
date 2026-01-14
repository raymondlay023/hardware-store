<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Purchase;
use App\Observers\ProductObserver;
use App\Policies\PurchasePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Product Observer
        Product::observe(ProductObserver::class);

        // Register Policies
        Gate::policy(Purchase::class, PurchasePolicy::class);
    }
}
