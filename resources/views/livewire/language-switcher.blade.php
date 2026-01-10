<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
        <span class="text-lg">{{ $locales[$currentLocale]['flag'] }}</span>
        <span class="hidden sm:inline">{{ $locales[$currentLocale]['name'] }}</span>
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-40 origin-top-right bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="py-1">
            @foreach($locales as $code => $locale)
                <button wire:click="switchLanguage('{{ $code }}')"
                        class="flex items-center w-full gap-3 px-4 py-2 text-sm text-left {{ $currentLocale === $code ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="text-lg">{{ $locale['flag'] }}</span>
                    <span>{{ $locale['name'] }}</span>
                    @if($currentLocale === $code)
                        <svg class="w-4 h-4 ml-auto text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
