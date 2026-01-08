<div>
    <x-page-header 
        title="Inventory Report" 
        subtitle="Stock levels, movements, and valuation analysis"
    >
        <x-slot name="actions">
            <button wire:click="exportCsv" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </button>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        {{-- Stock Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <x-report-card 
                label="Total Products"
                value="{{ number_format($summary['totalProducts']) }}"
                iconBg="bg-blue-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Low Stock Items"
                value="{{ number_format($summary['lowStockCount']) }}"
                iconBg="bg-yellow-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Critical Stock"
                value="{{ number_format($summary['criticalStockCount']) }}"
                iconBg="bg-red-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Inventory Value"
                value="Rp {{ number_format($summary['totalRetailValue'], 0, ',', '.') }}"
                iconBg="bg-green-50">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-slot>
            </x-report-card>
        </div>

        {{-- Low & Critical Stock Products --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Low Stock Products</h3>
                    <p class="text-sm text-gray-600 mt-1">Items below threshold</p>
                </div>
                <div class="p-6">
                    @if($lowStockProducts->count() > 0)
                        <div class="space-y-3">
                            @foreach($lowStockProducts as $product)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">Threshold: {{ $product->low_stock_threshold }} {{ $product->unit }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-sm font-semibold bg-yellow-200 text-yellow-800 rounded-full">
                                        {{ $product->current_stock }} {{ $product->unit }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No low stock items</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Critical Stock Products</h3>
                    <p class="text-sm text-gray-600 mt-1">Items requiring immediate attention</p>
                </div>
                <div class="p-6">
                    @if($criticalStockProducts->count() > 0)
                        <div class="space-y-3">
                            @foreach($criticalStockProducts as $product)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">Threshold: {{ $product->critical_stock_threshold }} {{ $product->unit }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-sm font-semibold bg-red-200 text-red-800 rounded-full">
                                        {{ $product->current_stock }} {{ $product->unit }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No critical stock items</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stock Movements by Type --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Stock Movements by Type</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $startDate }} to {{ $endDate }}</p>
            </div>
            <div class="p-6">
                @if($movementsByType->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach($movementsByType as $movement)
                            <div class="text-center p-4 border rounded-lg">
                                <p class="text-sm text-gray-600 capitalize">{{ $movement->type }}</p>
                                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($movement->total_quantity) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $movement->count }} transactions</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No movements in this period</p>
                @endif
            </div>
        </div>

    </div>
</div>
