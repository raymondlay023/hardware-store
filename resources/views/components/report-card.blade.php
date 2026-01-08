<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm p-6 border border-gray-100']) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">{{ $label }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
            
            @if(isset($change))
                <div class="flex items-center mt-2 text-sm">
                    @if($changePositive ?? true)
                        <svg class="w-4 h-4 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span class="text-green-600 font-medium">{{ $change }}</span>
                    @else
                        <svg class="w-4 h-4 text-red-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        <span class="text-red-600 font-medium">{{ $change }}</span>
                    @endif
                    <span class="text-gray-500 ml-1">{{ $changeLabel ?? 'vs last period' }}</span>
                </div>
            @endif
        </div>
        
        @if(isset($icon))
            <div class="ml-4 flex-shrink-0">
                <div class="p-3 rounded-lg {{ $iconBg ?? 'bg-blue-50' }}">
                    {!! $icon !!}
                </div>
            </div>
        @endif
    </div>
</div>
