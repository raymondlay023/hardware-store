<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Repositories\CustomerRepository;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings
     */
    public function register(): void
    {
        // Bind repositories to their implementations
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository(new Product());
        });

        $this->app->bind(SaleRepository::class, function ($app) {
            return new SaleRepository(new Sale());
        });

        $this->app->bind(CustomerRepository::class, function ($app) {
            return new CustomerRepository(new Customer());
        });
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        //
    }
}
