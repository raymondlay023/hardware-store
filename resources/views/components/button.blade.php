@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'type' => 'button',
    'loading' => false,
])

@php
$variantClasses = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-200',
    'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-100',
    'success' => 'bg-success-600 text-white hover:bg-success-700 focus:ring-success-200',
    'danger' => 'bg-danger-600 text-white hover:bg-danger-700 focus:ring-danger-200',
    'warning' => 'bg-warning-600 text-white hover:bg-warning-700 focus:ring-warning-200',
    'outline' => 'border-2 border-primary-600 text-primary-600 hover:bg-primary-50 focus:ring-primary-200',
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
    {{ $attributes->merge(['class' => "$classes rounded-lg transition-colors duration-200 font-medium flex items-center justify-center gap-2 focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed"]) }}
    {{ $loading ? 'disabled' : '' }}
>
    @if($loading)
        <i class="fas fa-spinner fa-spin"></i>
    @elseif($icon)
        <i class="fas {{ $icon }}"></i>
    @endif
    
    {{ $slot }}
</button>