<?php

namespace App\Mail;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Purchase Order Confirmed #' . $this->purchase->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase-confirmation',
            with: [
                'purchaseId' => $this->purchase->id,
                'supplier' => $this->purchase->supplier->name,
                'totalAmount' => $this->purchase->total_amount,
                'items' => $this->purchase->purchaseItems,
                'date' => $this->purchase->date->format('d M Y'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
