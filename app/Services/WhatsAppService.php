<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $url;
    protected bool $enabled;

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
        $this->url = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $this->enabled = config('services.fonnte.enabled', false);
    }

    /**
     * Check if WhatsApp service is enabled and configured
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->token);
    }

    /**
     * Send a WhatsApp message
     *
     * @param string $phone Phone number (with country code, e.g., 628123456789)
     * @param string $message Message content
     * @return array Response from Fonnte API
     */
    public function sendMessage(string $phone, string $message): array
    {
        if (!$this->isEnabled()) {
            Log::info('WhatsApp notification skipped - service disabled', ['phone' => $phone]);
            return ['status' => false, 'reason' => 'WhatsApp service disabled'];
        }

        // Format phone number (remove leading 0, add 62 for Indonesia)
        $phone = $this->formatPhoneNumber($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            Log::info('WhatsApp message sent', [
                'phone' => $phone,
                'status' => $result['status'] ?? false,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Send sale receipt via WhatsApp
     */
    public function sendSaleReceipt(Sale $sale, ?string $phone = null): array
    {
        $phone = $phone ?? $sale->customer?->phone;

        if (!$phone) {
            return ['status' => false, 'reason' => 'No phone number available'];
        }

        $message = $this->buildSaleReceiptMessage($sale);

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send low stock alert via WhatsApp
     */
    public function sendLowStockAlert(Product $product, string $phone): array
    {
        $message = $this->buildLowStockMessage($product);

        return $this->sendMessage($phone, $message);
    }

    /**
     * Build sale receipt message
     */
    protected function buildSaleReceiptMessage(Sale $sale): string
    {
        $storeName = config('app.name', 'BangunanPro');
        $items = '';

        foreach ($sale->saleItems as $item) {
            $items .= sprintf(
                "• %s x%d = Rp %s\n",
                $item->product->name ?? 'Product',
                $item->quantity,
                number_format($item->quantity * $item->unit_price, 0, ',', '.')
            );
        }

        $finalAmount = $sale->getFinalAmount();

        $message = "🧾 *{$storeName}*\n";
        $message .= "━━━━━━━━━━━━━━━\n";
        $message .= "*Struk Penjualan #" . str_pad($sale->id, 6, '0', STR_PAD_LEFT) . "*\n";
        $message .= "Tanggal: " . $sale->created_at->format('d/m/Y H:i') . "\n\n";
        
        $message .= "*Detail Pembelian:*\n";
        $message .= $items;
        $message .= "━━━━━━━━━━━━━━━\n";
        $message .= "Subtotal: Rp " . number_format($sale->total_amount, 0, ',', '.') . "\n";

        if ($sale->discount_value > 0) {
            $discountLabel = $sale->discount_type === 'percentage' 
                ? "Diskon ({$sale->discount_value}%)" 
                : "Diskon";
            $discountAmount = $sale->total_amount - $finalAmount;
            $message .= "{$discountLabel}: -Rp " . number_format($discountAmount, 0, ',', '.') . "\n";
        }

        $message .= "*TOTAL: Rp " . number_format($finalAmount, 0, ',', '.') . "*\n";
        $message .= "Pembayaran: " . ucfirst($sale->payment_method) . "\n\n";
        $message .= "Terima kasih atas kunjungan Anda! 🙏";

        return $message;
    }

    /**
     * Build low stock alert message
     */
    protected function buildLowStockMessage(Product $product): string
    {
        $message = "⚠️ *Peringatan Stok Rendah*\n\n";
        $message .= "Produk: *{$product->name}*\n";
        $message .= "Stok saat ini: {$product->current_stock} {$product->unit}\n";
        $message .= "Batas minimum: {$product->low_stock_threshold} {$product->unit}\n\n";
        $message .= "Segera lakukan pemesanan ulang.";

        return $message;
    }

    /**
     * Format phone number for Indonesian format
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with 62, add it
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
