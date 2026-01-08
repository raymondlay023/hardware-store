<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Receipt for Sale #' . $this->sale->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sale-receipt',
            with: [
                'saleId' => $this->sale->id,
                'customerName' => $this->sale->customer_name ?? 'Valued Customer',
                'totalAmount' => $this->sale->total_amount,
                'paymentMethod' => $this->sale->payment_method,
                'items' => $this->sale->saleItems,
                'date' => $this->sale->date->format('d M Y'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
