<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">
                Customer Name <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="edit-name"
                   wire:model="name" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('name') border-red-500 @enderror"
                   placeholder="Enter customer name"
                   required>
            @error('name') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <input type="email" 
                   id="edit-email"
                   wire:model="email" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('email') border-red-500 @enderror"
                   placeholder="customer@example.com">
            @error('email') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div>
            <label for="edit-phone" class="block text-sm font-medium text-gray-700 mb-2">
                Phone Number
            </label>
            <input type="tel" 
                   id="edit-phone"
                   wire:model="phone" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('phone') border-red-500 @enderror"
                   placeholder="+62 xxx xxxx xxxx">
            @error('phone') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Customer Type -->
        <div>
            <label for="edit-type" class="block text-sm font-medium text-gray-700 mb-2">
                Customer Type <span class="text-red-500">*</span>
            </label>
            <select id="edit-type"
                    wire:model="type" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('type') border-red-500 @enderror"
                    required>
                <option value="retail">Retail</option>
                <option value="wholesale">Wholesale</option>
                <option value="contractor">Contractor</option>
            </select>
            @error('type') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Address -->
        <div>
            <label for="edit-address" class="block text-sm font-medium text-gray-700 mb-2">
                Address
            </label>
            <textarea id="edit-address"
                      wire:model="address" 
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('address') border-red-500 @enderror"
                      placeholder="Enter customer address"></textarea>
            @error('address') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Credit Limit -->
        <div>
            <label for="edit-credit_limit" class="block text-sm font-medium text-gray-700 mb-2">
                Credit Limit (Rp) <span class="text-red-500">*</span>
            </label>
            <input type="number" 
                   id="edit-credit_limit"
                   wire:model="credit_limit" 
                   min="0"
                   step="1000"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('credit_limit') border-red-500 @enderror"
                   placeholder="0"
                   required>
            @error('credit_limit') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Maximum credit amount allowed for this customer</p>
        </div>

        <!-- Customer Stats (Read-only) -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Customer Statistics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Total Orders</p>
                    <p class="text-lg font-bold text-gray-900">{{ $customer->total_orders }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Purchases</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($customer->total_purchases, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button type="button" 
                    wire:click="$dispatch('closeModal')"
                    class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                Cancel
            </button>
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 transition shadow-sm">
                <i class="fas fa-save mr-2"></i>
                Update Customer
            </button>
        </div>
    </form>
</div>
