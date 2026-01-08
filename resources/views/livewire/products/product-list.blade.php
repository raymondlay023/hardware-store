<div>
    <!-- Page Header -->
    <x-page-header 
        title="Product Inventory" 
        description="Manage your construction materials and supplies"
        icon="fa-box">
        <x-slot name="actions">
            @can('create', App\Models\Product::class)
                <button wire:click="$toggle('showQuickAdd')"
                    class="bg-gradient-to-r from-success-500 to-success-600 text-white px-6 py-3 rounded-lg hover:from-success-600 hover:to-success-700 transition shadow-lg flex items-center gap-2 border-2 border-success-400">
                    <i class="fas fa-bolt"></i> Quick Add
                </button>
            @endcan

            @if(auth()->user()->hasPermission('products.import'))
                <button wire:click="$toggle('showBulkImport')"
                    class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-5 py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-file-import"></i>
                    <span class="font-semibold text-sm">Bulk Import</span>
                </button>
            @endif

            @can('create', App\Models\Product::class)
                <a href="{{ route('products.create') }}"
                    class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-5 py-3 rounded-lg hover:from-primary-600 hover:to-primary-700 transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span class="font-semibold text-sm">New Product</span>
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <!-- Search and Filter -->
    <x-filter-bar>
        <x-slot name="search">
            <input type="text" wire:model.live="search" placeholder="Search by product name or category..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm">
        </x-slot>
        <x-slot name="filters">
            <select wire:model.live="filterStockLevel"
                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm">
                <option value="all">All Products</option>
                <option value="low">Low Stock</option>
                <option value="critical">Critical Stock</option>
            </select>
        </x-slot>
    </x-filter-bar>

    <!-- Quick Add Modal -->
    @if ($showQuickAdd)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
                <div
                    class="sticky top-0 bg-gradient-to-r from-green-500 to-green-600 px-6 py-3 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-bolt"></i> Quick Add Product
                        </h2>
                        <p class="text-green-100 text-xs">Fast entry • Essential fields only</p>
                    </div>
                    <button wire:click="$set('showQuickAdd', false)" class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <livewire:products.quick-add-product :key="'quick-add-' . time()" />
                </div>
            </div>
        </div>
    @endif

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

    <!-- Bulk Import Modal -->
    @if ($showBulkImport)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-file-import"></i> Bulk Import Products
                        </h2>
                        <p class="text-purple-100 text-sm">Import multiple products from CSV file</p>
                    </div>
                    <button wire:click="$set('showBulkImport', false)"
                        class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <livewire:products.bulk-import :key="'bulk-import-' . time()" />
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
                                <div>
                                    <!-- Product Name with Brand -->
                                    <div class="font-medium text-gray-900">
                                        {{ $product->name }}
                                        @if ($product->brand)
                                            <span class="ml-2 text-sm font-normal text-gray-600">
                                                <i class="fas fa-certificate text-blue-400"></i>
                                                {{ $product->brand }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($product->aliases && $product->aliases->count() > 0)
                                        <div class="text-xs text-gray-500 mt-1 flex items-start gap-1 group relative">
                                            <i class="fas fa-tag text-blue-400 mt-0.5 flex-shrink-0"></i>
                                            <span class="line-clamp-1 cursor-help">
                                                {{ $product->aliases->take(3)->pluck('alias')->implode(', ') }}
                                                @if ($product->aliases->count() > 3)
                                                    <span
                                                        class="text-blue-600 font-medium">+{{ $product->aliases->count() - 3 }}
                                                        more</span>
                                                @endif
                                            </span>

                                            <!-- Tooltip with all aliases -->
                                            @if ($product->aliases->count() > 3)
                                                <div
                                                    class="hidden group-hover:block absolute left-0 top-6 bg-gray-800 text-white text-xs rounded-lg py-2 px-3 z-10 shadow-lg w-64">
                                                    <p class="font-semibold mb-1">All alternative names:</p>
                                                    <p>{{ $product->aliases->pluck('alias')->implode(', ') }}</p>
                                                    <div
                                                        class="absolute -top-1 left-4 w-2 h-2 bg-gray-800 transform rotate-45">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400 mt-1 italic">
                                            <i class="fas fa-info-circle"></i> No aliases
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ ucfirst($product->unit) }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @php
                                        $isCritical = $product->current_stock < $product->critical_stock_threshold;
                                        $isLow =
                                            $product->current_stock < $product->low_stock_threshold && !$isCritical;
                                        $isGood = !$isCritical && !$isLow;
                                    @endphp

                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1
                                                @if ($isCritical) bg-red-100 text-red-800
                                                @elseif($isLow) bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">

                                        @if ($isCritical)
                                            <i class="fas fa-exclamation-triangle"></i>
                                        @elseif($isLow)
                                            <i class="fas fa-exclamation-circle"></i>
                                        @else
                                            <i class="fas fa-check-circle"></i>
                                        @endif

                                        {{ $product->current_stock }} {{ $product->unit }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->supplier->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    @can('update', $product)
                                        <button wire:click="editProduct({{ $product->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endcan
                                    
                                    @if ($product->isLowStock() && $product->auto_reorder_enabled && $product->supplier_id)
                                        <button wire:click="autoReorder({{ $product->id }})"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded transition"
                                            title="Auto Reorder">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    @endif
                                    
                                    @can('delete', $product)
                                        <button wire:click="deleteProduct({{ $product->id }})"
                                            wire:confirm="Are you sure you want to delete this product?"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state 
                                    icon="fa-box-open"
                                    title="No products found"
                                    description="Try adjusting your search or add a new product" />
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
