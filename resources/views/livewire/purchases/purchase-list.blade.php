<div>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Purchase Orders</h1>
                <p class="text-gray-600">Track incoming inventory from suppliers</p>
            </div>
            <button 
                wire:click="$toggle('showCreateForm')"
                class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> New Purchase Order
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 flex gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search by supplier name..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
        </div>
        <select wire:model.live="filterStatus" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="received">Received</option>
        </select>
    </div>

    <!-- Create Form Modal -->
    @if($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Create Purchase Order</h2>
                    <button 
                        wire:click="$toggle('showCreateForm')"
                        class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <livewire:purchases.create-purchase />
                </div>
            </div>
        </div>
    @endif

    <!-- Purchases Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Order ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Supplier</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Items</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Total Amount</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">#{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-truck text-orange-500"></i>
                                    <span class="font-medium text-gray-900">{{ $purchase->supplier->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $purchase->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $purchase->purchaseItems()->count() }} item{{ $purchase->purchaseItems()->count() !== 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($purchase->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($purchase->status === 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    @if($purchase->status === 'pending')
                                        <button 
                                            wire:click="$dispatch('edit-purchase', { id: {{ $purchase->id }} })"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            wire:click="receivePurchase({{ $purchase->id }})"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded transition" title="Mark as Received">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                    <button 
                                        wire:click="deletePurchase({{ $purchase->id }})"
                                        wire:confirm="Are you sure you want to delete this purchase?"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500 text-lg">No purchase orders found</p>
                                <p class="text-gray-400">Create your first purchase order to track incoming inventory</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $purchases->links() }}
        </div>
    </div>

    <!-- Edit Purchase Modal -->
    <livewire:purchases.edit-purchase />
</div>
