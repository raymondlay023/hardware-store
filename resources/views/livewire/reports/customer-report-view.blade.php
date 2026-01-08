<div>
    <x-page-header 
        title="Customer Report" 
        subtitle="Customer analytics and purchase behavior"
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
        
        {{-- Customer Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <x-report-card 
                label="Total Customers"
                value="{{ number_format($overview['totalCustomers']) }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="Active Customers"
                value="{{ number_format($overview['activeCustomers']) }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="New This Month"
                value="{{ number_format($overview['newCustomersThisMonth']) }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </x-slot>
            </x-report-card>

            <x-report-card 
                label="With Credit Facility"
                value="{{ number_format($creditData['totalWithCredit']) }}">
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </x-slot>
            </x-report-card>
        </div>

        {{-- Top Customers --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top Customers by Revenue</h3>
                </div>
                <div class="p-6">
                    @if($topByRevenue->count() > 0)
                        <div class="space-y-3">
                            @foreach($topByRevenue->take(10) as $index => $customer)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                            <p class="text-sm text-gray-500 capitalize">{{ $customer->type }} • {{ $customer->total_orders }} orders</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($customer->total_purchases, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No customer data available</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top Customers by Orders</h3>
                </div>
                <div class="p-6">
                    @if($topByOrders->count() > 0)
                        <div class="space-y-3">
                            @foreach($topByOrders->take(10) as $index => $customer)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-green-600">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                            <p class="text-sm text-gray-500">Rp {{ number_format($customer->total_purchases, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-900">{{ number_format($customer->total_orders) }} orders</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No customer data available</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Customer Type Distribution --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Customer Distribution by Type</h3>
            </div>
            <div class="p-6">
                @if($typeDistribution->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($typeDistribution as $type)
                            <div class="text-center p-6 border rounded-lg">
                                <p class="text-sm text-gray-600 capitalize">{{ $type->type }}</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($type->count) }}</p>
                                <p class="text-sm text-gray-600 mt-2">Total Revenue</p>
                                <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($type->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No customer type data available</p>
                @endif
            </div>
        </div>

    </div>
</div>
