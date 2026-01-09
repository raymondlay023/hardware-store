<?php

namespace App\Notifications;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SaleCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Sale $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sale_id' => $this->sale->id,
            'customer_name' => $this->sale->customer_name,
            'total_amount' => $this->sale->total_amount,
            'final_amount' => $this->sale->getFinalAmount(),
            'items_count' => $this->sale->saleItems->count(),
            'payment_method' => $this->sale->payment_method,
            'message' => "Sale #{$this->sale->id} completed - Rp " . number_format($this->sale->getFinalAmount(), 0, ',', '.'),
            'url' => "/sales?sale={$this->sale->id}",
            'icon' => 'fa-cash-register',
            'color' => 'green',
        ];
    }

    /**
     * Get the mail representation of the notification (optional - for future email support).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sale Completed - #' . $this->sale->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new sale has been completed.')
            ->line('Customer: ' . $this->sale->customer_name)
            ->line('Total: Rp ' . number_format($this->sale->getFinalAmount(), 0, ',', '.'))
            ->action('View Sale', url('/sales?sale=' . $this->sale->id))
            ->line('Thank you for using BangunanPro!');
    }
}
