<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #007bff;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 10px;
        }

        .stat-box {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .stat-box label {
            font-size: 12px;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }

        .stat-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Hardware Store - Sales Report</h1>
        <p><strong>Date Range:</strong> {{ $dateRange }}</p>
        <p><strong>Generated:</strong> {{ $generatedAt }}</p>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stat-box">
            <label>Total Sales</label>
            <div class="value">{{ $stats['total_sales'] }}</div>
        </div>
        <div class="stat-box">
            <label>Total Revenue</label>
            <div class="value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <label>Items Sold</label>
            <div class="value">{{ $stats['total_items'] }}</div>
        </div>
        <div class="stat-box">
            <label>Avg Transaction</label>
            <div class="value">Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Sales Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer Name</th>
                <th class="text-right">Items</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->date }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td class="text-right">{{ $sale->saleItems->sum('quantity') }}</td>
                    <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">No sales data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This is an automatically generated report from Hardware Store Inventory System</p>
    </div>
</body>

</html>
