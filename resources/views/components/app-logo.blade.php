@props([
    'size' => 'w-24',
])

<img
    src="{{ asset('assets/images/logo/' . setting('app_logo')) }}"
    alt="{{ setting('app_name') }}"
    class="{{ $size }} object-contain"
/>
