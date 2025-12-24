<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Quick Category Selection -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-tags mr-1 text-green-600"></i>Category <span class="text-red-500">*</span>
            </label>

            <!-- Recent Categories Quick Select -->
            @if ($recentCategories->count() > 0)
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach ($recentCategories as $cat)
                        <button type="button" wire:click="$set('category', '{{ $cat }}')"
                            class="px-3 py-1 text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-full transition">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            @endif

            <input type="text" wire:model.live="category" placeholder="e.g., Cement, Steel, Paint" autofocus
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
            @error('category')
                <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Product Name -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-box mr-1 text-green-600"></i>Product Name <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="name" placeholder="e.g., Cement Bag 50kg"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
            @error('name')
                <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Price and Stock (Side by Side) -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-green-600"></i>Price (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" wire:model="price" placeholder="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                @error('price')
                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-warehouse mr-1 text-green-600"></i>Initial Stock <span
                        class="text-red-500">*</span>
                </label>
                <input type="number" wire:model="current_stock" placeholder="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                @error('current_stock')
                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Unit and Supplier (Compact) -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Unit</label>
                <select wire:model="unit"
                    class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="piece">Piece</option>
                    <option value="bag">Bag</option>
                    <option value="box">Box</option>
                    <option value="meter">Meter</option>
                    <option value="kg">KG</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Supplier (Optional)</label>
                <select wire:model="supplier_id"
                    class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">None</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2 pt-3 border-t">
            <button type="submit"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition font-semibold text-sm flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add & Continue
            </button>
            <button type="button" wire:click="saveAndClose"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition font-semibold text-sm flex items-center justify-center gap-2">
                <i class="fas fa-check"></i> Add & Close
            </button>
            <button type="button" wire:click="cancel"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Keyboard Hint -->
        <p class="text-xs text-gray-500 text-center">
            <i class="fas fa-keyboard mr-1"></i>Press <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Enter</kbd> to
            add & continue
        </p>
    </form>
</div>
