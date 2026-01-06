@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'type' => 'button',
    'loading' => false,
])

@php
$variantClasses = [
    'primary' => 'bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700',
    'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300',
    'success' => 'bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700',
    'danger' => 'bg-gradient-to-r from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700',
    'warning' => 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white hover:from-yellow-600 hover:to-yellow-700',
];

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2',
    'lg' => 'px-6 py-3 text-lg',
];

$classes = $variantClasses[$variant] . ' ' . $sizeClasses[$size];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$classes rounded-lg transition font-semibold flex items-center justify-center gap-2 shadow disabled:opacity-50 disabled:cursor-not-allowed"]) }}
    {{ $loading ? 'disabled' : '' }}
>
    @if($loading)
        <i class="fas fa-spinner fa-spin"></i>
    @elseif($icon)
        <i class="fas {{ $icon }}"></i>
    @endif
    
    {{ $slot }}
</button>