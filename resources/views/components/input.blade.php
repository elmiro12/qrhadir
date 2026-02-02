@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
])

<div class="space-y-2">
    @if($label)
        <label class="text-sm font-medium">
            {{ $label }} :
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-gray-300 focus:ring-orange-400 focus:border-orange-400 p-2',
            'placeholder' => $placeholder,
            'value' => $value,
        ]) }}
    />

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
