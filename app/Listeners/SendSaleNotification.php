<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use Illuminate\Support\Facades\Log;

class SendSaleNotification
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

        // TODO: Send email/SMS notifications
        // TODO: Push notification to dashboard
        // TODO: Update analytics
    }
}
