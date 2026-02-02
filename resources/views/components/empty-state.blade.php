@props([
    'title',
    'message' => null,
    'icon' => 'info',
])

<div class="text-center py-10 text-gray-500">
    <x-icon name="{{ $icon }}" size="text-4xl" class="mb-2" />
    <h3 class="font-semibold text-lg">{{ $title }}</h3>
    @if($message)
        <p class="text-sm mt-1">{{ $message }}</p>
    @endif
</div>
