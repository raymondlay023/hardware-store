<div>
    {{-- Page Header --}}
    <x-page-header 
        title="Reports & Analytics" 
        subtitle="View comprehensive business reports and insights"
    />

    {{-- Reports Grid --}}
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Sales Report Card --}}
            <a href="{{ route('reports.sales') }}" 
               class="block p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-300 transition-all duration-200"
               wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-blue-50">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Sales Reports</h3>
                <p class="text-sm text-gray-600 mb-4">Revenue tracking, top products, sales trends, and payment analysis</p>
                <div class="flex items-center text-blue-600 font-medium text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            {{-- Inventory Report Card --}}
            <a href="{{ route('reports.inventory') }}" 
               class="block p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-green-300 transition-all duration-200"
               wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-green-50">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Inventory Reports</h3>
                <p class="text-sm text-gray-600 mb-4">Stock levels, low stock alerts, movement history, and valuation</p>
                <div class="flex items-center text-green-600 font-medium text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            {{-- Customer Report Card --}}
            <a href="{{ route('reports.customers') }}" 
               class="block p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-300 transition-all duration-200"
               wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-purple-50">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Customer Reports</h3>
                <p class="text-sm text-gray-600 mb-4">Top customers, purchase frequency, credit tracking, and behavior</p>
                <div class="flex items-center text-purple-600 font-medium text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            {{-- Financial Report Card --}}
            <a href="{{ route('reports.financial') }}" 
               class="block p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-yellow-300 transition-all duration-200"
               wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg bg-yellow-50">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Financial Reports</h3>
                <p class="text-sm text-gray-600 mb-4">P&L statement, profit margins, cash flow, and expense tracking</p>
                <div class="flex items-center text-yellow-600 font-medium text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

        </div>

        {{-- Quick Stats Summary --}}
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-600">Most Accessed Report</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">Sales Reports</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-600">Available Reports</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">4 Categories</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-600">Export Options</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">CSV Format</p>
                </div>
            </div>
        </div>
    </div>
</div>
