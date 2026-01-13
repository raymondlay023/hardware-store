<div>
    <form wire:submit.prevent="saveAndClose" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Customer Name <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="name"
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
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <input type="email" 
                   id="email"
                   wire:model="email" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('email') border-red-500 @enderror"
                   placeholder="customer@example.com">
            @error('email') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                Phone Number
            </label>
            <input type="tel" 
                   id="phone"
                   wire:model="phone" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm @error('phone') border-red-500 @enderror"
                   placeholder="+62 xxx xxxx xxxx">
            @error('phone') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Customer Type -->
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                Customer Type <span class="text-red-500">*</span>
            </label>
            <select id="type"
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
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                Address
            </label>
            <textarea id="address"
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
            <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">
                Credit Limit (Rp) <span class="text-red-500">*</span>
            </label>
            <input type="number" 
                   id="credit_limit"
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

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button type="button" 
                    wire:click="$dispatch('closeModal')"
                    class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                Cancel
            </button>
                <x-app-button 
                    type="primary" 
                    icon="save"
                    wire:click="save">
                    {{ __('Create Customer') }}
                </x-app-button>
        </div>
    </form>
</div>
