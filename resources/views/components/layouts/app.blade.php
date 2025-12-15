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
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <i class="fas fa-hammer text-2xl"></i>
                    <h1 class="text-2xl font-bold">Hardware Store</h1>
                </div>

                <!-- Navigation Links -->
                @auth
                    <ul class="flex gap-8 items-center">
                        <!-- Dashboard - Admin & Manager only -->
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <li><a href="{{ route('dashboard') }}"
                                    class="hover:text-blue-100 transition flex items-center gap-2"><i
                                        class="fas fa-chart-line"></i> Dashboard</a></li>
                        @endif

                        <!-- Products - Admin & Manager only -->
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <li><a href="{{ route('products.index') }}"
                                    class="hover:text-blue-100 transition flex items-center gap-2"><i
                                        class="fas fa-box"></i> Products</a></li>
                        @endif

                        <!-- Suppliers - Admin & Manager only -->
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <li><a href="{{ route('suppliers.index') }}"
                                    class="hover:text-blue-100 transition flex items-center gap-2"><i
                                        class="fas fa-truck"></i> Suppliers</a></li>
                        @endif

                        <!-- Purchases - Admin & Manager only -->
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <li><a href="{{ route('purchases.index') }}"
                                    class="hover:text-blue-100 transition flex items-center gap-2"><i
                                        class="fas fa-shopping-cart"></i> Purchases</a></li>
                        @endif

                        <!-- Sales - All roles -->
                        <li><a href="{{ route('sales.index') }}"
                                class="hover:text-blue-100 transition flex items-center gap-2"><i
                                    class="fas fa-cash-register"></i> Sales</a></li>
                    </ul>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <div class="relative group">
                            <button class="flex items-center gap-2 hover:text-blue-100 transition">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                class="absolute right-0 mt-0 w-48 bg-white text-gray-900 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                    <p class="text-xs text-blue-600 font-semibold mt-1">
                                        @foreach (Auth::user()->roles as $role)
                                            {{ ucfirst($role->name) }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </p>
                                </div>

                                <!-- Profile Link -->
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100 transition flex items-center gap-2">
                                    <i class="fas fa-user-edit"></i> Edit Profile
                                </a>

                                <!-- Admin Only: User Management -->
                                @if (Auth::user()->roles()->where('name', 'admin')->exists())
                                    <a href="#users"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100 transition flex items-center gap-2 border-t border-gray-200">
                                        <i class="fas fa-users"></i> Manage Users
                                    </a>
                                @endif

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 transition flex items-center gap-2 border-t border-gray-200 text-red-600">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- Login Link (if not authenticated) -->
                @guest
                    <a href="{{ route('login') }}"
                        class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endguest
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
