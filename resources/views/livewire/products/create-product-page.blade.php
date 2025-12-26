<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Breadcrumb Navigation -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-600">Create Product</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            Create New Product
        </h1>
        <p class="text-gray-600">Add a new product to your inventory with complete details</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-edit"></i> Product Information
            </h2>
            <p class="text-blue-100 text-sm">Fill in all required fields marked with *</p>
        </div>

        <!-- Form Content with Better Spacing -->
        <div class="p-8">
            <livewire:products.product-form :key="'create-page-form'" />
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle"></i> Quick Tips
        </h3>
        <ul class="space-y-2 text-sm text-blue-800">
            <li class="flex items-start gap-2">
                <i class="fas fa-check text-blue-600 mt-1"></i>
                <span><strong>Low Stock Threshold:</strong> Set to 10 or more for high-demand items</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fas fa-check text-blue-600 mt-1"></i>
                <span><strong>Auto-Reorder:</strong> Enable for critical products to prevent stockouts</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fas fa-check text-blue-600 mt-1"></i>
                <span><strong>Supplier:</strong> Link products to suppliers for faster reordering</span>
            </li>
        </ul>
    </div>
</div>
