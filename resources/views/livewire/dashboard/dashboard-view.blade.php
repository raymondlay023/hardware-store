<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Overview of your hardware store inventory and sales</p>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Total Products -->
        <div
            class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-semibold">Total Products</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalProducts }}</p>
                </div>
                <i class="fas fa-box text-4xl text-blue-200"></i>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-semibold">Suppliers</p>
                    <p class="text-3xl font-bold text-purple-900 mt-2">{{ $totalSuppliers }}</p>
                </div>
                <i class="fas fa-truck text-4xl text-purple-200"></i>
            </div>
        </div>

        <!-- Inventory Value -->
        <div
            class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-semibold">Inventory Value</p>
                    <p class="text-3xl font-bold text-green-900 mt-2">${{ number_format($inventoryValue, 0) }}</p>
                </div>
                <i class="fas fa-warehouse text-4xl text-green-200"></i>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div
            class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-semibold">Low Stock Items</p>
                    <p class="text-3xl font-bold text-red-900 mt-2">{{ $lowStockCount }}</p>
                    @if ($criticalStockCount > 0)
                        <p class="text-xs text-red-600 mt-1">{{ $criticalStockCount }} critical</p>
                    @endif
                </div>
                <i class="fas fa-exclamation-triangle text-4xl text-red-200"></i>
            </div>
        </div>
    </div>

    <!-- Revenue Metrics -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <!-- Today's Revenue -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Today's Sales</h3>
                <i class="fas fa-calendar-check text-2xl text-green-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($todayRevenue, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ now()->format('l, F j, Y') }}</p>
        </div>

        <!-- This Month Revenue -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">This Month</h3>
                <i class="fas fa-calendar-alt text-2xl text-blue-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($thisMonthRevenue, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ now()->format('F Y') }}</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Total Revenue</h3>
                <i class="fas fa-chart-line text-2xl text-purple-500"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">All time</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-3 gap-8 mb-8">
        <!-- Low Stock Products -->
        <div class="col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-orange-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-orange-500"></i>
                        Low Stock Alert
                    </h3>
                </div>

                @if ($lowStockProducts->count() > 0)
                    <div class="divide-y">
                        @foreach ($lowStockProducts as $product)
                            <div class="px-6 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $product->category }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if ($product->current_stock < 5) bg-red-100 text-red-800
                                            @else
                                                bg-yellow-100 text-yellow-800 @endif">
                                            {{ $product->current_stock }} {{ $product->unit }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-check-circle text-4xl text-green-300 mb-3 block"></i>
                        <p class="text-gray-500">All products have healthy stock levels</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Purchases -->
        <div>
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-yellow-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-clock text-orange-500"></i>
                        Pending Orders
                    </h3>
                </div>

                <div class="p-6">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-orange-600">{{ $pendingPurchases }}</p>
                        <p class="text-sm text-gray-600 mt-2">Purchase orders awaiting receipt</p>

                        @if ($pendingPurchases > 0)
                            <a href="{{ route('purchases.index') }}"
                                class="inline-block mt-4 px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition text-sm font-semibold">
                                View Orders
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales and Top Products -->
    <div class="grid grid-cols-2 gap-8">
        <!-- Recent Sales -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-receipt text-green-500"></i>
                    Recent Sales
                </h3>
            </div>

            @if ($recentSales->count() > 0)
                <div class="divide-y max-h-96 overflow-y-auto">
                    @foreach ($recentSales as $sale)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-gray-900">{{ $sale->customer_name }}</p>
                                <p class="font-bold text-green-600">${{ number_format($sale->total_amount, 2) }}</p>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <p class="text-gray-600">{{ $sale->saleItems->count() }}
                                    item{{ $sale->saleItems->count() !== 1 ? 's' : '' }}</p>
                                <p class="text-gray-500">{{ $sale->date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No sales yet</p>
                </div>
            @endif
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-star text-purple-500"></i>
                    Top Sellers
                </h3>
            </div>

            @if ($topProducts->count() > 0)
                <div class="divide-y max-h-96 overflow-y-auto">
                    @foreach ($topProducts as $product)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-gray-900">{{ $product['name'] }}</p>
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">
                                    #{{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <p class="text-gray-600">{{ $product['quantity_sold'] }} sold</p>
                                <p class="font-semibold text-gray-900">${{ number_format($product['revenue'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No sales data yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Daily Revenue Chart Data (for future enhancement) -->
    @if (count($dailyRevenue) > 0)
        <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-blue-500"></i>
                Sales This Week
            </h3>

            <div class="grid grid-cols-7 gap-2">
                @php
                    $maxRevenue = max(array_values($dailyRevenue)) ?: 1;
                @endphp
                @foreach ($dailyRevenue as $date => $revenue)
                    <div class="text-center">
                        <div class="bg-gradient-to-t from-blue-500 to-blue-400 rounded-t flex items-end justify-center relative group"
                            style="height: {{ max(40, ($revenue / $maxRevenue) * 150) }}px">
                            <span class="text-white text-xs font-bold absolute -top-6">
                                @if ($revenue > 0)
                                    ${{ number_format($revenue, 0) }}
                                @endif
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">{{ \Carbon\Carbon::parse($date)->format('M d') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
