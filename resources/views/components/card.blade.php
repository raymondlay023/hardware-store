@props(['title' => '', 'actions' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden']) }}>
    @if($title || $actions)
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            @if($title)
                <h3 class="text-xl font-semibold text-gray-800">
                    {{ $title }}
                </h3>
            @endif
            
            @if($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
