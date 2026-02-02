@props([
    'href' => '#',
    'icon' => null,
    'type' => 'link', // link, button
    'variant' => 'default' // default, danger
])

@php
    $baseClasses = "cursor-pointer flex items-center w-full px-4 py-2 hover:bg-gray-100 transition-colors";
    $variantClasses = $variant === 'danger' ? 'text-red-600 hover:text-red-700' : 'text-gray-700';
@endphp

<li>
    @if($type === 'link')
        <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
            @if($icon)
                <span class="material-icons text-[1.1em] mr-3">{{ $icon }}</span>
            @endif
            <span>{{ $slot }}</span>
        </a>
    @else
        <button type="button" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
            @if($icon)
                <span class="material-icons text-[1.1em] mr-3">{{ $icon }}</span>
            @endif
            <span>{{ $slot }}</span>
        </button>
    @endif
</li>
