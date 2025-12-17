<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales Report</h1>
            <p class="text-gray-600 mt-1">Track daily sales and revenue</p>
        </div>
        <div class="flex gap-2">
            <button 
                wire:click="$dispatch('export-sales', { format: 'pdf' })"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
            <button 
                wire:click="$dispatch('export-sales', { format: 'csv' })"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Sales</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_sales'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Transactions</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-2">All transactions</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Items Sold</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_items'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Total units</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm">Avg Transaction</p>
            <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-2">Per transaction</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow space-y-4">
        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Quick Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Range</label>
                <div class="flex gap-2 flex-wrap">
                    <button 
                        wire:click="setDateRange('today')"
                        class="px-3 py-1 text-sm rounded-lg transition {{ $start_date === today()->format('Y-m-d') && $end_date === today()->format('Y-m-d') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Today
                    </button>
                    <button 
                        wire:click="setDateRange('yesterday')"
                        class="px-3 py-1 text-sm rounded-lg transition {{ $start_date === today()->subDay()->format('Y-m-d') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Yesterday
                    </button>
                    <button 
                        wire:click="setDateRange('last_7_days')"
                        class="px-3 py-1 text-sm rounded-lg transition {{ $start_date === today()->subDays(7)->format('Y-m-d') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Last 7 Days
                    </button>
                    <button 
                        wire:click="setDateRange('this_month')"
                        class="px-3 py-1 text-sm rounded-lg transition {{ $start_date === today()->startOfMonth()->format('Y-m-d') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        This Month
                    </button>
                </div>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input 
                    type="date" 
                    wire:model.live="start_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input 
                    type="date" 
                    wire:model.live="end_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <!-- Search & Reset -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Customer</label>
                <input 
                    type="text"
                    wire:model.live="search"
                    placeholder="Search by customer name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="flex items-end">
                <button 
                    wire:click="resetFilters"
                    class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $sale->date }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $sale->customer_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $sale->saleItems->sum('quantity') }} items
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-gray-900 font-semibold">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    @click="$dispatch('view-sale-details', { id: {{ $sale->id }} })"
                                    class="text-blue-600 hover:text-blue-900 transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                                <p>No sales found for the selected period</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sales->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
