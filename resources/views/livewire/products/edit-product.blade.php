<div>
    @if($product)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Edit Product</h2>
            
            <form wire:submit="save" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium mb-1">Product Name *</label>
                    <input 
                        type="text" 
                        wire:model="name"
                        placeholder="e.g., Cement Bag 50kg"
                        class="w-full px-4 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium mb-1">Category *</label>
                    <input 
                        type="text" 
                        wire:model="category"
                        placeholder="e.g., Cement, Steel, Bricks"
                        class="w-full px-4 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500">
                    @error('category') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium mb-1">Unit *</label>
                    <select wire:model="unit" class="w-full px-4 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500">
                        <option value="">Select unit</option>
                        <option value="bag">Bag</option>
                        <option value="piece">Piece</option>
                        <option value="meter">Meter</option>
                        <option value="kg">KG</option>
                        <option value="box">Box</option>
                    </select>
                    @error('unit') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium mb-1">Price ($) *</label>
                    <input 
                        type="number" 
                        step="0.01"
                        wire:model="price"
                        placeholder="0.00"
                        class="w-full px-4 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500">
                    @error('price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-medium mb-1">Supplier</label>
                    <select wire:model="supplier_id" class="w-full px-4 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500">
                        <option value="">Select supplier (optional)</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-2 mt-6">
                    <button 
                        type="submit"
                        class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Update Product
                    </button>
                    <button 
                        type="button"
                        wire:click="cancel"
                        class="flex-1 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
