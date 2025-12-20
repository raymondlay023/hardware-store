<div>
    <!-- Page Header with Stats -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Sales</h1>
                <p class="text-gray-600">Track customer sales and inventory movements</p>
            </div>
            <a href="{{ route('sales.create') }}"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> Record Sale
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold">Total Sales</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">Rp {{ number_format($totalSales, 2) }}</p>
                    </div>
                    <i class="fas fa-chart-line text-4xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-semibold">Today's Sales</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">Rp {{ number_format($todaysSales, 2) }}</p>
                    </div>
                    <i class="fas fa-calendar-check text-4xl text-green-200"></i>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-semibold">Transactions</p>
                        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $totalTransactions }}</p>
                    </div>
                    <i class="fas fa-receipt text-4xl text-purple-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="mb-6 flex gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            <input type="text" wire:model.live="search" placeholder="Search by customer name..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm">
        </div>
        <input type="date" wire:model.live="dateFrom"
            class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm">
        <input type="date" wire:model.live="dateTo"
            class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm">
    </div>

    <!-- Create Form Modal -->
    @if ($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Record Sale</h2>
                    <button wire:click="$toggle('showCreateForm')" class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <livewire:sales.create-sale />
                </div>
            </div>
        </div>
    @endif

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sale ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Items</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Total Amount</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Make row clickable to expand -->
                            <td class="px-6 py-4 cursor-pointer" wire:click="toggleSaleDetails({{ $sale->id }})">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="fas fa-chevron-{{ $expandedSale === $sale->id ? 'down' : 'right' }} text-gray-400 text-xs"></i>
                                    <span
                                        class="font-semibold text-gray-900">#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-green-500"></i>
                                    <span class="font-medium text-gray-900">{{ $sale->customer_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $sale->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium inline-block w-fit">
                                        {{ $sale->saleItems()->count() }}
                                        item{{ $sale->saleItems()->count() !== 1 ? 's' : '' }}
                                    </span>
                                    <span
                                        class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium inline-block w-fit capitalize">
                                        <i
                                            class="fas fa-{{ $sale->payment_method === 'cash' ? 'money-bill' : 'credit-card' }} mr-1"></i>
                                        {{ $sale->payment_method }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-semibold text-gray-900">
                                        Rp
                                        {{ number_format($sale->total_amount - ($sale->discount_value ?? 0), 0, ',', '.') }}
                                    </span>
                                    @if ($sale->discount_value > 0)
                                        <span class="text-xs text-red-600 font-medium">
                                            <i class="fas fa-tag"></i> -Rp
                                            {{ number_format($sale->discount_value, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <!-- Quick QR Code -->
                                    <button wire:click="showQrCode({{ $sale->id }})"
                                        class="p-2 text-purple-600 hover:bg-purple-50 rounded transition group relative"
                                        title="Show QR Code">
                                        <i class="fas fa-qrcode"></i>
                                        <span
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                            Digital Receipt
                                        </span>
                                    </button>

                                    <!-- Receipt Options -->
                                    <a href="{{ route('sales.receipt', ['saleId' => $sale->id]) }}"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded transition group relative"
                                        title="All Receipt Options">
                                        <i class="fas fa-receipt"></i>
                                        <span
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                            Receipt Options
                                        </span>
                                    </a>

                                    <!-- Delete (Admin/Manager only) -->
                                    @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                                        <button wire:click="deleteSale({{ $sale->id }})"
                                            wire:confirm="Are you sure you want to delete this sale? Stock will be restored."
                                            class="p-2 text-red-600 hover:bg-red-50 rounded transition group relative"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                            <span
                                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                                Delete Sale
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Sale Details Row (only show if expanded) -->
                        @if ($expandedSale === $sale->id)
                            <!-- Sale Details Row (expandable) -->
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="space-y-2">
                                        <p class="text-sm font-semibold text-gray-700">Items in this sale:</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach ($sale->saleItems as $item)
                                                <div class="bg-white p-3 rounded border border-gray-200">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <p class="font-medium text-gray-900">
                                                                {{ $item->product->name }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">Qty:
                                                                {{ $item->quantity }}
                                                                ×
                                                                Rp {{ number_format($item->unit_price, 2) }}</p>
                                                        </div>
                                                        <p class="font-semibold text-gray-900">
                                                            Rp
                                                            {{ number_format($item->quantity * $item->unit_price, 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500 text-lg">No sales found</p>
                                <p class="text-gray-400">Record your first sale to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $sales->links() }}
        </div>
    </div>

    <!-- QR Code Modal -->
    @if ($selectedSaleForQr)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div
                    class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 flex justify-between items-center rounded-t-lg">
                    <h2 class="text-xl font-bold text-white">
                        <i class="fas fa-qrcode mr-2"></i>Digital Receipt
                    </h2>
                    <button wire:click="closeQrModal" class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-8 text-center">
                    <p class="text-gray-700 font-semibold mb-4">
                        Invoice #{{ str_pad($selectedSaleForQr->id, 6, '0', STR_PAD_LEFT) }}
                    </p>

                    <div class="bg-purple-50 p-6 rounded-lg inline-block">
                        {!! QrCode::size(250)->generate($selectedSaleForQr->digital_receipt_url) !!}
                    </div>

                    <p class="text-sm text-gray-600 mt-4 mb-2">Customer scans to get digital receipt</p>

                    <div class="flex gap-2 mt-4">
                        <button onclick="copyReceiptLink('{{ $selectedSaleForQr->digital_receipt_url }}')"
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-copy mr-2"></i>Copy Link
                        </button>
                        <a href="{{ $selectedSaleForQr->digital_receipt_url }}" target="_blank"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition text-center">
                            <i class="fas fa-external-link-alt mr-2"></i>Open
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function copyReceiptLink(url) {
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Receipt link copied to clipboard!');
                    });
                }
            </script>
        @endpush
    @endif

</div>
