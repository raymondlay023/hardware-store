<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Order #{{ $purchase->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .container { max-width: 100%; margin: 0 auto; padding: 30px; }
        
        .header { border-bottom: 3px solid #7c3aed; padding-bottom: 20px; margin-bottom: 20px; }
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .company-info h1 { font-size: 24px; color: #7c3aed; margin-bottom: 5px; }
        .company-info p { font-size: 10px; color: #666; }
        .po-info { text-align: right; }
        .po-info h2 { font-size: 20px; color: #333; margin-bottom: 5px; }
        .po-info .po-number { font-size: 14px; color: #7c3aed; font-weight: bold; }
        
        .info-section { display: flex; justify-content: space-between; margin-bottom: 25px; }
        .info-box { width: 48%; }
        .info-box h3 { font-size: 12px; color: #7c3aed; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .info-box p { margin-bottom: 3px; }
        .info-box strong { color: #333; }
        
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #7c3aed; color: white; padding: 10px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 10px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals { margin-top: 30px; }
        .totals table { width: 300px; margin-left: auto; }
        .totals td { padding: 8px; border: none; }
        .totals .total-row { font-size: 14px; font-weight: bold; background: #f5f3ff; }
        
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 10px; text-transform: uppercase; font-weight: bold; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-received { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; }
        .footer-grid { display: flex; justify-content: space-between; }
        .signature-box { width: 200px; text-align: center; margin-top: 50px; }
        .signature-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 5px; font-size: 10px; }
        
        .notes { margin-top: 30px; padding: 15px; background: #f9f9f9; border-left: 3px solid #7c3aed; }
        .notes h4 { font-size: 11px; margin-bottom: 5px; color: #7c3aed; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 0; vertical-align: top;">
                        <div class="company-info">
                            <h1>BangunanPro</h1>
                            <p>BangunanPro - Sistem ERP Toko Bangunan</p>
                            <p>Jl. Contoh No. 123, Jakarta</p>
                            <p>Phone: (021) 123-4567</p>
                        </div>
                    </td>
                    <td style="border: none; padding: 0; text-align: right; vertical-align: top;">
                        <div class="po-info">
                            <h2>PURCHASE ORDER</h2>
                            <p class="po-number">PO-{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p style="margin-top: 10px;">Date: {{ $purchase->date->format('d M Y') }}</p>
                            <p>
                                <span class="status status-{{ $purchase->status }}">{{ $purchase->status }}</span>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Supplier Info -->
        <div class="info-section">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 0; width: 50%; vertical-align: top;">
                        <div class="info-box">
                            <h3>Supplier Information</h3>
                            <p><strong>{{ $purchase->supplier->name }}</strong></p>
                            @if($purchase->supplier->contact_person)
                                <p>Contact: {{ $purchase->supplier->contact_person }}</p>
                            @endif
                            @if($purchase->supplier->phone)
                                <p>Phone: {{ $purchase->supplier->phone }}</p>
                            @endif
                            @if($purchase->supplier->email)
                                <p>Email: {{ $purchase->supplier->email }}</p>
                            @endif
                            @if($purchase->supplier->address)
                                <p>Address: {{ $purchase->supplier->address }}</p>
                            @endif
                        </div>
                    </td>
                    <td style="border: none; padding: 0; width: 50%; vertical-align: top;">
                        <div class="info-box">
                            <h3>Order Details</h3>
                            <p><strong>Order Date:</strong> {{ $purchase->date->format('d M Y') }}</p>
                            <p><strong>Payment Terms:</strong> {{ $purchase->supplier->payment_terms ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>
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
                    <th style="width: 45%;">Item Description</th>
                    <th class="text-center" style="width: 15%;">Quantity</th>
                    <th class="text-right" style="width: 17%;">Unit Price</th>
                    <th class="text-right" style="width: 18%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->purchaseItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }} {{ $item->product->unit }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->quantity * $item->unit_cost, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right" style="width: 120px;">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        <div class="notes">
            <h4>Terms & Conditions</h4>
            <p>1. Please deliver goods to our warehouse during business hours (08:00 - 17:00)</p>
            <p>2. Include a delivery note with this PO number</p>
            <p>3. Payment will be processed according to agreed terms</p>
        </div>

        <!-- Footer with Signatures -->
        <div class="footer">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; width: 33%; text-align: center;">
                        <div class="signature-box">
                            <div class="signature-line">Prepared By</div>
                        </div>
                    </td>
                    <td style="border: none; width: 33%; text-align: center;">
                        <div class="signature-box">
                            <div class="signature-line">Approved By</div>
                        </div>
                    </td>
                    <td style="border: none; width: 33%; text-align: center;">
                        <div class="signature-box">
                            <div class="signature-line">Received By</div>
                        </div>
                    </td>
                </tr>
            </table>
            <p style="text-align: center; margin-top: 20px; font-size: 9px; color: #999;">
                Generated on {{ now()->format('d M Y, H:i') }} | BangunanPro ERP System
            </p>
        </div>
    </div>
</body>
</html>
