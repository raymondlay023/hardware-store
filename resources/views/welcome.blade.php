<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Hardware Store - Inventory System</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
            <nav class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-hammer text-2xl"></i>
                    <span class="text-2xl font-bold">Hardware Store</span>
                </div>
                <div class="flex gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                            Dashboard
                        </a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="border-2 border-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-500 transition">
                            Register
                        </a>
                    @endguest
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 py-20">
            <div class="text-center mb-20">
                <h1 class="text-5xl font-bold text-gray-900 mb-6">
                    Hardware Store Inventory System
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Manage your construction materials, suppliers, and sales all in one place.
                </p>
                @guest
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition inline-block">
                        Get Started
                    </a>
                @endguest
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-lg transition">
                    <i class="fas fa-box text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Product Management</h3>
                    <p class="text-gray-600">
                        Track your inventory with ease. Manage products, categories, and stock levels in real-time.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-lg transition">
                    <i class="fas fa-truck text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Supplier Management</h3>
                    <p class="text-gray-600">
                        Organize your suppliers, track payment terms, and manage purchase orders efficiently.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-lg transition">
                    <i class="fas fa-chart-line text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sales Analytics</h3>
                    <p class="text-gray-600">
                        Track sales, monitor revenue, and get insights into your best-selling products.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-lg transition">
                    <i class="fas fa-shopping-cart text-4xl text-orange-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Purchase Orders</h3>
                    <p class="text-gray-600">
                        Create and manage purchase orders. Track incoming stock and update inventory automatically.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-lg transition">
                    <i class="fas fa-users text-4xl text-indigo-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">User Roles</h3>
                    <p class="text-gray-600">
                        Manage different user roles: Admin, Manager, and Cashier with specific permissions.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center hover:shadow-lg transition">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Stock Alerts</h3>
                    <p class="text-gray-600">
                        Get notified when inventory is low and manage stock levels with smart alerts.
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-16 mt-20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold mb-2">100%</div>
                        <p class="text-blue-100">Online Access</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">Real-time</div>
                        <p class="text-blue-100">Updates</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold mb-2">Secure</div>
                        <p class="text-blue-100">Authentication</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        @guest
        <section class="max-w-7xl mx-auto px-4 py-20 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
            <p class="text-gray-600 mb-8">Sign in to your account to access the inventory system.</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                    Create Account
                </a>
            </div>
        </section>
        @endguest

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 mt-12">
            <div class="max-w-7xl mx-auto px-4 py-6 text-center">
                <p>&copy; 2025 Hardware Store Inventory System. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
