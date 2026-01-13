{{-- Navigation Dropdown Link Component --}}
@props([
    'href',
    'active' => false,
    'icon' => null,
])

<a href="{{ $href }}"
   wire:navigate
   class="flex items-center gap-3 px-4 py-2 text-sm {{ $active ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
    @if($icon)
        <i class="fas fa-{{ $icon }} w-4 text-center {{ $active ? 'text-primary-600' : 'text-gray-400' }}"></i>
    @endif
    <span>{{ $slot }}</span>
</a>
