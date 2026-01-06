@props([
    'label',
    'name',
    'placeholder' => 'Select an option',
    'required' => false,
    'icon' => null,
    'error' => null,
    'options' => [],
])

<div {{ $attributes->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
            @if($icon)
                <i class="fas {{ $icon }} mr-2 text-blue-600"></i>
            @endif
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm']) }}
    >
        <option value="">{{ $placeholder }}</option>
        {{ $slot }}
    </select>
    
    @if($error)
        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
            <i class="fas fa-exclamation-circle"></i>
            {{ $error }}
        </p>
    @endif
</div>