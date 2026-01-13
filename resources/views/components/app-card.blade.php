@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'headerColor' => 'gray',
    'bordered' => false,
    'noPadding' => false,
])

@php
$headerClasses = match($headerColor) {
    'primary' => 'bg-gradient-to-r from-primary-50 to-primary-100 border-b border-primary-200',
    'success' => 'bg-gradient-to-r from-success-50 to-success-100 border-b border-success-200',
    'warning' => 'bg-gradient-to-r from-yellow-50 to-orange-100 border-b border-yellow-200',
    'danger' => 'bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200',
    'info' => 'bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200',
    'purple' => 'bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200',
    'gray' => 'bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200',
    default => 'bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200',
};

$borderClass = $bordered ? 'border-l-4' : '';
$borderColorClass = match($headerColor) {
    'primary' => 'border-primary-500',
    'success' => 'border-success-500',
    'warning' => 'border-yellow-500',
    'danger' => 'border-red-500',
    'info' => 'border-blue-500',
    'purple' => 'border-purple-500',
    'gray' => 'border-gray-500',
    default => '',
};

$contentPadding = $noPadding ? '' : 'p-4 sm:p-6';
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-lg shadow-sm overflow-hidden $borderClass $borderColorClass"]) }}>
    @if($title || isset($header))
        <div class="px-4 sm:px-6 py-3 sm:py-4 {{ $headerClasses }}">
            @if(isset($header))
                {{ $header }}
            @else
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($icon)
                            <i class="fas fa-{{ $icon }} text-lg"></i>
                        @endif
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $title }}</h3>
                            @if($description)
                                <p class="text-xs sm:text-sm text-gray-600 mt-0.5">{{ $description }}</p>
                            @endif
                        </div>
                    </div>
                    @if(isset($actions))
                        <div class="flex items-center gap-2">
                            {{ $actions }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
    
    <div class="{{ $contentPadding }}">
        {{ $slot }}
    </div>
</div>
