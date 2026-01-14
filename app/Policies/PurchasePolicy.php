<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;

class PurchasePolicy
{
    /**
     * Determine if the user can view any purchases.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('purchases.view');
    }

    /**
     * Determine if the user can view the purchase.
     */
    public function view(User $user, Purchase $purchase): bool
    {
        return $user->hasPermission('purchases.view');
    }

    /**
     * Determine if the user can create purchases.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('purchases.create');
    }

    /**
     * Determine if the user can update the purchase.
     */
    public function update(User $user, Purchase $purchase): bool
    {
        return $user->hasPermission('purchases.update');
    }

    /**
     * Determine if the user can delete the purchase.
     */
    public function delete(User $user, Purchase $purchase): bool
    {
        return $user->hasPermission('purchases.delete');
    }

    /**
     * Determine if the user can receive the purchase.
     */
    public function receive(User $user, Purchase $purchase): bool
    {
        return $user->hasPermission('purchases.receive') || $user->hasPermission('purchases.update');
    }
}
