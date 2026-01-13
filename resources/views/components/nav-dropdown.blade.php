{{-- Navigation Dropdown Component --}}
@props([
    'label',
    'icon' => null,
    'active' => false,
])

<div x-data="{ open: false }" @click.away="open = false" class="relative">
    {{-- Dropdown Trigger --}}
    <button @click="open = !open" 
            class="hover:text-blue-100 transition flex items-center gap-2 {{ $active ? 'border-b-2 border-white pb-1' : '' }}"
            :aria-expanded="open.toString()"
            aria-haspopup="true">
        @if($icon)
            <i class="fas fa-{{ $icon }}"></i>
        @endif
        <span>{{ $label }}</span>
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
         style="display: none;">
        {{ $slot }}
    </div>
</div>
