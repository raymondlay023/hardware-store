<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; }
        .info-box { background: #f3f4f6; padding: 15px; margin: 20px 0; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #f9fafb; text-align: left; padding: 12px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        .total-row { font-weight: bold; background: #f9fafb; font-size: 1.1em; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">✅ Purchase Order Confirmed</h1>
            <p style="margin: 10px 0 0 0;">PO #{{ $purchaseId }}</p>
        </div>
        
        <div class="content">
            <p>Your purchase order has been successfully created and confirmed.</p>
            
            <div class="info-box">
                <p style="margin: 0;"><strong>Supplier:</strong> {{ $supplier }}</p>
                <p style="margin: 10px 0 0 0;"><strong>Date:</strong> {{ $date }}</p>
            </div>
            
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                        <td>Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->quantity * $item->unit_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            <p>The purchase order is now pending delivery. Stock will be updated once received.</p>
        </div>
        
        <div class="footer">
            <p>{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
