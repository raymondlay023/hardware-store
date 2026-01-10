<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendSaleNotification implements ShouldQueue
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}

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

        // Send WhatsApp receipt to customer if phone number available
        $this->sendWhatsAppReceipt($sale);
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

    /**
     * Send WhatsApp receipt to customer
     */
    protected function sendWhatsAppReceipt($sale): void
    {
        $phone = $sale->customer?->phone;

        if (!$phone) {
            Log::info("WhatsApp receipt skipped - no customer phone", ['sale_id' => $sale->id]);
            return;
        }

        try {
            $result = $this->whatsAppService->sendSaleReceipt($sale, $phone);
            
            if ($result['status'] ?? false) {
                Log::info("WhatsApp receipt sent", ['sale_id' => $sale->id, 'phone' => $phone]);
            } else {
                Log::warning("WhatsApp receipt failed", [
                    'sale_id' => $sale->id,
                    'reason' => $result['reason'] ?? 'Unknown'
                ]);
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp receipt error", [
                'sale_id' => $sale->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
