<div>
    <x-page-header 
        title="Financial Report" 
        subtitle="Profit & Loss, margins, and cash flow analysis"
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

        {{-- P&L Summary --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-100 p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Profit & Loss Statement</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($profitLoss['revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-600">Cost of Goods Sold</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($profitLoss['cogs'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-600">Gross Profit</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($profitLoss['grossProfit'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($profitLoss['grossProfitMargin'], 2) }}% margin</p>
                </div>
            </div>
        </div>

        {{-- Cash Flow Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <x-report-card 
                label="Cash In (Sales)"
                value="Rp {{ number_format($cashFlow['totalCashIn'], 0, ',', '.') }}"
                iconBg="bg-green-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Cash Out (Purchases)"
                value="Rp {{ number_format($cashFlow['totalCashOut'], 0, ',', '.') }}"
                iconBg="bg-red-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Net Cash Flow"
                value="Rp {{ number_format($cashFlow['netCashFlow'], 0, ',', '.') }}"
                iconBg="bg-blue-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </x-slot>
            </x-report-card>
        </div>

        {{-- Top Profitable Products --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Profitable Products</h3>
                <p class="text-sm text-gray-600 mt-1">Best performers by profit margin</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Units Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($profitByProduct as $index => $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($product->units_sold) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">Rp {{ number_format($product->cost, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($product->profit, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No profit data available for this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Profit by Category --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Profit by Category</h3>
            </div>
            <div class="p-6">
                @if($profitByCategory->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($profitByCategory as $category)
                            <div class="border rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-600">{{ $category->category_name }}</p>
                                <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($category->profit, 0, ',', '.') }}</p>
                                <div class="mt-2 text-xs text-gray-500">
                                    <p>Revenue: Rp {{ number_format($category->revenue, 0, ',', '.') }}</p>
                                    <p>Cost: Rp {{ number_format($category->cost, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No category profit data available</p>
                @endif
            </div>
        </div>

    </div>
</div>
