<div class="p-6">
    <x-page-header 
        title="Customers" 
        description="Manage your customer database"
        icon="fa-users">
        <x-slot name="actions">
            @can('create', App\Models\Customer::class)
                <button wire:click="$toggle('showCreateForm')"
                    class="btn-primary flex items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold text-sm">Add Customer</span>
                </button>
            @endcan
        </x-slot>
    </x-page-header>

    <!-- Create Customer Modal -->
    @if($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="create-customer-title">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4 flex justify-between items-center rounded-t-lg z-10">
                    <div>
                        <h2 id="create-customer-title" class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-user-plus"></i> Add New Customer
                        </h2>
                        <p class="text-primary-100 text-sm mt-1">Create a new customer record</p>
                    </div>
                    <button wire:click="$toggle('showCreateForm')"
                            class="text-white hover:text-gray-200 transition ml-4"
                            aria-label="Close create customer modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <livewire:customers.create-customer @customerCreated="$refresh" @closeModal="$toggle('showCreateForm')" :key="'create-customer-'.time()" />
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Customer Modal -->
    @if($showEditForm && $editingCustomerId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="edit-customer-title">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center rounded-t-lg z-10">
                    <div>
                        <h2 id="edit-customer-title" class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-user-edit"></i> Edit Customer
                        </h2>
                        <p class="text-blue-100 text-sm mt-1">Update customer information</p>
                    </div>
                    <button wire:click="$set('showEditForm', false)"
                            class="text-white hover:text-gray-200 transition ml-4"
                            aria-label="Close edit customer modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    @php
                        $editCustomer = \App\Models\Customer::find($editingCustomerId);
                    @endphp
                    @if($editCustomer)
                        <livewire:customers.edit-customer :customer="$editCustomer" @customerUpdated="$set('showEditForm', false); $refresh()" @closeModal="$set('showEditForm', false)" :key="'edit-customer-'.$editingCustomerId" />
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <x-filter-bar>
        <x-slot name="search">
            <input type="text" wire:model.live.debounce.300ms="search" 
                placeholder="Search customers by name, phone, or email..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
        </x-slot>
        <x-slot name="filters">
            <select wire:model.live="filterType" 
                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <option value="all">All Types</option>
                <option value="retail">Retail</option>
                <option value="wholesale">Wholesale</option>
                <option value="contractor">Contractor</option>
            </select>
        </x-slot>
    </x-filter-bar>

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
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <!-- Loading Skeleton -->
            <tr wire:loading.class="table-row" wire:loading.class.remove="hidden" wire:target="search,filterType" class="hidden">
                <td colspan="5" class="p-0">
                    <x-loading-skeleton type="table" :rows="10" />
                </td>
            </tr>
            
            <!-- Actual Data -->
            <tbody wire:loading.remove wire:target="search,filterType">
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
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="editCustomer({{ $customer->id }})" 
                                        class="text-primary-600 hover:text-primary-900 transition"
                                        title="Edit customer">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $customer->id }})" 
                                        class="text-red-600 hover:text-red-900 transition"
                                        title="Delete customer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
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
