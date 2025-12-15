<div>
    <!-- Page Header with Stats -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Sales</h1>
                <p class="text-gray-600">Track customer sales and inventory movements</p>
            </div>
            <button 
                wire:click="$toggle('showCreateForm')"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> Record Sale
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold">Total Sales</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">${{ number_format($totalSales, 2) }}</p>
                    </div>
                    <i class="fas fa-chart-line text-4xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-semibold">Today's Sales</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">${{ number_format($todaysSales, 2) }}</p>
                    </div>
                    <i class="fas fa-calendar-check text-4xl text-green-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-semibold">Transactions</p>
                        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $totalTransactions }}</p>
                    </div>
                    <i class="fas fa-receipt text-4xl text-purple-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="mb-6 flex gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search by customer name..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm">
        </div>
        <input 
            type="date" 
            wire:model.live="dateFrom"
            class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm">
        <input 
            type="date" 
            wire:model.live="dateTo"
            class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm">
    </div>

    <!-- Create Form Modal -->
    @if($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Record Sale</h2>
                    <button 
                        wire:click="$toggle('showCreateForm')"
                        class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <livewire:sales.create-sale />
                </div>
            </div>
        </div>
    @endif

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sale ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Items</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Total Amount</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-green-500"></i>
                                    <span class="font-medium text-gray-900">{{ $sale->customer_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $sale->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $sale->saleItems()->count() }} item{{ $sale->saleItems()->count() !== 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button 
                                        wire:click="$dispatch('view-sale', { id: {{ $sale->id }} })"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        wire:click="deleteSale({{ $sale->id }})"
                                        wire:confirm="Are you sure you want to delete this sale? Stock will be restored."
                                        class="p-2 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sale Details Row (expandable) -->
                        <tr class="bg-gray-50">
                            <td colspan="6" class="px-6 py-4">
                                <div class="space-y-2">
                                    <p class="text-sm font-semibold text-gray-700">Items in this sale:</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($sale->saleItems as $item)
                                            <div class="bg-white p-3 rounded border border-gray-200">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                                                    </div>
                                                    <p class="font-semibold text-gray-900">${{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500 text-lg">No sales found</p>
                                <p class="text-gray-400">Record your first sale to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $sales->links() }}
        </div>
    </div>
</div>
