@props([
    'icon' => 'fa-inbox',
    'title' => 'No data found',
    'description' => null,
    'variant' => 'info', // info, warning, error
])

@php
$variantClasses = [
    'info' => 'text-gray-300',
    'warning' => 'text-yellow-300',
    'error' => 'text-red-300',
];
$iconColor = $variantClasses[$variant] ?? $variantClasses['info'];
@endphp

<div {{ $attributes->merge(['class' => 'px-6 py-12 text-center']) }}>
    <i class="fas {{ $icon }} text-4xl {{ $iconColor }} mb-3 block"></i>
    <p class="text-gray-500 text-lg font-semibold mb-1">{{ $title }}</p>
    @if($description)
        <p class="text-gray-400 text-sm">{{ $description }}</p>
    @endif
    
    @if(isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
