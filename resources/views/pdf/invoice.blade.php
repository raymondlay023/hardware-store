<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $sale->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .container { max-width: 100%; margin: 0 auto; padding: 30px; }
        
        .header { border-bottom: 3px solid #10b981; padding-bottom: 20px; margin-bottom: 25px; }
        .company-info h1 { font-size: 28px; color: #10b981; margin-bottom: 5px; }
        .company-info p { font-size: 10px; color: #666; }
        
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 28px; color: #333; margin-bottom: 5px; }
        .invoice-title .invoice-number { font-size: 14px; color: #10b981; font-weight: bold; }
        
        .info-section { margin-bottom: 25px; }
        .info-box { margin-bottom: 15px; }
        .info-box h3 { font-size: 11px; color: #10b981; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .info-box p { margin-bottom: 2px; }
        
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #10b981; color: white; padding: 12px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 12px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals { margin-top: 20px; }
        .totals table { width: 350px; margin-left: auto; border: none; }
        .totals td { padding: 8px 10px; border: none; border-bottom: 1px solid #e5e7eb; }
        .totals .subtotal-row { color: #666; }
        .totals .total-row { font-size: 16px; font-weight: bold; background: #ecfdf5; color: #065f46; }
        .totals .total-row td { border: none; padding: 15px 10px; }
        
        .payment-info { margin-top: 30px; padding: 20px; background: #f3f4f6; border-radius: 8px; }
        .payment-info h4 { font-size: 12px; color: #374151; margin-bottom: 10px; }
        .payment-info p { font-size: 10px; margin-bottom: 3px; }
        
        .status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-paid { background: #d1fae5; color: #065f46; }
        
        .footer { margin-top: 40px; text-align: center; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 10px; color: #9ca3af; margin-bottom: 3px; }
        
        .thank-you { text-align: center; margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 8px; }
        .thank-you h3 { color: #065f46; font-size: 16px; margin-bottom: 5px; }
        .thank-you p { color: #047857; font-size: 11px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 0; vertical-align: top; width: 50%;">
                        <div class="company-info">
                            <h1>BangunanPro</h1>
                            <p>BangunanPro - Sistem ERP Toko Bangunan</p>
                            <p>Jl. Contoh No. 123, Jakarta 12345</p>
                            <p>Phone: (021) 123-4567 | Email: info@bangunanpro.com</p>
                        </div>
                    </td>
                    <td style="border: none; padding: 0; text-align: right; vertical-align: top; width: 50%;">
                        <div class="invoice-title">
                            <h2>INVOICE</h2>
                            <p class="invoice-number">INV-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p style="margin-top: 10px;"><span class="status status-paid">PAID</span></p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer & Invoice Info -->
        <div class="info-section">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 0; width: 50%; vertical-align: top;">
                        <div class="info-box">
                            <h3>Bill To</h3>
                            <p><strong>{{ $sale->customer_name ?? 'Walk-in Customer' }}</strong></p>
                            @if($sale->customer)
                                @if($sale->customer->phone)
                                    <p>Phone: {{ $sale->customer->phone }}</p>
                                @endif
                                @if($sale->customer->email)
                                    <p>Email: {{ $sale->customer->email }}</p>
                                @endif
                                @if($sale->customer->address)
                                    <p>{{ $sale->customer->address }}</p>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td style="border: none; padding: 0; width: 50%; vertical-align: top;">
                        <div class="info-box">
                            <h3>Invoice Details</h3>
                            <p><strong>Invoice Date:</strong> {{ $sale->date->format('d M Y') }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}</p>
                            <p><strong>Transaction ID:</strong> TXN-{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Description</th>
                    <th class="text-center" style="width: 12%;">Qty</th>
                    <th class="text-right" style="width: 18%;">Unit Price</th>
                    <th class="text-right" style="width: 20%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr class="subtotal-row">
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right" style="width: 130px;">
                        Rp {{ number_format($sale->saleItems->sum(fn($i) => $i->quantity * $i->unit_price), 0, ',', '.') }}
                    </td>
                </tr>
                @if($sale->discount_value > 0)
                <tr class="subtotal-row">
                    <td class="text-right">Discount:</td>
                    <td class="text-right">- Rp {{ number_format($sale->discount_value, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Thank You -->
        <div class="thank-you">
            <h3>Thank You for Your Business!</h3>
            <p>We appreciate your trust in BangunanPro</p>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <h4>Payment Information</h4>
            <p><strong>Status:</strong> Paid in full via {{ ucfirst($sale->payment_method) }}</p>
            <p><strong>Date Paid:</strong> {{ $sale->date->format('d M Y') }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For questions, please contact us at info@bangunanpro.com</p>
            <p style="margin-top: 10px; color: #d1d5db;">Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
