@props([
    'searchPlaceholder' => 'Search...',
    'showSearch' => true,
])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-col sm:flex-row gap-4']) }}>
    @if($showSearch)
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            {{ $search ?? '' }}
        </div>
    @endif
    
    @if(isset($filters))
        <div class="flex flex-wrap gap-3">
            {{ $filters }}
        </div>
    @endif
</div>
