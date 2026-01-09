<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendSaleNotification implements ShouldQueue
{
    public function handle(SaleCompleted $event): void
    {
        $sale = $event->sale;
        
        // Log the sale completion
        Log::info("Sale #{$sale->id} completed", [
            'customer' => $sale->customer_name,
            'total' => $sale->total_amount,
            'items_count' => $sale->saleItems->count(),
        ]);

        // Send database notification to admin/manager users
        $this->notifyAdmins($sale);
    }

    /**
     * Notify admin and manager users about the sale
     */
    protected function notifyAdmins($sale): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'manager']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SaleCompletedNotification($sale));
        }
    }
}

