<div>
    <form wire:submit="save" class="space-y-5">
        <!-- Name -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-box mr-2 text-blue-600"></i>Product Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                wire:model="name"
                placeholder="e.g., Cement Bag 50kg"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
            @error('name') <span class="text-red-600 text-sm mt-1 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
        </div>

        <!-- Category -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-tags mr-2 text-blue-600"></i>Category <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                wire:model="category"
                placeholder="e.g., Cement, Steel, Bricks"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
            @error('category') <span class="text-red-600 text-sm mt-1 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
        </div>

        <!-- Unit -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-ruler mr-2 text-blue-600"></i>Unit <span class="text-red-500">*</span>
            </label>
            <select wire:model="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <option value="">Select a unit</option>
                <option value="bag">Bag</option>
                <option value="piece">Piece</option>
                <option value="meter">Meter</option>
                <option value="kg">KG</option>
                <option value="box">Box</option>
            </select>
            @error('unit') <span class="text-red-600 text-sm mt-1 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
        </div>

        <!-- Price -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-dollar-sign mr-2 text-blue-600"></i>Price ($) <span class="text-red-500">*</span>
            </label>
            <input 
                type="number" 
                step="0.01"
                wire:model="price"
                placeholder="0.00"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
            @error('price') <span class="text-red-600 text-sm mt-1 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
        </div>

        <!-- Supplier -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-truck mr-2 text-blue-600"></i>Supplier
            </label>
            <select wire:model="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <option value="">Select supplier (optional)</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id') <span class="text-red-600 text-sm mt-1 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button 
                type="submit"
                class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition font-semibold flex items-center justify-center gap-2 shadow">
                <i class="fas fa-save"></i> Save Product
            </button>
            <button 
                type="button"
                wire:click="cancel"
                class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-semibold flex items-center justify-center gap-2">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </form>
</div>
