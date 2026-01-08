<div>
    {{-- Page Header --}}
    <x-page-header 
        title="Sales Report" 
        subtitle="Revenue tracking and sales analytics"
    >
        <x-slot name="actions">
            <button wire:click="exportCsv" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </button>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        {{-- Date Range Filter --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <x-date-range-picker :activeRange="$activeRange" />
        </div>

        {{-- Revenue Metrics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <x-report-card 
                label="Total Revenue"
                value="Rp {{ number_format($metrics['totalRevenue'], 0, ',', '.') }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Total Transactions"
                value="{{ number_format($metrics['totalTransactions']) }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Average Order Value"
                value="Rp {{ number_format($metrics['averageOrderValue'], 0, ',', '.') }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Total Items Sold"
                value="{{ number_format($metrics['totalItems']) }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </x-slot>
            </x-report-card>
        </div>

        {{-- Top Products & Payment Methods Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            {{-- Top Products by Revenue --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top Products by Revenue</h3>
                    <p class="text-sm text-gray-600 mt-1">Best performing products in this period</p>
                </div>
                <div class="p-6">
                    @if($topProductsByRevenue->count() > 0)
                        <div class="space-y-3">
                            @foreach($topProductsByRevenue as $index => $item)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->product->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->total_quantity }} units</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No sales data available for this period</p>
                    @endif
                </div>
            </div>

            {{-- Top Products by Quantity --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top Products by Quantity</h3>
                    <p class="text-sm text-gray-600 mt-1">Most sold products by units</p>
                </div>
                <div class="p-6">
                    @if($topProductsByQuantity->count() > 0)
                        <div class="space-y-3">
                            @foreach($topProductsByQuantity as $index => $item)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-green-600">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->product->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-500">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-900">{{ number_format($item->total_quantity) }} units</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No sales data available for this period</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Payment Methods & Customer Types --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            {{-- Payment Methods --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Sales by Payment Method</h3>
                </div>
                <div class="p-6">
                    @if($paymentMethods->count() > 0)
                        <div class="space-y-4">
                            @foreach($paymentMethods as $method)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $method->payment_method }}</span>
                                        <span class="text-sm text-gray-600">{{ $method->count }} transactions</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($method->total / $metrics['totalRevenue']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($method->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No payment data available</p>
                    @endif
                </div>
            </div>

            {{-- Customer Types --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Sales by Customer Type</h3>
                </div>
                <div class="p-6">
                    @if($customerTypes->count() > 0)
                        <div class="space-y-4">
                            @foreach($customerTypes as $type)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $type->type }}</span>
                                        <span class="text-sm text-gray-600">{{ $type->count }} orders</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($type->total / $metrics['totalRevenue']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($type->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No customer type data available</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Recent Transactions Table --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                <p class="text-sm text-gray-600 mt-1">Latest {{ $recentSales->count() }} sales in this period</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $sale->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $sale->customer?->name ?? $sale->customer_name ?? 'Walk-in' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 capitalize">
                                    {{ $sale->payment_method }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $sale->saleItems->sum('quantity') }} items
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No sales found for this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
