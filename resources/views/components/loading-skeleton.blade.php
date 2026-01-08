@props([
    'type' => 'table', // table, card, list
    'rows' => 5,
])

@if($type === 'table')
    <div class="animate-pulse">
        @for($i = 0; $i < $rows; $i++)
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center space-x-4">
                    <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/6"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/6"></div>
                    <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                </div>
            </div>
        @endfor
    </div>
@elseif($type === 'card')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-pulse">
        @for($i = 0; $i < $rows; $i++)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="h-6 bg-gray-200 rounded w-3/4 mb-4"></div>
                <div class="space-y-3">
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                </div>
            </div>
        @endfor
    </div>
@elseif($type === 'list')
    <div class="space-y-3 animate-pulse">
        @for($i = 0; $i < $rows; $i++)
            <div class="flex items-center space-x-4 p-4 bg-white rounded-lg border border-gray-200">
                <div class="h-10 w-10 bg-gray-200 rounded-full"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        @endfor
    </div>
@endif
