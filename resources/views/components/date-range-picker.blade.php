<div class="flex flex-wrap gap-2 items-center">
    <!-- Preset Buttons -->
    <div class="flex gap-2">
        <button 
            wire:click="setDateRange('today')" 
            class="px-3 py-2 text-sm font-medium border rounded-lg transition-colors {{ $activeRange === 'today' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
            type="button">
            Today
        </button>
        <button 
            wire:click="setDateRange('week')" 
            class="px-3 py-2 text-sm font-medium border rounded-lg transition-colors {{ $activeRange === 'week' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
            type="button">
            This Week
        </button>
        <button 
            wire:click="setDateRange('month')" 
            class="px-3 py-2 text-sm font-medium border rounded-lg transition-colors {{ $activeRange === 'month' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
            type="button">
            This Month
        </button>
        <button 
            wire:click="setDateRange('year')" 
            class="px-3 py-2 text-sm font-medium border rounded-lg transition-colors {{ $activeRange === 'year' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
            type="button">
            This Year
        </button>
    </div>
    
    <!-- Custom Date Inputs -->
    <div class="flex items-center gap-2 ml-4">
        <label for="start-date" class="text-sm font-medium text-gray-700">From:</label>
        <input 
            type="date" 
            id="start-date"
            wire:model.live="startDate"
            class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
        
        <span class="text-gray-500">to</span>
        
        <label for="end-date" class="text-sm font-medium text-gray-700">To:</label>
        <input 
            type="date" 
            id="end-date"
            wire:model.live="endDate"
            class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
    </div>
</div>
