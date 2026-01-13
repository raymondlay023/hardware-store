<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            margin: 15mm !important;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            background: white;
            font-size: 11px;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .info-grid {
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #999;
            border-collapse: collapse;
        }

        .info-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #999;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-cell {
            display: table-cell;
            padding: 6px 8px;
            width: 25%;
            font-size: 10px;
        }

        .info-label {
            background: #f0f0f0;
            font-weight: bold;
            border-right: 1px solid #999;
            width: 20%;
        }

        .info-value {
            padding-left: 8px;
            width: 30%;
        }

        /* Responsive table for both orientations */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: auto;
        }

        thead {
            background: #f0f0f0;
        }

        th {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            border: 1px solid #999;
            padding: 6px 8px;
            font-size: 10px;
            word-wrap: break-word;
        }

        /* Column widths - responsive to orientation */
        th:nth-child(1),
        td:nth-child(1) {
            width: auto;
            /* Product name - takes remaining space */
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 60px;
            /* Qty */
            text-align: center;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 90px;
            /* Unit Price */
            text-align: right;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 100px;
            /* Amount */
            text-align: right;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .totals-box {
            width: 45%;
            margin-left: 55%;
            min-width: 200px;
        }

        .total-row {
            display: table;
            width: 100%;
            padding: 6px 0;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .total-row span:first-child {
            display: table-cell;
            text-align: left;
        }

        .total-row span:last-child {
            display: table-cell;
            text-align: right;
        }

        .total-row.grand-total {
            border: 2px solid #333;
            padding: 8px;
            font-weight: bold;
            font-size: 12px;
            background: #f9f9f9;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #999;
            font-size: 9px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'BangunanPro') }}</h1>
            <p>SALES INVOICE / RECEIPT</p>
        </div>

        <!-- Invoice Information -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Invoice #</div>
                <div class="info-cell info-value">{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="info-cell info-label">Date</div>
                <div class="info-cell info-value">{{ $sale->date->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Customer</div>
                <div class="info-cell info-value">{{ $sale->customer_name }}</div>
                <div class="info-cell info-label">Payment Method</div>
                <div class="info-cell info-value">{{ ucfirst($sale->payment_method) }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Cashier</div>
                <div class="info-cell info-value">{{ $sale->user->name ?? 'System' }}</div>
                <div class="info-cell info-label">Reference</div>
                <div class="info-cell info-value">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sale->saleItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                </div>
                @if ($sale->discount_value && $sale->discount_value > 0)
                    <div class="total-row" style="color: #d32f2f;">
                        <span>Discount:</span>
                        <span>- Rp {{ number_format($sale->discount_value, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>Rp
                        {{ number_format(max(0, $sale->total_amount - ($sale->discount_value ?? 0)), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Please keep this receipt for your records.</p>
            <p>Printed on: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>
