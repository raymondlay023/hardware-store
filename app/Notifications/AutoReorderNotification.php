<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AutoReorderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $purchases;

    public function __construct(array $purchases)
    {
        $this->purchases = $purchases;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $totalPurchases = count($this->purchases);
        $totalItems = array_sum(array_map(fn($p) => $p->purchaseItems->count(), $this->purchases));
        $totalValue = array_sum(array_map(fn($p) => $p->total_amount, $this->purchases));

        return [
            'type' => 'auto_reorder',
            'purchase_count' => $totalPurchases,
            'items_count' => $totalItems,
            'total_value' => $totalValue,
            'message' => "🛒 Auto-Reorder: {$totalPurchases} draft purchase order(s) created with {$totalItems} items. Review and confirm to proceed.",
            'url' => '/purchases?status=pending',
            'icon' => 'fa-truck',
            'color' => 'orange',
            'priority' => 'high',
        ];
    }
}
