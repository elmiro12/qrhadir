@props([
    'href' => '#',
    'variant' => 'primary',     // primary, secondary, success, info, dark, danger
    'size' => 'md',             // sm | md | lg
    'icon' => null,             // material icon name
    'iconPosition' => 'left',   // left | right
    'target' => null,           // _blank | _self | null
])

@php
    // Base style
    $baseClasses = 'cusror-pointer inline-flex items-center justify-center rounded-lg font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

    // Variants
    $variants = [
        'primary'   => 'bg-orange-500 text-white hover:bg-orange-600 focus:ring-orange-400',
        'secondary' => 'bg-white text-red-600 border border-red-600 hover:bg-red-50 focus:ring-red-400',
        'success'   => 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-400',
        'info'      => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-400',
        'dark'      => 'bg-gray-800 text-white hover:bg-gray-900 focus:ring-gray-700',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    ];

    // Sizes
    $sizes = [
        'xs' => 'p-1 text-xs',
        'sm' => 'p-2 text-sm',
        'md' => 'p-3 text-base',
        'lg' => 'p-4 text-lg',
    ];

    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

<a
    href="{{ $href }}"
    @if($target) target="{{ $target }}" @endif
    @if($attributes->has('title')) title="{{ $attributes->get('title') }}" @endif
    {{ $attributes->merge([
        'class' => "$baseClasses $variantClasses $sizeClasses gap-2"
    ]) }}
>
    {{-- ICON LEFT --}}
    @if($icon && $iconPosition === 'left')
        <span class="material-icons text-[1.2em]">
            {{ $icon }}
        </span>
    @endif

    {{-- LABEL (Hidden if empty) --}}
    @if($slot->isNotEmpty())
        <span>
            {{ $slot }}
        </span>
    @endif

    {{-- ICON RIGHT --}}
    @if($icon && $iconPosition === 'right')
        <span class="material-icons text-[1.2em]">
            {{ $icon }}
        </span>
    @endif
</a>
