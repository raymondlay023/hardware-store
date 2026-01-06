@props([
    'label',
    'name',
    'placeholder' => '',
    'required' => false,
    'rows' => 4,
    'error' => null,
    'helpText' => null,
])

<div {{ $attributes->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea 
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm resize-y']) }}
    >{{ $slot }}</textarea>
    
    @if($error)
        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
            <i class="fas fa-exclamation-circle"></i>
            {{ $error }}
        </p>
    @elseif($helpText)
        <p class="text-xs text-gray-500 mt-1">{{ $helpText }}</p>
    @endif
</div>
