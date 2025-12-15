<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Inventory System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Hardware Store</h1>
            <ul class="flex gap-6">
                <li><a href="{{ route('products.index') }}" class="hover:text-gray-200">Products</a></li>
                <li><a href="#suppliers" class="hover:text-gray-200">Suppliers</a></li>
                <li><a href="#purchases" class="hover:text-gray-200">Purchases</a></li>
                <li><a href="#sales" class="hover:text-gray-200">Sales</a></li>
                <li><a href="#dashboard" class="hover:text-gray-200">Dashboard</a></li>
            </ul>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>
