<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Inventory System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-hammer text-2xl"></i>
                    <h1 class="text-2xl font-bold">Hardware Store</h1>
                </div>
                <ul class="flex gap-8 items-center">
                    <li><a href="{{ route('products.index') }}" class="hover:text-blue-100 transition flex items-center gap-2"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="{{ route('suppliers.index') }}" class="hover:text-blue-100 transition flex items-center gap-2"><i class="fas fa-truck"></i> Suppliers</a></li>
                    <li><a href="{{ route('purchases.index') }}" class="hover:text-blue-100 transition flex items-center gap-2"><i class="fas fa-shopping-cart"></i> Purchases</a></li>
                    <li><a href="{{ route('sales.index') }}" class="hover:text-blue-100 transition flex items-center gap-2"><i class="fas fa-cash-register"></i> Sales</a></li>
                    <li><a href="#dashboard" class="hover:text-blue-100 transition flex items-center gap-2"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center">
            <p>&copy; 2025 Hardware Store Inventory System. All rights reserved.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
