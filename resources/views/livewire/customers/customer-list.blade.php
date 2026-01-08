<div class="p-6">
    <!-- Page Header -->
    <x-page-header 
        title="Customers" 
        description="Manage your customer database"
        icon="fa-users">
    </x-page-header>

    <!-- Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search customers by name, phone, or email..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Filter by Type -->
        <div class="sm:w-48">
            <select wire:model.live="filterType" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Types</option>
                <option value="retail">Retail</option>
                <option value="wholesale">Wholesale</option>
                <option value="contractor">Contractor</option>
            </select>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Customer
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Stats
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Credit Limit
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                            @if($customer->address)
                                <div class="text-xs text-gray-500">{{ Str::limit($customer->address, 30) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $customer->type === 'wholesale' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $customer->type === 'retail' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $customer->type === 'contractor' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst($customer->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($customer->phone)
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $customer->phone }}
                                </div>
                            @endif
                            @if($customer->email)
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>{{ $customer->email }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $customer->total_orders }} orders</div>
                            <div class="text-xs text-gray-500">Rp {{ number_format($customer->total_purchases, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                Rp {{ number_format($customer->credit_limit, 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state 
                                icon="fa-user-friends"
                                title="No customers found"
                                description="Start adding customers to track purchases" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $customers->links() }}
        </div>
    </div>
</div>
