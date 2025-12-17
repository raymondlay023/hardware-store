<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintReceipt extends Component
{
    public $saleId = null;
    public $sale = null;
    public $printFormat = 'digital';
    public $orientation = 'portrait'; // Add this property

    public function mount($saleId = null)
    {
        if ($saleId) {
            $this->saleId = $saleId;
            $this->loadSale();
        }
    }

    public function loadSale()
    {
        try {
            $this->sale = Sale::with(['saleItems.product', 'user'])
                ->findOrFail($this->saleId);
        } catch (\Exception $e) {
            $this->dispatch('notification',
                message: 'Sale not found: ' . $e->getMessage(),
                type: 'error'
            );
            $this->sale = null;
        }
    }

    /**
     * Generate QR Code for digital receipt
     */
    public function getQrCodeProperty()
    {
        if (!$this->sale) {
            return null;
        }

        return QrCode::size(250)
                     ->margin(2)
                     ->generate($this->sale->digital_receipt_url);
    }

    public function printThermal()
    {
        if (!$this->sale) {
            $this->dispatch('notification',
                message: 'Please load a sale first',
                type: 'error'
            );
            return null;
        }

        $receipt = $this->generateThermalReceipt();
        
        return response()->streamDownload(function () use ($receipt) {
            echo $receipt;
        }, 'receipt-' . $this->sale->id . '.txt', [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

    public function printPDF()
    {
        if (!$this->sale) {
            $this->dispatch('notification',
                message: 'Please load a sale first',
                type: 'error'
            );
            return null;
        }

        // ini_set('memory_limit', '256M'); // Add this for larger invoices

        // Reload sale with fresh data
        $sale = Sale::with(['saleItems.product', 'user'])
            ->findOrFail($this->saleId);

        $pdf = Pdf::loadView('receipts.pdf', ['sale' => $sale]);
        $pdf->setPaper('a4', $this->orientation); // Use dynamic orientation

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'receipt-' . $sale->id . '.pdf'
        );
    }


    private function generateThermalReceipt()
    {
        $receipt = '';
        $width = 32;

        $companyName = config('app.name', 'HARDWARE STORE');
        $receipt .= $this->centerText($companyName, $width) . "\n";
        $receipt .= str_repeat('=', $width) . "\n\n";

        $receipt .= "Invoice #: " . str_pad($this->sale->id, 10, '0', STR_PAD_LEFT) . "\n";
        $receipt .= "Date: " . $this->sale->date->format('d/m/Y H:i') . "\n";
        $receipt .= "Cashier: " . ($this->sale->user->name ?? 'Unknown') . "\n";
        $receipt .= str_repeat('-', $width) . "\n\n";

        $receipt .= "Customer: " . $this->sale->customer_name . "\n";
        $receipt .= "Payment: " . ucfirst($this->sale->payment_method) . "\n";
        $receipt .= str_repeat('-', $width) . "\n\n";

        $receipt .= "Item" . str_repeat(' ', 13) . "Qty" . str_repeat(' ', 4) . "Price\n";
        $receipt .= str_repeat('-', $width) . "\n";

        $subtotal = 0;
        foreach ($this->sale->saleItems as $item) {
            $itemTotal = $item->quantity * $item->unit_price;
            $subtotal += $itemTotal;

            $productName = substr($item->product->name, 0, 16);
            $receipt .= $productName . "\n";

            $qtyStr = "x" . $item->quantity;
            $priceStr = "Rp " . number_format($itemTotal, 0, ',', '.');
            $spacing = $width - strlen($qtyStr) - strlen($priceStr) - 8;
            $line = $qtyStr . str_repeat(' ', 8) . str_repeat(' ', max(0, $spacing)) . $priceStr;
            $receipt .= substr($line, 0, $width) . "\n";
        }

        $receipt .= str_repeat('=', $width) . "\n";

        $subtotalStr = "Rp " . number_format($subtotal, 0, ',', '.');
        $subtotalLine = "SUBTOTAL" . str_repeat(' ', max(0, $width - 8 - strlen($subtotalStr))) . $subtotalStr;
        $receipt .= substr($subtotalLine, 0, $width) . "\n";

        if ($this->sale->discount_value && $this->sale->discount_value > 0) {
            $discountStr = "- Rp " . number_format($this->sale->discount_value, 0, ',', '.');
            $discountLine = "DISCOUNT" . str_repeat(' ', max(0, $width - 8 - strlen($discountStr))) . $discountStr;
            $receipt .= substr($discountLine, 0, $width) . "\n";
        }

        $totalAmount = max(0, $this->sale->total_amount - ($this->sale->discount_value ?? 0));
        $totalStr = "Rp " . number_format($totalAmount, 0, ',', '.');
        $totalLine = "TOTAL" . str_repeat(' ', max(0, $width - 5 - strlen($totalStr))) . $totalStr;
        $receipt .= str_repeat('=', $width) . "\n";
        $receipt .= substr($totalLine, 0, $width) . "\n";
        $receipt .= str_repeat('=', $width) . "\n\n";

        $receipt .= $this->centerText("Thank you for your purchase!", $width) . "\n";
        $receipt .= $this->centerText("Please come again", $width) . "\n\n";
        $receipt .= $this->centerText(now()->format('d/m/Y H:i:s'), $width) . "\n";

        return $receipt;
    }

    private function centerText($text, $width)
    {
        $textLen = strlen($text);
        if ($textLen >= $width) {
            return substr($text, 0, $width);
        }
        $padding = floor(($width - $textLen) / 2);
        return str_repeat(' ', max(0, $padding)) . $text;
    }

    public function render()
    {
        return view('livewire.sales.print-receipt');
    }
}