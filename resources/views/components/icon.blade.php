@props([
    'name',
    'size' => '18px',
])

<span {{ $attributes->merge([
    'class' => "material-icons inline-flex align-middle"
]) }} style='font-size:{{ $size }}''>
    {{ $name }}
</span>
