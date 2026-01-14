<div x-data="{
    init() {
        window.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                @this.call('save');
            }
            // Escape to clear product selection
            if (e.key === 'Escape') {
                @this.call('clearProduct');
            }
        });
    }
}">
    <div class="space-y-5">
        <form wire:submit="save" class="space-y-5">
            <!-- Header with Quick Stats -->
            <div
                class="bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-2xl font-bold">
                            <i class="fas fa-shopping-cart mr-2"></i>Create New Sale
                        </h2>
                        <p class="text-green-100 text-sm">Quick and easy transaction entry</p>
                    </div>
                    <div class="text-right">
                        <p class="text-green-100 text-xs">{{ now()->format('H:i') }}</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Grid: Left (Products) + Right (Summary) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- LEFT COLUMN: Products & Cart (2 cols) -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Customer Info & Payment Method (Compact Top Section) -->
                    <x-app-card header-color="info" bordered class="mb-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Customer field with autocomplete -->
                            <div class="relative">
                                <x-form-input 
                                    name="customer_name"
                                    label="Customer"
                                    icon="user"
                                    placeholder="e.g., PT. Maju Jaya"
                                    wire:model.live.debounce.300ms="customer_name"
                                    class="uppercase tracking-wide" />

                                @if (!empty($customerSuggestions))
                                    <div
                                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                        @foreach ($customerSuggestions as $suggestion)
                                            <button type="button"
                                                wire:click="$set('customer_name', '{{ $suggestion }}')"
                                                class="w-full text-left px-3 py-2 hover:bg-blue-50 text-sm">
                                                {{ $suggestion }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>


                            <!-- Date - with quick select buttons -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    <i class="fas fa-calendar mr-1 text-blue-600"></i>Date
                                </label>
                                <div class="flex gap-1">
                                    <input type="date" wire:model="date"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm text-sm">
                                    <x-app-button 
                                        type="primary"
                                        size="sm"
                                        wire:click="setToday"
                                        title="Set to today">
                                        Today
                                    </x-app-button>
                                </div>
                                @error('date')
                                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Method - with icons -->
                            <div>
                                <x-form-select 
                                    name="payment_method"
                                    label="Payment"
                                    icon="credit-card"
                                    wire:model="payment_method"
                                    class="uppercase tracking-wide">
                                    <option value="cash">💵 Cash</option>
                                    <option value="card">💳 Card</option>
                                    <option value="check">✓ Check</option>
                                    <option value="transfer">📱 Transfer</option>
                                </x-form-select>
                            </div>
                        </div>
                    </x-app-card>

                    <!-- Product Search Section - ENHANCED -->
                    <x-app-card 
                        title="Add Products" 
                        icon="search"
                        header-color="success" 
                        bordered
                        class="mb-5 !overflow-visible">
                        <div class="space-y-3">
                            <!-- Search Bar with keyboard hint -->
                            <div class="relative group">
                                <input type="text" wire:model.live.debounce.300ms="productSearch"
                                    @if ($selectedProduct) disabled @endif
                                    @focus="$wire.showProductSearch = true" @keydown.enter="$wire.addItem()"
                                    placeholder="Type product name or category... (Enter to add)"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm @if ($selectedProduct) bg-green-50 @endif text-sm">

                                <!-- Search icon inside input -->
                                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>

                                @if ($selectedProduct)
                                    <button type="button" wire:click="clearProduct"
                                        class="absolute right-10 top-3 text-gray-400 hover:text-red-600 transition"
                                        title="Clear selection">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif

                                <!-- Product Suggestions - Enhanced with categories -->
                                @if (!$selectedProduct && $showProductSearch && $products)
                                    <div
                                        class="absolute top-full left-0 right-0 mt-2 bg-white border-2 border-gray-300 rounded-lg shadow-2xl z-50 max-h-80 overflow-y-auto">
                                        <div
                                            class="sticky top-0 bg-gray-50 px-4 py-2 border-b text-xs font-semibold text-gray-600">
                                            {{ count($products) }} result{{ count($products) !== 1 ? 's' : '' }} found
                                        </div>
                                        @foreach ($products as $product)
                                            <button type="button"
                                                wire:click="selectProduct({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $product['price'] }})"
                                                class="w-full text-left px-4 py-3 hover:bg-green-50 border-b last:border-b-0 transition group">
                                                <div class="flex justify-between items-start gap-3">
                                                    <div class="flex-1">
                                                        <p
                                                            class="font-semibold text-gray-900 text-sm group-hover:text-green-600">
                                                            {{ $product['name'] }}</p>
                                                        <div class="flex gap-2 mt-1">
                                                            <span
                                                                class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                                {{ $product['category'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="text-right flex-shrink-0">
                                                        <p class="font-bold text-green-600 text-sm">Rp
                                                            {{ number_format($product['price'], 0, ',', '.') }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            <i
                                                                class="fas fa-box mr-1"></i>{{ $product['current_stock'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @elseif (!$selectedProduct && $showProductSearch && strlen($productSearch) >= 1 && !$products)
                                    <div
                                        class="absolute top-full left-0 right-0 mt-2 bg-white border-2 border-gray-300 rounded-lg shadow-2xl z-50 p-4 text-center">
                                        <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                        <p class="text-gray-600 text-sm font-medium">No products found</p>
                                        <p class="text-gray-500 text-xs">Try searching with different keywords</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Quantity Control - Only when product selected -->
                            <!-- Replace the entire quantity control section with this: -->
                            @if ($selectedProduct)
                                <div
                                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-4">
                                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                        <i class="fas fa-boxes mr-1 text-green-600"></i>Quantity
                                    </label>

                                    <!-- Cleaner stepper design -->
                                    <div class="flex items-center justify-center gap-3 mb-4">
                                        <button type="button" wire:click="decrementQuantity"
                                            @if ($quantity <= 1) disabled @endif
                                            class="w-12 h-12 bg-white border-2 border-gray-300 hover:border-green-500 disabled:border-gray-200 disabled:opacity-50 text-gray-700 rounded-lg font-bold text-xl transition shadow-sm flex items-center justify-center">
                                            −
                                        </button>

                                        <div class="flex-1 max-w-[120px]">
                                            <input type="number" wire:model.live="quantity" min="1"
                                                @keydown.enter="$wire.addItem()"
                                                class="w-full text-center px-4 py-3 text-4xl font-black border-2 border-green-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white shadow-sm">
                                        </div>

                                        <button type="button" wire:click="incrementQuantity"
                                            class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold text-xl transition shadow-sm flex items-center justify-center">
                                            +
                                        </button>
                                    </div>

                                    <!-- On "Add to Cart" button -->
                                    <x-app-button 
                                        type="success" 
                                        icon="cart-plus"
                                        wire:click="addItem"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50"
                                        class="w-full">
                                        <span wire:loading.remove>Add to Cart</span>
                                        <span wire:loading>Adding...</span>
                                    </x-app-button>
                                </div>
                            @endif

                            @error('items')
                                <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                    <p class="text-red-700 text-sm"><i
                                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </x-app-card>

                    <!-- Cart Items Table - ENHANCED -->
                    @if ($items)
                        <x-app-card 
                            title="Cart Items" 
                            icon="shopping-cart"
                            header-color="purple" 
                            bordered
                            no-padding
                            class="mb-5">
                            <x-slot name="actions">
                                <span class="bg-purple-600 text-white text-xs font-bold rounded-full px-3 py-1">
                                    {{ count($items) }} items
                                </span>
                            </x-slot>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-bold text-gray-900">#</th>
                                            <th class="px-4 py-3 text-left font-bold text-gray-900">Product</th>
                                            <th class="px-4 py-3 text-center font-bold text-gray-900">Qty</th>
                                            <th class="px-4 py-3 text-right font-bold text-gray-900">Price</th>
                                            <th class="px-4 py-3 text-right font-bold text-gray-900">Subtotal</th>
                                            <th class="px-4 py-3 text-center font-bold text-gray-900">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach ($items as $index => $item)
                                            <tr class="hover:bg-gray-50 transition group">
                                                <td class="px-4 py-3 font-bold text-gray-900">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3">
                                                    <p class="font-semibold text-gray-900">{{ $item['product_name'] }}
                                                    </p>
                                                    <p class="text-xs text-gray-600">{{ $item['category'] }}</p>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="number"
                                                        wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                        value="{{ $item['quantity'] }}" min="1"
                                                        class="w-16 text-center px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-sm">
                                                </td>
                                                <td class="px-4 py-3 text-right font-medium text-gray-900">
                                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-right font-bold text-green-600">
                                                    Rp
                                                    {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button type="button"
                                                        wire:click="removeItem({{ $index }})"
                                                        class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1 rounded transition font-semibold text-sm group-hover:opacity-100 opacity-75">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Cart Summary -->
                            <div
                                class="bg-gradient-to-r from-green-50 via-blue-50 to-purple-50 px-5 py-4 border-t-2 border-gray-200">
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-600 font-semibold">TOTAL ITEMS</p>
                                        <p class="text-2xl font-black text-blue-600">
                                            {{ collect($items)->sum('quantity') }}
                                        </p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-600 font-semibold">LINE ITEMS</p>
                                        <p class="text-2xl font-black text-purple-600">
                                            {{ count($items) }}
                                        </p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-sm border-2 border-green-500">
                                        <p class="text-xs text-gray-600 font-semibold">SUBTOTAL</p>
                                        <p class="text-2xl font-black text-green-600">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </x-app-card>
                    @else
                        <div class="bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-300">
                            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-600 font-semibold mb-1">Cart is empty</p>
                            <p class="text-sm text-gray-500">Search and add products to get started</p>
                        </div>
                    @endif
                </div>

                <!-- RIGHT COLUMN: Summary, Discount & Notes (1 col) -->
                <div class="space-y-5">
                    <!-- Payment Summary Card - ENHANCED -->
                    @if ($items)
                        <div
                            class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-lg shadow-2xl p-5 space-y-4 sticky top-5">
                            <h3 class="font-bold text-lg flex items-center gap-2 uppercase tracking-wide">
                                <i class="fas fa-calculator text-yellow-400 text-xl"></i>Summary
                            </h3>

                            <!-- Breakdown -->
                            <div class="space-y-2 text-sm border-b border-gray-700 pb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Subtotal</span>
                                    <span class="font-bold text-gray-100">Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                @if ($discount_type !== 'none' && $discountAmount > 0)
                                    <div class="flex justify-between text-red-400">
                                        <span>Discount
                                            @if ($discount_type === 'percentage')
                                                ({{ $discount_value }}%)
                                            @endif
                                        </span>
                                        <span class="font-bold">- Rp
                                            {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Total -->
                            <div class="bg-gradient-to-r from-green-600 to-green-500 rounded-lg p-4 text-center">
                                <p class="text-green-100 text-xs font-bold mb-1 uppercase">TOTAL PAYMENT</p>
                                <p class="text-4xl font-black text-white">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-gray-700 rounded p-2 text-center">
                                    <p class="text-gray-400">Items</p>
                                    <p class="text-xl font-black text-blue-400">{{ collect($items)->sum('quantity') }}
                                    </p>
                                </div>
                                <div class="bg-gray-700 rounded p-2 text-center">
                                    <p class="text-gray-400">Products</p>
                                    <p class="text-xl font-black text-purple-400">{{ count($items) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Discount Section - Only show when items exist -->
                    @if ($items)
                        <x-app-card 
                            title="Discount" 
                            icon="tag"
                            header-color="warning" 
                            bordered
                            class="mb-5">
                            <div class="space-y-3">
                                <!-- Discount Type -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type</label>
                                    <select wire:model.live="discount_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 font-medium">
                                        <option value="none">None</option>
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount (Rp)</option>
                                    </select>
                                </div>

                                <!-- Discount Value -->
                                @if ($discount_type !== 'none')
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">
                                            Value
                                        </label>
                                        <input type="number" wire:model.live="discount_value" placeholder="0"
                                            min="0" @if ($discount_type === 'percentage') max="100" @endif
                                            step="0.01"
                                            class="w-full px-3 py-2 border-2 border-yellow-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 font-bold bg-yellow-50">
                                    </div>

                                    <!-- Discount Display -->
                                    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-3 text-center">
                                        <p class="text-xs text-gray-700 font-semibold mb-1">DISCOUNT</p>
                                        <p class="text-2xl font-black text-yellow-600">
                                            Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Quick Discount Buttons -->
                                <div class="grid grid-cols-4 gap-2 text-xs">
                                    <button type="button" wire:click="applyQuickDiscount(5)"
                                        class="px-2 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded font-semibold transition border border-yellow-300">
                                        5%
                                    </button>
                                    <button type="button" wire:click="applyQuickDiscount(10)"
                                        class="px-2 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded font-semibold transition border border-yellow-300">
                                        10%
                                    </button>
                                    <button type="button" wire:click="applyQuickDiscount(15)"
                                        class="px-2 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded font-semibold transition border border-yellow-300">
                                        15%
                                    </button>
                                    <button type="button" wire:click="clearDiscount"
                                        class="px-2 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded font-semibold transition border border-red-300">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </x-app-card>

                        <!-- Notes Section -->
                        
                        <x-app-card 
                            title="Notes" 
                            icon="sticky-note"
                            header-color="info" 
                            bordered>
                            <label class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wide">
                                <i class="fas fa-sticky-note text-blue-600 mr-1"></i>Notes
                            </label>
                            <textarea wire:model="notes" placeholder="Add notes... (optional)" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none shadow-sm"></textarea>
                            <p class="text-xs text-gray-600 mt-2">Max 500 characters</p>
                        </x-app-card>
                    @else
                        <!-- Empty State for Right Column -->
                        <div class="bg-blue-50 rounded-lg p-5 border-l-4 border-blue-500 text-center">
                            <i class="fas fa-info-circle text-3xl text-blue-300 mb-2 block"></i>
                            <p class="text-blue-700 font-semibold text-sm">Add items to cart</p>
                            <p class="text-blue-600 text-xs mt-1">Discount & summary will appear here</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons - Fixed at bottom -->
            @if ($items)
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 sticky bottom-0 bg-white p-5 rounded-lg shadow-lg border-t-2 border-green-500">
                    <x-app-button 
                        type="success" 
                        icon="check-circle"
                        type-attr="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        size="lg"
                        class="w-full">
                        <span wire:loading.remove>Complete Sale</span>
                        <span wire:loading>Processing...</span>
                    </x-app-button>
                    <button type="button" wire:click="cancel"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-4 rounded-lg font-bold text-lg flex items-center justify-center gap-2 shadow-lg transition transform hover:scale-105 active:scale-95">
                        <i class="fas fa-times-circle text-xl"></i> Cancel
                    </button>
                </div>
            @else
                <div class="text-center py-8 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-shopping-cart text-5xl text-gray-400 mb-3 block"></i>
                    <p class="text-gray-600 font-semibold">Ready to create a sale?</p>
                    <p class="text-gray-500 text-sm">Search and add products above to get started</p>
                </div>
            @endif
        </form>
    </div>
</div>
