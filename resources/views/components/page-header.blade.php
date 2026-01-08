@props([
    'title',
    'description' => null,
    'icon' => null,
])

<div class="mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex-1">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                @if($icon)
                    <i class="fas {{ $icon }} text-primary-600"></i>
                @endif
                {{ $title }}
            </h1>
            @if($description)
                <p class="text-gray-600 text-sm sm:text-base">{{ $description }}</p>
            @endif
        </div>
        
        @if(isset($actions))
            <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>

