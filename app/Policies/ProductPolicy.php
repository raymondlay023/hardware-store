<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('products.view');
    }

    /**
     * Determine if the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->hasPermission('products.view');
    }

    /**
     * Determine if the user can create products.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('products.create');
    }

    /**
     * Determine if the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasPermission('products.update');
    }

    /**
     * Determine if the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermission('products.delete');
    }

    /**
     * Determine if the user can import products.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('products.import');
    }

    /**
     * Determine if the user can export products.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('products.export');
    }
}
