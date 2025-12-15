<div>
    <form wire:submit="save" class="space-y-5">
        <!-- Supplier and Date -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-truck mr-2 text-orange-600"></i>Supplier <span class="text-red-500">*</span>
                </label>
                <select wire:model="supplier_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
                    <option value="">Select supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <span class="text-red-600 text-sm mt-1 block"><i
                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-orange-600"></i>Date <span class="text-red-500">*</span>
                </label>
                <input type="date" wire:model="date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
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
                                        wire:click="selectProduct({{ $product['id'] }}, '{{ addslashes($product['name']) }}')"
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Cost ($)</label>
                        <input type="number" step="0.01" wire:model="unit_cost" placeholder="0.00"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm">
                    </div>
                </div>

                <button type="button" wire:click="addItem"
                    class="w-full bg-orange-100 text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-200 transition font-semibold">
                    <i class="fas fa-plus mr-2"></i> Add Item
                </button>

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
                                <th class="px-4 py-2 text-right text-sm font-semibold">Unit Cost</th>
                                <th class="px-4 py-2 text-right text-sm font-semibold">Subtotal</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $index => $item)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-2 text-center">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-2 text-right">${{ number_format($item['unit_cost'], 2) }}</td>
                                    <td class="px-4 py-2 text-right font-semibold">
                                        ${{ number_format($item['quantity'] * $item['unit_cost'], 2) }}</td>
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
                                    ${{ number_format(collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_cost']), 2) }}
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
            <button type="submit"
                class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-lg hover:from-orange-600 hover:to-orange-700 transition font-semibold flex items-center justify-center gap-2 shadow">
                <i class="fas fa-save"></i> Create Purchase Order
            </button>
            <button type="button" wire:click="cancel"
                class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-semibold flex items-center justify-center gap-2">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </form>
</div>
