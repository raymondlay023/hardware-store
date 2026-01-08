<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; }
        .alert-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
        table { width: 100%; margin: 15px 0; }
        td { padding: 8px 0; }
        .label { font-weight: bold; color: #6b7280; width: 150px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">⚠️ Low Stock Alert</h1>
            <p style="margin: 10px 0 0 0;">Immediate Attention Required</p>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <div class="alert-box">
                <strong>{{ $productName }}</strong> has reached critically low stock levels and requires immediate reordering.
            </div>
            
            <table>
                <tr>
                    <td class="label">Product:</td>
                    <td><strong>{{ $productName }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Current Stock:</td>
                    <td><strong style="color: #dc2626;">{{ $currentStock }} {{ $unit }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Critical Threshold:</td>
                    <td>{{ $threshold }} {{ $unit }}</td>
                </tr>
                <tr>
                    <td class="label">Suggested Reorder:</td>
                    <td>{{ $reorderQuantity }} {{ $unit }}</td>
                </tr>
            </table>
            
            <p>Please take action to restock this product to avoid stock-outs and potential lost sales.</p>
            
            <a href="{{ config('app.url') }}/products" class="button">View Product in Dashboard</a>
        </div>
        
        <div class="footer">
            <p>This is an automated alert from BangunanPro Inventory System</p>
            <p>{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
