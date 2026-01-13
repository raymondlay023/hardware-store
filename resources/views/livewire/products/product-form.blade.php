<div>
    <form wire:submit="save" class="space-y-5">
        <!-- Name -->
        <div>
            <x-form-input 
                name="name"
                label="Product Name"
                icon="box"
                placeholder="e.g., Cement Bag 50kg"
                required
                wire:model="name" />
        </div>

        <!-- Brand -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-certificate mr-2 text-blue-600"></i>Brand
            </label>
            <input type="text" wire:model="brand" placeholder="e.g., Avian, Tiga Roda, Nippon Paint"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
            <p class="text-xs text-gray-500 mt-1">Optional: Enter the manufacturer or brand name</p>
            @error('brand')
                <span class="text-red-600 text-sm mt-1 block"><i
                        class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
            @enderror
        </div>

        <!-- Category -->
        <div>
            <x-form-input 
                name="category"
                label="Category"
                icon="tags"
                placeholder="e.g., Cement, Steel, Bricks"
                required
                wire:model="category" />
        </div>

        <!-- Unit -->
        <div>
            <x-form-select 
                name="unit"
                label="Unit"
                icon="ruler"
                required
                wire:model="unit">
                <option value="">Select a unit</option>
                <option value="bag">Bag</option>
                <option value="piece">Piece</option>
                <option value="meter">Meter</option>
                <option value="kg">KG</option>
                <option value="box">Box</option>
            </x-form-select>
        </div>

        <!-- Price -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-money-bill mr-2 text-blue-600"></i>Price (Rp) <span class="text-red-500">*</span>
            </label>
            <input type="number" step="0.01" wire:model.blur="price" placeholder="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm
        @error('price') border-red-500 @enderror">
            @error('price')
                <span class="text-red-600 text-sm mt-1 block flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                </span>
            @else
                <p class="text-xs text-gray-500 mt-1">Selling price to customers</p>
            @enderror
        </div>

        <!-- Cost -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Cost (Rp)
            </label>
            <input type="number" step="0.01" wire:model.blur="cost" placeholder="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm
        @error('cost') border-red-500 @enderror">
            @error('cost')
                <span class="text-red-600 text-sm mt-1 block flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                </span>
            @else
                <p class="text-xs text-gray-500 mt-1">Purchase cost from supplier (for profit calculations)</p>
            @enderror
        </div>

        <!-- Markup Percentage -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-percentage mr-2 text-purple-600"></i>Markup Percentage (%)
            </label>
            <input type="number" step="0.01" wire:model.blur="markup_percentage" placeholder="e.g., 20"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent shadow-sm
        @error('markup_percentage') border-red-500 @enderror">
            @error('markup_percentage')
                <span class="text-red-600 text-sm mt-1 block flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                </span>
            @else
                <p class="text-xs text-gray-500 mt-1">Optional: markup percentage over cost (helps with automatic pricing)</p>
            @enderror
        </div>

        <!-- Supplier -->
        <div>
            <x-form-select 
                name="supplier_id"
                label="Supplier"
                icon="truck"
                wire:model="supplier_id">
                <option value="">Select supplier (optional)</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </x-form-select>
        </div>

        <!-- Product Aliases Section -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-lg border-2 border-blue-200">
            <div class="mb-4">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-search text-blue-600"></i>
                    Alternative Names / Search Terms
                </h3>
                <p class="text-xs text-gray-600 mt-1">
                    Add common names customers use: Indonesian terms, English terms, brand names, local slang, etc.
                </p>
            </div>

            <div class="space-y-3">
                @foreach ($aliases as $index => $alias)
                    <div class="flex gap-2" wire:key="alias-{{ $index }}">
                        <div class="flex-1">
                            <input type="text" wire:model="aliases.{{ $index }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm bg-white"
                                placeholder="e.g., Semen, Cement, Portland cement">
                            @error('aliases.' . $index)
                                <span class="text-red-600 text-xs mt-1 block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="button" wire:click="removeAlias({{ $index }})"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold shadow-sm"
                            title="Remove this alias">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>

            <x-app-button 
                type="primary" 
                icon="plus"
                wire:click="addAlias"
                size="sm"
                class="mt-3 w-full">
                Add
            </x-app-button>

            <!-- Tips -->
            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <p class="text-xs font-semibold text-yellow-800 mb-1 flex items-center gap-1">
                    <i class="fas fa-lightbulb"></i>
                    Tips:
                </p>
                <ul class="text-xs text-yellow-700 space-y-1 ml-5 list-disc">
                    <li>Add both Indonesian & English: "Paku" and "Nail"</li>
                    <li>Include brand names: "Gypsum" and "Kalsi board"</li>
                    <li>Add common misspellings or regional terms</li>
                </ul>
            </div>
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
                    <span class="text-sm font-medium text-gray-700">Enable auto-reorder when stock is low</span>
                </label>
                <p class="text-xs text-gray-500 mt-2">When enabled, a purchase order will be automatically created when
                    stock falls below the low threshold</p>
            </div>

            @if ($auto_reorder_enabled)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-shopping-cart mr-2 text-blue-600"></i>Reorder Quantity
                    </label>
                    <input type="number" wire:model="reorder_quantity" placeholder="e.g., 50"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">Quantity to order when auto-reorder is triggered</p>
                    @error('reorder_quantity')
                        <span class="text-red-600 text-sm mt-1 block"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>
            @endif
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <x-app-button 
                type="primary" 
                icon="save"
                wire:click="save"
                class="flex-1">
                {{ $isEditing ? 'Update Product' : 'Save Product' }}
            </x-app-button>
            <x-app-button 
                type="secondary"
                icon="times"
                wire:click="cancel"
                class="flex-1">
                Cancel
            </x-app-button>
        </div>
    </form>
</div>
