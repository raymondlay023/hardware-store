<div class="p-6 bg-white rounded-lg shadow">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Products</h1>
        <button wire:click="$toggle('showCreateForm')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Product
        </button>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Search products..."
            class="w-full px-4 py-2 border rounded border-gray-300 focus:outline-none focus:border-blue-500">
    </div>

    <!-- Create Form (toggle) -->
    @if ($showCreateForm)
        <livewire:products.create-product />
    @endif

    <!-- Products Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Category</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Unit</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Stock</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Supplier</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->category }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->unit }}</td>
                        <td class="border border-gray-300 px-4 py-2">${{ number_format($product->price, 2) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <span
                                class="px-3 py-1 rounded {{ $product->current_stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $product->current_stock }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->supplier->name ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center space-x-2">
                            <button wire:click="$dispatch('edit-product', { id: {{ $product->id }} })"
                                class="text-blue-600 hover:text-blue-800">Edit</button>
                            <button wire:click="deleteProduct({{ $product->id }})" wire:confirm="Are you sure?"
                                class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                            No products found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
    <!-- Add this after the pagination div -->
    <livewire:products.edit-product />

</div>
