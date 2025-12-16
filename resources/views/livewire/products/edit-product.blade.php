<div>
    @if ($product)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white"><i class="fas fa-edit mr-2"></i>Edit Product</h2>
                    <button wire:click="cancel" class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit="save" class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-box mr-2 text-blue-600"></i>Product Name <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="name" placeholder="e.g., Cement Bag 50kg"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                            @error('name')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tags mr-2 text-blue-600"></i>Category <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="category" placeholder="e.g., Cement, Steel, Bricks"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                            @error('category')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-ruler mr-2 text-blue-600"></i>Unit <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="unit"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                                <option value="">Select a unit</option>
                                <option value="bag">Bag</option>
                                <option value="piece">Piece</option>
                                <option value="meter">Meter</option>
                                <option value="kg">KG</option>
                                <option value="box">Box</option>
                            </select>
                            @error('unit')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-blue-600"></i>Price ($) <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" wire:model="price" placeholder="0.00"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                            @error('price')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-truck mr-2 text-blue-600"></i>Supplier
                            </label>
                            <select wire:model="supplier_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                                <option value="">Select supplier (optional)</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Low Stock Threshold -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-exclamation-circle mr-2 text-yellow-600"></i>Low Stock Threshold
                            </label>
                            <input type="number" wire:model="low_stock_threshold" placeholder="10"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Alert when stock falls below this level</p>
                            @error('low_stock_threshold')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Critical Stock Threshold -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>Critical Stock Threshold
                            </label>
                            <input type="number" wire:model="critical_stock_threshold" placeholder="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Urgent alert when stock falls below this level</p>
                            @error('critical_stock_threshold')
                                <span class="text-red-600 text-sm mt-1 block"><i
                                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Auto Reorder Settings -->
                        <div class="border-t pt-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4"><i
                                    class="fas fa-cogs mr-2 text-blue-600"></i>Auto-Reorder Settings</h3>

                            <div class="mb-4">
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" wire:model.live="auto_reorder_enabled"
                                        class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                    <span class="text-sm font-medium text-gray-700">Enable auto-reorder when stock is
                                        low</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2">When enabled, a purchase order will be
                                    automatically created when stock falls below the low threshold</p>
                            </div>

                            @if ($auto_reorder_enabled)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-shopping-cart mr-2 text-blue-600"></i>Reorder Quantity
                                    </label>
                                    <input type="number" wire:model="reorder_quantity" placeholder="e.g., 50"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                                    <p class="text-xs text-gray-500 mt-1">Quantity to order when auto-reorder is
                                        triggered</p>
                                    @error('reorder_quantity')
                                        <span class="text-red-600 text-sm mt-1 block"><i
                                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>


                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-semibold flex items-center justify-center gap-2 shadow">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <button type="button" wire:click="cancel"
                                class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-semibold flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
