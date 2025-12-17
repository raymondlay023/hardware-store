<div>
    @if ($showModal && $sale)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div
                    class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">
                        <i class="fas fa-receipt mr-2"></i>Sale Details
                    </h2>
                    <button wire:click="closeModal" class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
    
                <!-- Content -->
                <div class="p-6 space-y-6">
                    <!-- Sale Information -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Date</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $sale->date }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Customer</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $sale->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Items</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $sale->saleItems->sum('quantity') }} units
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="text-lg font-semibold text-green-600">Rp
                                    {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
    
                    <!-- Sale Items Table -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-box mr-2 text-blue-600"></i>Items Sold
                        </h3>
    
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="w-full">
                                <thead class="bg-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Product</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900">Quantity</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Unit Price</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($sale->saleItems as $item)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $item->product->category }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-900">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    {{ $item->quantity }} {{ $item->product->unit }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right text-gray-900">
                                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                                Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fas fa-inbox text-2xl mb-2 opacity-50"></i>
                                                <p>No items in this sale</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
    
                    <!-- Summary -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total Sale Amount:</span>
                            <span class="text-2xl font-bold text-green-600">Rp
                                {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
    
                    <!-- Close Button -->
                    <div class="flex gap-3">
                        <button wire:click="closeModal"
                            class="w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
