<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WhatsAppService $whatsAppService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configure WhatsApp service for testing
        Config::set('services.fonnte.token', 'test-token');
        Config::set('services.fonnte.url', 'https://api.fonnte.com/send');
        Config::set('services.fonnte.enabled', true);
        
        $this->whatsAppService = new WhatsAppService();
    }

    public function test_phone_number_formatting_with_leading_zero(): void
    {
        // Indonesian phone starting with 0
        $formatted = $this->whatsAppService->formatPhoneNumber('081234567890');
        
        $this->assertEquals('6281234567890', $formatted); // 0 is replaced with 62
    }

    public function test_phone_number_formatting_without_country_code(): void
    {
        // Phone without leading zero or country code
        $formatted = $this->whatsAppService->formatPhoneNumber('81234567890');
        
        $this->assertEquals('6281234567890', $formatted);
    }

    public function test_phone_number_formatting_with_country_code(): void
    {
        // Phone already with country code
        $formatted = $this->whatsAppService->formatPhoneNumber('6281234567890');
        
        $this->assertEquals('6281234567890', $formatted);
    }

    public function test_builds_sale_receipt_message(): void
    {
        $product1 = Product::factory()->create(['name' => 'Semen Gresik', 'price' => 50000]);
        $product2 = Product::factory()->create(['name' => 'Cat Tembok', 'price' => 75000]);
        
        $sale = Sale::factory()->create([
            'total_amount' => 100000,
            'discount_type' => 'percentage',
            'discount_value' => 10,
        ]);
        
        // Create sale items
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'unit_price' => 50000,
        ]);

        $message = $this->whatsAppService->buildSaleReceiptMessage($sale->fresh(['saleItems.product']));

        // Check message contains key information
        $this->assertStringContainsString('Terima kasih', $message);
        $this->assertStringContainsString('100.000', $message); // Indonesian number format uses dots
    }

    public function test_builds_low_stock_alert_message(): void
    {
        $product = Product::factory()->create([
            'name' => 'Semen Gresik',
            'current_stock' => 5,
            'low_stock_threshold' => 20,
        ]);

        $message = $this->whatsAppService->buildLowStockMessage($product); // Fixed method name

        // Check message contains product info
        $this->assertStringContainsString('Semen Gresik', $message);
        $this->assertStringContainsString('5', $message);
        $this->assertStringContainsString('20', $message);
        $this->assertStringContainsString('Stok Rendah', $message);
    }

    public function test_service_disabled_returns_false(): void
    {
        // Disable the service
        Config::set('services.fonnte.enabled', false);
        $service = new WhatsAppService();

        Http::fake();

        $result = $service->sendMessage('081234567890', 'Test message');

        $this->assertFalse($result['status']);
        $this->assertEquals('WhatsApp service disabled', $result['reason']);
        
        // Ensure no HTTP request was made
        Http::assertNothingSent();
    }

    public function test_sends_message_with_correct_format(): void
    {
        Http::fake([
            'api.fonnte.com/*' => Http::response([
                'status' => true,
                'message' => 'Message sent',
            ], 200),
        ]);

        $result = $this->whatsAppService->sendMessage('081234567890', 'Test message');

        // Verify HTTP request was made with correct data
        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'test-token') &&
                   $request['target'] === '6281234567890' &&
                   $request['message'] === 'Test message' &&
                   $request['countryCode'] === '62';
        });

        $this->assertTrue($result['status']);
    }
}
