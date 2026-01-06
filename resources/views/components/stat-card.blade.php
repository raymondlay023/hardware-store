@props([
    'title',
    'value',
    'icon' => 'fa-chart-line',
    'iconColor' => 'text-blue-600',
    'trend' => null,
    'trendUp' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow']) }}>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
            
            @if($trend)
                <div class="mt-2 flex items-center text-sm">
                    @if($trendUp)
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span class="text-green-600 font-medium">{{ $trend }}</span>
                    @else
                        <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                        <span class="text-red-600 font-medium">{{ $trend }}</span>
                    @endif
                    <span class="text-gray-500 ml-1">vs last period</span>
                </div>
            @endif
        </div>
        
        <div class="ml-4">
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                <i class="fas {{ $icon }} text-xl {{ $iconColor }}"></i>
            </div>
        </div>
    </div>
</div>