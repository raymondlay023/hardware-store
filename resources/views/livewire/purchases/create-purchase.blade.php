<div>
    <form wire:submit="save" class="space-y-5">
        <!-- Supplier and Date -->
        <div class="grid grid-cols-2 gap-4">
        <div>
                <x-form-select
                    name="supplier_id"
                    label="Supplier"
                    icon="truck"
                    required
                    wire:model="supplier_id">
                    <option value="">Select supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </x-form-select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-orange-600"></i>Date <span class="text-red-500">*</span>
                </label>
                <input type="date" wire:model="date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm">
                @error('date')
                    <span class="text-red-600 text-sm mt-1 block"><i
                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Add Items Section -->
        <div class="border-t pt-5">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><i
                    class="fas fa-boxes mr-2 text-orange-600"></i>Purchase Items</h3>

            <div class="space-y-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product</label>
                    <div class="relative">
                        <input type="text" wire:model.live="productSearch"
                            @if ($selectedProduct) disabled @endif placeholder="Search product..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm @if ($selectedProduct) bg-gray-50 @endif">

                        @if (!$selectedProduct && $products)
                            <div
                                class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                                @foreach ($products as $product)
                                    <button type="button"
                                    wire:click="selectProduct({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $product['cost'] ?? 0 }})"
                                        class="block w-full text-left px-4 py-2 hover:bg-orange-50 text-gray-700 border-b last:border-b-0">
                                        {{ $product['name'] }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @if ($selectedProduct)
                            <button type="button" wire:click="clearProduct"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                        <input type="number" wire:model="quantity" placeholder="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Price (Rp)</label>
                        <input type="number" step="0.01" wire:model="unit_price" placeholder="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
                    </div>
                </div>

                <x-app-button 
                    type="warning"
                    icon="plus"
                    wire:click="addItem"
                    class="w-full">
                    Add Item
                </x-app-button>

                @error('items')
                    <span class="text-red-600 text-sm block"><i
                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>


            <!-- Items Table -->
            @if ($items)
                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Product</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold">Qty</th>
                                <th class="px-4 py-2 text-right text-sm font-semibold">Unit Price</th>
                                <th class="px-4 py-2 text-right text-sm font-semibold">Subtotal</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $index => $item)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-2 text-center">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-semibold">
                                        Rp {{ number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-semibold">
                                <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                                <td class="px-4 py-2 text-right">
                                    Rp {{ number_format(collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <x-app-button 
                type="warning"
                icon="save"
                type-attr="submit"
                class="flex-1">
                Create Purchase Order
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
