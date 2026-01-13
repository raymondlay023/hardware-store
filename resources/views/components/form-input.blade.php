@props([
    'label' => null,
    'name',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'icon' => null,
    'error' => null,
    'helpText' => null,
    'wire:model' => null,
])

<div {{ $attributes->except(['wire:model', 'wire:model.live', 'wire:model.defer'])->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
            @if($icon)
                <i class="fas fa-{{ $icon }} mr-1 text-primary-600"></i>
            @endif
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->only(['wire:model', 'wire:model.live', 'wire:model.defer', 'wire:model.live.debounce.300ms']) }}
        {{ $attributes->except(['class', 'label', 'name', 'type', 'placeholder', 'required', 'icon', 'error', 'helpText'])->merge([
            'class' => 'w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm text-sm transition-all duration-200 ' . ($error ? 'border-red-300 bg-red-50' : 'hover:border-gray-400')
        ]) }}
    >
    
    @if($error)
        <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
            <i class="fas fa-exclamation-circle"></i>
            {{ $error }}
        </p>
    @elseif($helpText)
        <p class="text-xs text-gray-500 mt-1">{{ $helpText }}</p>
    @endif
</div>
