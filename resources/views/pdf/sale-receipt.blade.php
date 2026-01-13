<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #333; }
        .container { max-width: 100%; margin: 0 auto; padding: 15px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 9px; color: #666; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 9px; }
        .info-box { margin-bottom: 15px; }
        .info-box strong { display: block; margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #f5f5f5; padding: 8px 5px; text-align: left; border-bottom: 1px solid #ddd; font-size: 9px; }
        td { padding: 8px 5px; border-bottom: 1px solid #eee; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background: #f9f9f9; }
        .total-row td { font-size: 11px; padding: 10px 5px; }
        .footer { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ccc; font-size: 9px; color: #666; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; }
        .badge-success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>BangunanPro</h1>
            <p>BangunanPro - Sistem ERP Toko Bangunan</p>
            <p>Receipt #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Sale Info -->
        <div class="info-box">
            <table style="margin: 0;">
                <tr>
                    <td style="border: none; padding: 3px 0;"><strong>Date:</strong> {{ $sale->date->format('d M Y, H:i') }}</td>
                    <td style="border: none; padding: 3px 0; text-align: right;"><strong>Payment:</strong> {{ ucfirst($sale->payment_method) }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 3px 0;"><strong>Customer:</strong> {{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                    <td style="border: none; padding: 3px 0; text-align: right;"><span class="badge badge-success">PAID</span></td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @if($sale->discount_value > 0)
                <tr>
                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right">Rp {{ number_format($sale->saleItems->sum(fn($i) => $i->quantity * $i->unit_price), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Discount:</strong></td>
                    <td class="text-right">- Rp {{ number_format($sale->discount_value, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your purchase!</strong></p>
            <p>Goods sold are non-refundable</p>
            <p style="margin-top: 10px;">Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
