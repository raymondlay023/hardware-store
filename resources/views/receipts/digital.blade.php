<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Receipt #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-lg">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 text-center">
            <i class="fas fa-receipt text-4xl mb-3"></i>
            <h1 class="text-2xl font-bold">{{ config('app.name', 'BangunanPro') }}</h1>
            <p class="text-blue-100 text-sm mt-1">Digital Receipt</p>
        </div>

        <!-- Receipt Info -->
        <div class="p-6 border-b-4 border-dashed border-gray-300">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase">Invoice #</p>
                    <p class="font-bold text-lg">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-xs uppercase">Date</p>
                    <p class="font-bold">{{ $sale->date->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase">Customer</p>
                    <p class="font-semibold">{{ $sale->customer_name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-xs uppercase">Payment</p>
                    <p class="font-semibold capitalize">{{ $sale->payment_method }}</p>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-shopping-cart text-blue-600"></i>
                Items Purchased
            </h2>

            <div class="space-y-3">
                @foreach ($sale->saleItems as $item)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-semibold text-gray-900 flex-1">{{ $item->product->name }}</p>
                            <p class="font-bold text-blue-600 ml-3">
                                Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>{{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Totals -->
        <div class="px-6 pb-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                </div>

                @if ($sale->discount_value && $sale->discount_value > 0)
                    <div class="flex justify-between text-sm text-red-600">
                        <span>Discount:</span>
                        <span class="font-semibold">- Rp {{ number_format($sale->discount_value, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="border-t-2 border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Total:</span>
                        <span class="text-2xl font-black text-green-600">
                            Rp
                            {{ number_format(max(0, $sale->total_amount - ($sale->discount_value ?? 0)), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 pb-6 space-y-3">
            <button onclick="window.print()"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition">
                <i class="fas fa-print"></i> Print Receipt
            </button>

            <button onclick="shareReceipt()"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition">
                <i class="fas fa-share-alt"></i> Share Receipt
            </button>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-6 text-center text-sm text-gray-600 border-t">
            <p class="mb-2">Thank you for shopping with us!</p>
            <p class="text-xs">Cashier: {{ $sale->user->name ?? 'System' }}</p>
            <p class="text-xs text-gray-400 mt-3">
                <i class="fas fa-lock"></i> This receipt is valid for 30 days
            </p>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body {
                background: white;
            }

            button {
                display: none !important;
            }

            .max-w-md {
                max-width: 100%;
                box-shadow: none;
            }
        }
    </style>

    <!-- Share functionality -->
    <script>
        function shareReceipt() {
            if (navigator.share) {
                navigator.share({
                    title: 'Receipt #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}',
                    text: 'My purchase receipt from {{ config('app.name') }}',
                    url: window.location.href
                }).catch(err => console.log('Share cancelled'));
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href);
                alert('Receipt link copied to clipboard!');
            }
        }
    </script>
</body>

</html>
