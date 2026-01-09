<div>
    <!-- Page Header -->
    <x-page-header 
        title="Stock Movement History" 
        description="Track all inventory movements including sales, purchases, and adjustments"
        icon="fa-exchange-alt">
        <x-slot name="actions">
            <a href="{{ route('inventory.adjust') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-sliders-h"></i>
                New Adjustment
            </a>
        </x-slot>
    </x-page-header>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stock In</p>
                    <p class="text-2xl font-bold text-green-600">+{{ number_format($stats['total_in']) }}</p>
                </div>
                <i class="fas fa-arrow-down text-3xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stock Out</p>
                    <p class="text-2xl font-bold text-red-600">-{{ number_format($stats['total_out']) }}</p>
                </div>
                <i class="fas fa-arrow-up text-3xl text-red-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Movements</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['movement_count']) }}</p>
                </div>
                <i class="fas fa-exchange-alt text-3xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Product</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Product name..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Product Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                <select wire:model.live="filterProduct"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Products</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Movement Type</label>
                <select wire:model.live="filterType"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach ($movementTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" wire:model.live="dateFrom"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" wire:model.live="dateTo"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Clear Filters -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <button wire:click="clearFilters"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-semibold">
                <i class="fas fa-times mr-1"></i> Clear Filters
            </button>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($movements as $movement)
                        @php
                            $isIncoming = in_array($movement->type, ['purchase', 'adjustment_in', 'return']);
                            $typeConfig = [
                                'sale' => ['icon' => 'shopping-cart', 'color' => 'red', 'label' => 'Sale'],
                                'purchase' => ['icon' => 'truck', 'color' => 'green', 'label' => 'Purchase'],
                                'adjustment_in' => ['icon' => 'plus-circle', 'color' => 'green', 'label' => 'Adjustment In'],
                                'adjustment_out' => ['icon' => 'minus-circle', 'color' => 'red', 'label' => 'Adjustment Out'],
                                'return' => ['icon' => 'undo', 'color' => 'blue', 'label' => 'Return'],
                            ];
                            $config = $typeConfig[$movement->type] ?? ['icon' => 'question', 'color' => 'gray', 'label' => $movement->type];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm">
                                {{ $movement->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-500">{{ $movement->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $movement->product->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                    <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium {{ $isIncoming ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isIncoming ? '+' : '-' }}{{ $movement->quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if ($movement->reference_type && $movement->reference_id)
                                    {{ ucfirst($movement->reference_type) }} #{{ $movement->reference_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate" title="{{ $movement->notes }}">
                                {{ $movement->notes ?: '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $movement->user->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500">No movements found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($movements->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>
