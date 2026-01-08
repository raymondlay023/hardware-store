<div>
    <!-- Page Header -->
    <x-page-header 
        title="Purchase Orders" 
        description="Track incoming inventory from suppliers"
        icon="fa-shopping-cart">
        <x-slot name="actions">
            <button 
                wire:click="$toggle('showCreateForm')"
                class="bg-gradient-to-r from-accent-500 to-accent-600 text-white px-6 py-3 rounded-lg hover:from-accent-600 hover:to-accent-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> New Purchase Order
            </button>
        </x-slot>
    </x-page-header>

    <!-- Search and Filter -->
    <x-filter-bar>
        <x-slot name="search">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search by supplier name..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent shadow-sm">
        </x-slot>
        <x-slot name="filters">
            <select wire:model.live="filterStatus" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent shadow-sm">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="received">Received</option>
            </select>
        </x-slot>
    </x-filter-bar>

    <!-- Create Form Modal -->
    @if($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="purchase-modal-title">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-accent-500 to-accent-600 px-6 py-4 flex justify-between items-center rounded-t-lg z-10">
                    <div>
                        <h2 id="purchase-modal-title" class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-shopping-cart"></i> Create Purchase Order
                        </h2>
                        <p class="text-accent-100 text-sm mt-1">Add new purchase order from supplier</p>
                    </div>
                    <button wire:click="$toggle('showCreateForm')"
                            class="text-white hover:text-gray-200 transition ml-4"
                            aria-label="Close purchase order modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
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
                    <!-- Loading Skeleton -->
                    <tr wire:loading.class="table-row" wire:loading.class.remove="hidden" wire:target="search,filterStatus" class="hidden">
                        <td colspan="7" class="p-0">
                            <x-loading-skeleton type="table" :rows="10" />
                        </td>
                    </tr>
                    
                    <!-- Actual Data -->
                </tbody>
                <tbody wire:loading.remove wire:target="search,filterStatus" class="divide-y divide-gray-200">
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
                            <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
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
                            <td colspan="7">
                                <x-empty-state 
                                    icon="fa-shopping-cart"
                                    title="No purchase orders found"
                                    description="Create your first purchase order to track incoming inventory" />
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
