<div>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Product Inventory</h1>
                <p class="text-gray-600">Manage your construction materials and supplies</p>
            </div>
            <button wire:click="$toggle('showCreateForm')"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Product
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 flex gap-6">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            <input type="text" wire:model.live="search" placeholder="Search by product name or category..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
        </div>
        <select wire:model.live="filterStockLevel"
            class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
            <option value="all">All Products</option>
            <option value="low">Low Stock</option>
            <option value="critical">Critical Stock</option>
        </select>
    </div>

    <!-- Create Form Modal -->
    @if ($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Add New Product</h2>
                    <button wire:click="$set('showCreateForm', false)"
                        class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <livewire:products.product-form :key="'create-' . time()" />
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Form Modal -->
    @if ($editingProductId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white"><i class="fas fa-edit mr-2"></i>Edit Product</h2>
                    <button wire:click="$set('editingProductId', null)"
                        class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <livewire:products.product-form :productId="$editingProductId" :key="'edit-' . $editingProductId" />
                </div>
            </div>
        </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Product Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Category</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Unit</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Price</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Stock Level</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Supplier</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ ucfirst($product->unit) }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold
                                        @if ($product->current_stock < 5) bg-red-100 text-red-800
                                        @elseif($product->current_stock < 10)
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-green-100 text-green-800 @endif">
                                        {{ $product->current_stock }} {{ $product->unit }}
                                    </span>
                                    @if ($product->current_stock < 10)
                                        <i class="fas fa-exclamation-circle text-orange-500"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->supplier->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button wire:click="editProduct({{ $product->id }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if ($product->isLowStock() && $product->auto_reorder_enabled && $product->supplier_id)
                                        <button wire:click="autoReorder({{ $product->id }})"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded transition"
                                            title="Auto Reorder">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    @endif
                                    <button wire:click="deleteProduct({{ $product->id }})"
                                        wire:confirm="Are you sure you want to delete this product?"
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
                                <p class="text-gray-500 text-lg">No products found</p>
                                <p class="text-gray-400">Try adjusting your search or add a new product</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
    </div>
</div>
