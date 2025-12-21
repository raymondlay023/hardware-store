<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-8 shadow-lg">
        <h1 class="text-4xl font-bold mb-2">Welcome back, {{ $userName }}! 👋</h1>
        <p class="text-blue-100">Here's your today's sales performance</p>
        <p class="text-sm text-blue-100 mt-2">{{ today()->format('l, F d, Y') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Sales -->
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Today's Transactions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['sales_count'] }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-lg">
                    <i class="fas fa-cash-register text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Today's Revenue</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Items Sold -->
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Items Sold</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_items'] }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <i class="fas fa-box text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Avg Transaction -->
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Avg Transaction</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-orange-100 p-4 rounded-lg">
                    <i class="fas fa-chart-line text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-lightning-bolt text-yellow-500 mr-2"></i>Quick Actions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('sales.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition flex items-center gap-3">
                <i class="fas fa-plus-circle text-2xl text-blue-600"></i>
                <div>
                    <p class="font-semibold text-gray-900">Create Sale</p>
                    <p class="text-sm text-gray-600">Record new transaction</p>
                </div>
            </a>
            
            <a href="{{ route('products.index') }}" class="p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition flex items-center gap-3">
                <i class="fas fa-search text-2xl text-green-600"></i>
                <div>
                    <p class="font-semibold text-gray-900">View Products</p>
                    <p class="text-sm text-gray-600">Check stock & prices</p>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}" class="p-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition flex items-center gap-3">
                <i class="fas fa-user text-2xl text-purple-600"></i>
                <div>
                    <p class="font-semibold text-gray-900">My Profile</p>
                    <p class="text-sm text-gray-600">Update account info</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Today's Sales Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list text-blue-600 mr-2"></i>Today's Sales
            </h3>
        </div>

        @if($todaysSales->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Time</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Customer</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Items</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Amount</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($todaysSales as $sale)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $sale->created_at->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $sale->customer_name }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $sale->saleItems->sum('quantity') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        wire:click="$dispatch('view-sale-details', { id: {{ $sale->id }} })"
                                        class="text-blue-600 hover:text-blue-900 transition hover:bg-blue-50 p-2 rounded">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                <p class="text-gray-500 text-lg">No sales recorded today yet</p>
                <a href="{{ route('sales.index') }}" class="text-blue-600 hover:text-blue-900 mt-2 inline-block font-semibold">
                    Create your first sale →
                </a>
            </div>
        @endif
    </div>

    <!-- Performance Note -->
    <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-star text-2xl text-green-600"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">Great work! 🎉</h4>
                <p class="text-gray-600 text-sm mt-1">
                    @if($stats['sales_count'] === 0)
                        Start your day by creating your first sale. Good luck!
                    @else
                        You've completed {{ $stats['sales_count'] }} transactions today. Keep up the excellent work!
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
