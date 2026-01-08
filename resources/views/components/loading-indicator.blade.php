<!-- Livewire Loading Indicator -->
<div 
    wire:loading 
    wire:target="saveSale,save,delete,update"
    class="fixed top-0 left-0 right-0 z-50"
    role="status"
    aria-live="polite"
    aria-busy="true"
>
    <div class="h-1 bg-blue-500 animate-pulse"></div>
    <span class="sr-only">Loading, please wait...</span>
</div>

<!-- Global Loading Overlay -->
<div 
    x-data="{ loading: false }"
    x-show="loading"
    x-on:start-loading.window="loading = true"
    x-on:stop-loading.window="loading = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
    style="display: none;"
    role="alert"
    aria-live="assertive"
    aria-busy="true"
>
    <div class="bg-white rounded-lg p-6 shadow-xl">
        <div class="flex items-center space-x-3">
            <i class="fas fa-spinner fa-spin text-3xl text-blue-600" aria-hidden="true"></i>
            <span class="text-lg font-semibold text-gray-900">Loading...</span>
        </div>
    </div>
</div>
