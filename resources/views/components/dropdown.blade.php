@props([
    'id' => 'dropdown-' . uniqid(),
    'label' => 'Aksi',
    'icon' => 'more_vert',
    'variant' => 'secondary',
    'size' => 'sm'
])

@php
    $variants = [
        'primary' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300',
        'secondary' => 'text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200',
        'dark' => 'text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
    ];

    $variantClasses = $variants[$variant] ?? $variants['secondary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

<button id="{{ $id }}Button" data-dropdown-toggle="{{ $id }}" class="{{ $variantClasses }} {{ $sizeClasses }} font-medium rounded-lg text-center inline-flex items-center uppercase tracking-wider" type="button">
    @if($icon)
        <span class="material-icons text-[1.2em] mr-1.5">{{ $icon }}</span>
    @endif
    {{ $label }}
    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
    </svg>
</button>

<!-- Dropdown menu -->
<div id="{{ $id }}" class="z-10 hidden bg-white rounded-lg shadow-sm min-w-[12rem] border border-gray-100">
    <ul class="py-2 text-sm text-gray-700" aria-labelledby="{{ $id }}Button">
        {{ $slot }}
    </ul>
</div>
