@props([
    'type' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
    'typeAttr' => null,
])

@php
$typeClasses = match($type) {
    'primary' => 'bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white shadow-lg hover:shadow-xl',
    'success' => 'bg-gradient-to-r from-success-500 to-success-600 hover:from-success-600 hover:to-success-700 text-white shadow-lg hover:shadow-xl',
    'warning' => 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white shadow-lg hover:shadow-xl',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm hover:shadow',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-700 shadow-sm',
    'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700',
    default => 'bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white shadow-lg hover:shadow-xl',
};

$sizeClasses = match($size) {
    'sm' => 'px-3 py-2 text-xs sm:text-sm',
    'md' => 'px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base',
    'lg' => 'px-6 py-3 sm:py-4 text-base sm:text-lg',
    default => 'px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base',
};

$baseClasses = 'font-semibold rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2';
$classes = "$baseClasses $typeClasses $sizeClasses";

$tag = $href ? 'a' : 'button';
$htmlType = $typeAttr ?? ($href ? null : 'button');
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => $classes])->when(!$href, fn($attr) => $attr->merge(['type' => $htmlType])) }}>
    
    @if($icon && $iconPosition === 'left')
        <i class="fas fa-{{ $icon }}"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <i class="fas fa-{{ $icon }}"></i>
    @endif
</{{ $tag }}>

