<div>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Suppliers</h1>
                <p class="text-gray-600">Manage your suppliers and payment terms</p>
            </div>
            <button 
                wire:click="$toggle('showCreateForm')"
                class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Supplier
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6">
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search by supplier name or contact..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent shadow-sm">
        </div>
    </div>

    <!-- Create Form Modal -->
    @if($showCreateForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Add New Supplier</h2>
                    <button 
                        wire:click="$toggle('showCreateForm')"
                        class="text-white hover:text-gray-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <livewire:suppliers.create-supplier />
                </div>
            </div>
        </div>
    @endif

    <!-- Suppliers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Supplier Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Contact</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Payment Terms</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Products</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-building text-purple-500"></i>
                                    {{ $supplier->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fas fa-phone text-gray-400"></i>
                                    {{ $supplier->contact }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                    {{ $supplier->payment_terms }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="bg-blue-50 px-3 py-1 rounded inline-block">
                                    <span class="text-sm font-semibold text-blue-700">
                                        {{ $supplier->products()->count() }} product{{ $supplier->products()->count() !== 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button 
                                        wire:click="$dispatch('edit-supplier', { id: {{ $supplier->id }} })"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        wire:click="deleteSupplier({{ $supplier->id }})"
                                        wire:confirm="Are you sure you want to delete this supplier?"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500 text-lg">No suppliers found</p>
                                <p class="text-gray-400">Try adjusting your search or add a new supplier</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $suppliers->links() }}
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <livewire:suppliers.edit-supplier />
</div>
