@push('head')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

@endpush
<div>
    <!-- Page Header -->
    <x-page-header 
        title="Dashboard" 
        :description="__('Centralized view of business stats and sales')"
        icon="fa-chart-line">
        <x-slot name="actions">
            <!-- Cache Clear Button (Admin/Manager only) -->
            @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                <x-app-button 
                    type="secondary" 
                    icon="sync-alt"
                    wire:click="clearCache"
                    class="w-full sm:w-auto"
                    title="Clear dashboard cache for fresh data">
                    {{ __('Refresh Cache') }}
                </x-app-button>
            @endif
        </x-slot>
    </x-page-header>

    <!-- Date Range Filter -->
    <x-app-card no-padding class="mb-6">
        <div class="flex flex-col lg:flex-row flex-wrap items-start lg:items-center gap-3 p-4">
            <div class="flex items-center gap-2 w-full lg:w-auto">
                <i class="fas fa-calendar text-gray-400"></i>
                <span class="text-sm font-semibold text-gray-700">{{ __('Period:') }}</span>
            </div>

            <!-- Quick Filter Buttons -->
            <div class="flex flex-wrap gap-2 w-full lg:w-auto lg:flex-1">
                <button wire:click="$set('dateRange', 'today')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition
                        {{ $dateRange === 'today' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('Today') }}
                </button>

                <button wire:click="$set('dateRange', 'yesterday')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition
                        {{ $dateRange === 'yesterday' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('Yesterday') }}
                </button>

                <button wire:click="$set('dateRange', 'week')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition
                        {{ $dateRange === 'week' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('This Week') }}
                </button>

                <button wire:click="$set('dateRange', 'month')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition
                        {{ $dateRange === 'month' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('This Month') }}
                </button>

                <button wire:click="$set('dateRange', 'last_month')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition hidden sm:block
                        {{ $dateRange === 'last_month' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('Last Month') }}
                </button>

                <button wire:click="$set('dateRange', 'year')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition
                        {{ $dateRange === 'year' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('This Year') }}
                </button>

                <button wire:click="$set('dateRange', 'all')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition hidden sm:block
                        {{ $dateRange === 'all' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('All Time') }}
                </button>

                <button wire:click="$toggle('showCustomDatePicker')"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm transition
                        {{ $dateRange === 'custom' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-sliders-h mr-1"></i> {{ __('Custom') }}
                </button>
            </div>

            <!-- Selected Range Display -->
            <div class="w-full lg:w-auto flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-lg">
                <i class="fas fa-info-circle text-blue-600"></i>
                <span class="text-xs sm:text-sm font-semibold text-blue-900">{{ $dateRangeLabel }}</span>
            </div>
        </div>

        <!-- Custom Date Picker -->
        @if ($showCustomDatePicker)
            <div class="mt-4 pt-4 border-t border-gray-200 px-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('From Date') }}</label>
                        <input type="date" wire:model="customDateFrom"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('To Date') }}</label>
                        <input type="date" wire:model="customDateTo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="w-full sm:w-auto">
                        <x-app-button 
                            type="primary" 
                            icon="check"
                            wire:click="applyCustomDateRange"
                            class="w-full">
                            {{ __('Apply') }}
                        </x-app-button>
                    </div>
                </div>
            </div>
        @endif
    </x-app-card>

    <!-- Key Metrics Grid - RESPONSIVE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
        <!-- Total Products -->
        <div
            class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-xs sm:text-sm font-semibold">{{ __('Total Products') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-900 mt-1 sm:mt-2">{{ $totalProducts }}</p>
                </div>
                <i class="fas fa-box text-3xl sm:text-4xl text-blue-200"></i>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-xs sm:text-sm font-semibold">{{ __('Suppliers') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-900 mt-1 sm:mt-2">{{ $totalSuppliers }}</p>
                </div>
                <i class="fas fa-truck text-3xl sm:text-4xl text-purple-200"></i>
            </div>
        </div>

        <!-- Inventory Value -->
        <div
            class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-green-600 text-xs sm:text-sm font-semibold">{{ __('Inventory Value') }}</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-900 mt-1 sm:mt-2 truncate">
                        Rp {{ number_format($inventoryValue / 1000000, 1) }}M
                    </p>
                </div>
                <i class="fas fa-warehouse text-3xl sm:text-4xl text-green-200 flex-shrink-0"></i>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div
            class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-xs sm:text-sm font-semibold">{{ __('Low Stock Items') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-900 mt-1 sm:mt-2">{{ $lowStockCount }}</p>
                    @if ($criticalStockCount > 0)
                        <p class="text-xs text-red-600 mt-1">{{ $criticalStockCount }} {{ __('critical') }}</p>
                    @endif
                </div>
                <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl text-red-200"></i>
            </div>
        </div>

        <!-- Stock Turnover -->
        <div
            class="bg-gradient-to-br from-teal-50 to-teal-100 border border-teal-200 rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-600 text-xs sm:text-sm font-semibold">{{ __('Stock Turnover') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-teal-900 mt-1 sm:mt-2">
                        {{ number_format($turnoverRate, 1) }}x</p>
                    <p class="text-xs text-teal-600 mt-1">Last 30 days</p>
                </div>
                <i class="fas fa-sync-alt text-3xl sm:text-4xl text-teal-200"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions - RESPONSIVE -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <a href="{{ route('sales.create') }}"
            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition text-center group">
            <i
                class="fas fa-cash-register text-2xl sm:text-3xl mb-1 sm:mb-2 block group-hover:scale-110 transition"></i>
            <p class="font-semibold text-xs sm:text-sm">{{ __('New Sale') }}</p>
        </a>

        <a href="{{ route('purchases.index') }}"
            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition text-center group">
            <i
                class="fas fa-shopping-cart text-2xl sm:text-3xl mb-1 sm:mb-2 block group-hover:scale-110 transition"></i>
            <p class="font-semibold text-xs sm:text-sm">{{ __('New Purchase') }}</p>
        </a>

        <a href="{{ route('products.index') }}"
            class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition text-center group">
            <i class="fas fa-box-open text-2xl sm:text-3xl mb-1 sm:mb-2 block group-hover:scale-110 transition"></i>
            <p class="font-semibold text-xs sm:text-sm">{{ __('Manage Inventory') }}</p>
        </a>

        <a href="{{ route('sales.index') }}"
            class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition text-center group">
            <i class="fas fa-chart-bar text-2xl sm:text-3xl mb-1 sm:mb-2 block group-hover:scale-110 transition"></i>
            <p class="font-semibold text-xs sm:text-sm">{{ __('View Reports') }}</p>
        </a>
    </div>

    <!-- Revenue Metrics - RESPONSIVE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Today's Sales -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ __('Today\'s Sales') }}</h3>
                <i class="fas fa-calendar-check text-xl sm:text-2xl text-green-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">Rp
                {{ number_format($todayRevenue / 1000, 0) }}k</p>

            @if ($yesterdayRevenue > 0)
                <div class="flex items-center gap-2 mt-2">
                    @if ($revenueChange >= 0)
                        <span class="text-green-600 text-xs sm:text-sm font-semibold flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> {{ number_format(abs($revenueChange), 1) }}%
                        </span>
                    @else
                        <span class="text-red-600 text-xs sm:text-sm font-semibold flex items-center gap-1">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($revenueChange), 1) }}%
                        </span>
                    @endif
                    <span class="text-gray-500 text-xs">{{ __('vs yesterday') }}</span>
                </div>
            @endif

            <p class="text-xs sm:text-sm text-gray-500 mt-2 hidden sm:block">{{ now()->format('l, F j, Y') }}</p>
        </div>

        <!-- This Month Revenue -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ __('This Month') }}</h3>
                <i class="fas fa-calendar-alt text-xl sm:text-2xl text-blue-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">Rp
                {{ number_format($thisMonthRevenue / 1000, 0) }}k</p>

            @if ($lastMonthRevenue > 0)
                <div class="flex items-center gap-2 mt-2">
                    @if ($monthlyChange >= 0)
                        <span class="text-green-600 text-xs sm:text-sm font-semibold flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> {{ number_format(abs($monthlyChange), 1) }}%
                        </span>
                    @else
                        <span class="text-red-600 text-xs sm:text-sm font-semibold flex items-center gap-1">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($monthlyChange), 1) }}%
                        </span>
                    @endif
                    <span class="text-gray-500 text-xs">{{ __('vs last month') }}</span>
                </div>
            @endif

            <p class="text-xs sm:text-sm text-gray-500 mt-2">{{ now()->format('F Y') }}</p>
        </div>

        <!-- Selected Period Revenue -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ __('Selected Period') }}</h3>
                <i class="fas fa-chart-line text-xl sm:text-2xl text-purple-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">Rp
                {{ number_format($totalRevenue / 1000, 0) }}k</p>
            <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between gap-1 mt-2">
                <p class="text-xs sm:text-sm text-gray-500">{{ $totalTransactions }} {{ __('trans.') }}</p>
                <p class="text-xs sm:text-sm font-semibold text-purple-600 truncate">
                    {{ __('Avg:') }} Rp {{ number_format($avgTransaction / 1000, 0) }}k
                </p>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 shadow-sm">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center gap-2">
                <i class="fas fa-wallet text-blue-500"></i>
                <span class="truncate">{{ __('Payment Methods') }}</span>
            </h3>

            <div class="space-y-2 sm:space-y-3">
                @foreach ($paymentMethodStats as $payment)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <i
                                class="fas fa-{{ $payment->payment_method === 'cash' ? 'money-bill' : 'credit-card' }} text-gray-400 flex-shrink-0"></i>
                            <span
                                class="text-xs sm:text-sm font-medium capitalize truncate">{{ $payment->payment_method }}</span>
                        </div>
                        <div class="text-right ml-2">
                            <p class="font-semibold text-gray-900 text-xs sm:text-sm truncate">
                                Rp {{ number_format($payment->total / 1000, 0) }}k
                            </p>
                            <p class="text-xs text-gray-500">{{ $payment->count }}x</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content Grid - RESPONSIVE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-6 sm:mb-8">
        <!-- Low Stock Products -->
        <div class="lg:col-span-2">
            <x-app-card 
                title="Low Stock Alert"
                icon="exclamation-circle"
                header-color="warning" 
                bordered
                no-padding>

                @if ($lowStockProducts->count() > 0)
                    <div class="divide-y max-h-96 overflow-y-auto">
                        @foreach ($lowStockProducts as $product)
                            <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                    <div class="flex-1 min-w-0 w-full sm:w-auto">
                                        <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                            {{ $product->name }}</p>
                                        <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $product->category }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-end">
                                        <span
                                            class="px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold flex-shrink-0
                                            @if ($product->current_stock < 5) bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $product->current_stock }} {{ $product->unit }}
                                        </span>

                                        <x-app-button 
                                            href="{{ route('purchases.create') }}?product={{ $product->id }}"
                                            type="primary"
                                            icon="plus"
                                            size="sm"
                                            title="Reorder">
                                            <span class="hidden sm:inline">{{ __('Reorder') }}</span>
                                        </x-app-button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                        <i class="fas fa-check-circle text-3xl sm:text-4xl text-green-300 mb-2 sm:mb-3 block"></i>
                        <p class="text-gray-500 text-sm sm:text-base">{{ __('All products have healthy stock levels') }}</p>
                    </div>
                @endif
            </x-app-card>
        </div>

        <!-- Pending Purchases -->
        <div>
            <x-app-card 
                title="Pending Orders"
                icon="clock"
                header-color="warning"
                bordered>

                <div class="text-center">
                        <p class="text-3xl sm:text-4xl font-bold text-orange-600">{{ $pendingPurchases }}</p>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2">{{ __('Purchase orders awaiting receipt') }}</p>

                        @if ($pendingPurchases > 0)
                            <x-app-button 
                                href="{{ route('purchases.index') }}"
                                type="warning"
                                size="sm"
                                class="mt-3 sm:mt-4">
                                {{ __('View Orders') }}
                            </x-app-button>
                        @endif
                </div>
            </x-app-card>
        </div>
    </div>

    <!-- Recent Sales and Top Products - RESPONSIVE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
        <!-- Recent Sales -->
        <x-app-card 
            title="Recent Sales"
            :description="'(' . $dateRangeLabel . ')'"
            icon="receipt"
            header-color="success" 
            bordered
            no-padding>

            @if ($recentSales->count() > 0)
                <div class="divide-y max-h-80 sm:max-h-96 overflow-y-auto">
                    @foreach ($recentSales as $sale)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-gray-900 text-sm sm:text-base truncate flex-1 mr-2">
                                    {{ $sale->customer_name }}</p>
                                <p class="font-bold text-green-600 text-sm sm:text-base whitespace-nowrap">
                                    Rp {{ number_format($sale->total_amount / 1000, 0) }}k
                                </p>
                            </div>
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <p class="text-gray-600">{{ $sale->saleItems->count() }}
                                    {{ $sale->saleItems->count() !== 1 ? __('items') : __('item') }}</p>
                                <p class="text-gray-500">{{ $sale->date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <i class="fas fa-inbox text-3xl sm:text-4xl text-gray-300 mb-2 sm:mb-3 block"></i>
                    <p class="text-gray-500 text-sm sm:text-base">{{ __('No sales in this period') }}</p>
                </div>
            @endif
        </x-app-card>

        <!-- Top Selling Products -->
        <x-app-card 
            title="Top Sellers"
            :description="'(' . $dateRangeLabel . ')'"
            icon="star"
            header-color="purple" 
            bordered
            no-padding>

            @if ($topProducts->count() > 0)
                <div class="divide-y max-h-80 sm:max-h-96 overflow-y-auto">
                    @foreach ($topProducts as $product)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-gray-900 text-sm sm:text-base truncate flex-1 mr-2">
                                    {{ $product['name'] }}</p>
                                <span
                                    class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold flex-shrink-0">
                                    #{{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <p class="text-gray-600">{{ $product['quantity_sold'] }} {{ __('sold') }}</p>
                                <p class="font-semibold text-gray-900 whitespace-nowrap">
                                    Rp {{ number_format($product['revenue'] / 1000, 0) }}k
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <i class="fas fa-inbox text-3xl sm:text-4xl text-gray-300 mb-2 sm:mb-3 block"></i>
                    <p class="text-gray-500 text-sm sm:text-base">{{ __('No sales data in this period') }}</p>
                </div>
            @endif
        </x-app-card>
    </div>

    <!-- Recent Activity & Top Customers Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
        <!-- Recent Activity Feed -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-500"></i>
                    <span>{{ __('Recent Activity') }}</span>
                </h3>
                <a href="{{ route('admin.activity-logs') }}" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                    {{ __('View All') }} →
                </a>
            </div>

            @if ($recentActivity->count() > 0)
                <div class="divide-y max-h-80 sm:max-h-96 overflow-y-auto">
                    @foreach ($recentActivity as $activity)
                        <div class="px-4 sm:px-6 py-3 hover:bg-gray-50 transition">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-{{ $activity['color'] }}-100">
                                        <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-sm"></i>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        <span class="capitalize">{{ $activity['action'] }}</span>
                                        <span class="text-gray-600">{{ $activity['model'] }}</span>
                                        <span class="text-gray-400">#{{ $activity['model_id'] }}</span>
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500">{{ $activity['user'] }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <i class="fas fa-clock text-3xl sm:text-4xl text-gray-300 mb-2 sm:mb-3 block"></i>
                    <p class="text-gray-500 text-sm sm:text-base">{{ __('No recent activity recorded') }}</p>
                </div>
            @endif
        </div>

        <!-- Top Customers Widget -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-users text-amber-500"></i>
                    <span>{{ __('Top Customers') }}</span>
                </h3>
                <a href="{{ route('customers.index') }}" class="text-xs sm:text-sm text-amber-600 hover:text-amber-800 font-semibold">
                    {{ __('View All') }} →
                </a>
            </div>

            @if ($topCustomers->count() > 0)
                <div class="divide-y max-h-80 sm:max-h-96 overflow-y-auto">
                    @foreach ($topCustomers as $index => $customer)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                            {{ $customer['name'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 capitalize">
                                            {{ $customer['type'] }} • {{ $customer['orders'] }} {{ __('orders') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right ml-3">
                                    <p class="font-bold text-gray-900 text-sm sm:text-base whitespace-nowrap">
                                        Rp {{ number_format($customer['revenue'] / 1000, 0) }}k
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <i class="fas fa-users text-3xl sm:text-4xl text-gray-300 mb-2 sm:mb-3 block"></i>
                    <p class="text-gray-500 text-sm sm:text-base">{{ __('No customer data yet') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Revenue Chart -->
    @if (count($dailyRevenue) > 0)
        <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        {{ __('Sales Trend') }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $dateRangeLabel }}</p>
                </div>

                <!-- Chart Type Toggle -->
                <div class="flex gap-2">
                    <button onclick="updateChartType('line')" id="chartTypeLineBtn"
                        class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-semibold transition hover:bg-blue-700">
                        <i class="fas fa-chart-line mr-1"></i> {{ __('Line') }}
                    </button>
                    <button onclick="updateChartType('bar')" id="chartTypeBarBtn"
                        class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition hover:bg-gray-300">
                        <i class="fas fa-chart-bar mr-1"></i> {{ __('Bar') }}
                    </button>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="relative" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Chart Stats Summary -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600">{{ __('Total Revenue') }}</p>
                    <p class="text-xl font-bold text-gray-900">Rp
                        {{ number_format(array_sum($dailyRevenue), 0, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">{{ __('Average Daily') }}</p>
                    <p class="text-xl font-bold text-blue-600">Rp
                        {{ number_format(array_sum($dailyRevenue) / count($dailyRevenue), 0, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">{{ __('Peak Day') }}</p>
                    <p class="text-xl font-bold text-green-600">Rp
                        {{ number_format(max($dailyRevenue), 0, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">{{ __('Lowest Day') }}</p>
                    <p class="text-xl font-bold text-orange-600">Rp
                        {{ number_format(min($dailyRevenue), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                let revenueChart;
                let currentChartType = 'line';

                // Chart data from Laravel
                const chartLabels = @json(array_keys($dailyRevenue));
                const chartData = @json(array_values($dailyRevenue));

                // Format dates for display
                const formattedLabels = chartLabels.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                });

                // Initialize chart
                function initChart() {
                    const ctx = document.getElementById('revenueChart').getContext('2d');

                    revenueChart = new Chart(ctx, {
                        type: currentChartType,
                        data: {
                            labels: formattedLabels,
                            datasets: [{
                                label: 'Revenue (Rp)',
                                data: chartData,
                                backgroundColor: currentChartType === 'line' ?
                                    'rgba(59, 130, 246, 0.1)' :
                                    'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: 'rgb(59, 130, 246)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    cornerRadius: 8,
                                    displayColors: false,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            if (value >= 1000000) {
                                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                            } else if (value >= 1000) {
                                                return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                            }
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }

                // Update chart type
                function updateChartType(type) {
                    currentChartType = type;

                    // Update button styles
                    document.getElementById('chartTypeLineBtn').className = type === 'line' ?
                        'px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-semibold transition' :
                        'px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition hover:bg-gray-300';

                    document.getElementById('chartTypeBarBtn').className = type === 'bar' ?
                        'px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-semibold transition' :
                        'px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition hover:bg-gray-300';

                    // Destroy and recreate chart
                    if (revenueChart) {
                        revenueChart.destroy();
                    }
                    initChart();
                }

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    initChart();
                });

                // Reinitialize chart when Livewire updates
                document.addEventListener('livewire:navigated', function() {
                    if (revenueChart) {
                        revenueChart.destroy();
                    }
                    initChart();
                });
            </script>
        @endpush
    @endif

</div>
