@props([
    'title',
    'icon',
    'variant' => 'primary',
    'link' => null,
    'linkText' => 'Lihat detail'
])

@php
    $variants = [
        'primary' => [
            'bg' => 'bg-gradient-to-br from-orange-500 to-orange-700 text-white',
            'overlay' => 'bg-black/10'
        ],
        'secondary' => [
            'bg' => 'bg-gradient-to-br from-white to-red-50 text-red-700 border border-red-600',
            'overlay' => 'bg-red-100/40'
        ],
        'success' => [
            'bg' => 'bg-gradient-to-br from-green-500 to-green-700 text-white',
            'overlay' => 'bg-black/10'
        ],
        'info' => [
            'bg' => 'bg-gradient-to-br from-blue-500 to-blue-700 text-white',
            'overlay' => 'bg-black/10'
        ],
        'dark' => [
            'bg' => 'bg-gradient-to-br from-gray-800 to-gray-600 text-white',
            'overlay' => 'bg-black/20'
        ],
        'danger' => [
            'bg' => 'bg-gradient-to-br from-red-600 to-red-800 text-white',
            'overlay' => 'bg-black/10'
        ],
    ];

    $style = $variants[$variant] ?? $variants['primary'];
@endphp

<div {{ $attributes->merge([
    'class' => "relative overflow-hidden rounded-xl shadow-md transition hover:shadow-xl ". $style['bg']
]) }}>

    <div class="absolute inset-0 {{ $style['overlay'] }}"></div>

    {{-- Icon background --}}
    <div class="absolute right-3 top-3 opacity-50">
        <i class="material-icons" style="font-size:60px">{{ $icon }}</i>
    </div>

    <div class="p-5 relative z-10">
        <p class="text-sm font-medium opacity-90">{{ $title }}</p>

        <div class="mt-2 flex items-end justify-between">
            <h2 class="text-3xl font-bold">
                {{ $slot }}
            </h2>
        </div>
    </div>

    @if($link)
        <a href="{{ $link }}"
           class="flex items-center justify-between px-5 py-3 text-sm bg-black/10 hover:bg-black/20 transition">
            <span class="font-medium">Lihat Detail</span>
            <i class="material-icons text-base">arrow_forward</i>
        </a>
    @endif
</div>
