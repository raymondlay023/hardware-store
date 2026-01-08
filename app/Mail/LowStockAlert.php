<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Low Stock Alert: ' . $this->product->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-alert',
            with: [
                'productName' => $this->product->name,
                'currentStock' => $this->product->current_stock,
                'threshold' => $this->product->critical_stock_threshold,
                'unit' => $this->product->unit,
                'reorderQuantity' => $this->product->reorder_quantity ?? 50,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
