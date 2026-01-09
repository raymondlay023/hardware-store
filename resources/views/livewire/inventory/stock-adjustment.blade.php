<div>
    <!-- Page Header -->
    <x-page-header 
        title="Stock Adjustment" 
        description="Adjust inventory levels with proper documentation"
        icon="fa-sliders-h">
        <x-slot name="actions">
            <button wire:click="openModal"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-plus"></i>
                New Adjustment
            </button>
        </x-slot>
    </x-page-header>

    <!-- Recent Adjustments Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Recent Adjustments</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($recentAdjustments as $adjustment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm">
                                {{ $adjustment->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-500">{{ $adjustment->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $adjustment->product->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($adjustment->type === 'adjustment_in')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i> Add
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-down mr-1"></i> Remove
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium">
                                {{ $adjustment->quantity }} {{ $adjustment->product->unit ?? 'pcs' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">
                                {{ $adjustment->notes }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $adjustment->user->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500">No adjustments recorded yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Adjustment Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal Panel -->
                <div class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                            Stock Adjustment
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form wire:submit="saveAdjustment">
                        <!-- Product Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                            <select wire:model.live="selectedProductId"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a product...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Stock: {{ $product->current_stock }} {{ $product->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedProductId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Current Stock Display -->
                        @if ($selectedProduct)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <strong>Current Stock:</strong> {{ $selectedProduct->current_stock }} {{ $selectedProduct->unit }}
                                </p>
                            </div>
                        @endif

                        <!-- Adjustment Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type *</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none
                                    {{ $adjustmentType === 'add' ? 'border-green-500 bg-green-50 ring-2 ring-green-500' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="adjustmentType" value="add" class="sr-only">
                                    <span class="flex flex-1 flex-col">
                                        <span class="flex items-center gap-2 text-sm font-semibold {{ $adjustmentType === 'add' ? 'text-green-700' : 'text-gray-900' }}">
                                            <i class="fas fa-plus-circle"></i> Add Stock
                                        </span>
                                        <span class="text-xs {{ $adjustmentType === 'add' ? 'text-green-600' : 'text-gray-500' }}">Increase inventory</span>
                                    </span>
                                </label>
                                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none
                                    {{ $adjustmentType === 'remove' ? 'border-red-500 bg-red-50 ring-2 ring-red-500' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="adjustmentType" value="remove" class="sr-only">
                                    <span class="flex flex-1 flex-col">
                                        <span class="flex items-center gap-2 text-sm font-semibold {{ $adjustmentType === 'remove' ? 'text-red-700' : 'text-gray-900' }}">
                                            <i class="fas fa-minus-circle"></i> Remove Stock
                                        </span>
                                        <span class="text-xs {{ $adjustmentType === 'remove' ? 'text-red-600' : 'text-gray-500' }}">Decrease inventory</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                            <input type="number" wire:model="quantity" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason *</label>
                            <select wire:model="reason"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="correction">Stock Correction</option>
                                <option value="damage">Damaged Goods</option>
                                <option value="loss">Lost/Missing</option>
                                <option value="found">Found Stock</option>
                                <option value="return">Customer Return</option>
                                <option value="expiry">Expired Product</option>
                                <option value="transfer">Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea wire:model="notes" rows="2"
                                placeholder="Add any additional notes..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <!-- Preview -->
                        @if ($selectedProduct && $quantity)
                            <div class="mb-6 p-4 rounded-lg {{ $adjustmentType === 'add' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <p class="text-sm font-medium {{ $adjustmentType === 'add' ? 'text-green-800' : 'text-red-800' }}">
                                    <strong>Result:</strong> 
                                    {{ $selectedProduct->current_stock }} 
                                    {{ $adjustmentType === 'add' ? '+' : '-' }} 
                                    {{ $quantity }} = 
                                    <span class="text-lg">
                                        {{ $adjustmentType === 'add' ? $selectedProduct->current_stock + $quantity : $selectedProduct->current_stock - $quantity }}
                                    </span>
                                    {{ $selectedProduct->unit }}
                                </p>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-semibold">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 {{ $adjustmentType === 'add' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-lg transition font-semibold">
                                <i class="fas fa-check mr-1"></i> Confirm Adjustment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
