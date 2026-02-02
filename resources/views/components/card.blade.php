@props([
    'title' => null,
    'footer' => null,
    'color' => 'primary', // primary, secondary, success, info, dark, danger
])

@php
    $headerColors = [
        'primary'   => 'bg-orange-500 text-white',
        'secondary' => 'bg-white text-red-600 border-b border-red-200',
        'success'   => 'bg-green-500 text-white',
        'info'      => 'bg-blue-500 text-white',
        'dark'      => 'bg-gray-800 text-white',
        'danger'    => 'bg-red-600 text-white',
    ];

    $headerClass = $headerColors[$color] ?? $headerColors['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow overflow-hidden']) }}>

    {{-- HEADER --}}
    @if($title)
        <div class="px-6 py-4 font-semibold {{ $headerClass }}">
            {{ $title }}
        </div>
    @endif

    {{-- BODY --}}
    <div class="p-6">
        {{ $slot }}
    </div>

    {{-- FOOTER --}}
    @if($footer)
        <div class="px-6 py-4 bg-gray-50 border-t text-sm text-gray-600">
            {{ $footer }}
        </div>
    @endif

</div>
