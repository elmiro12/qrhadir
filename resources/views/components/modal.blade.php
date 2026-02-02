@props([
    'id',
    'title' => null,
])

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
     class="hidden fixed inset-0 z-50 justify-center items-center w-full h-full bg-black/50">

    <div class="relative bg-white rounded-lg shadow max-w-lg w-full">

        @if($title)
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="font-semibold text-gray-800">{{ $title }}</h3>
            <button data-modal-hide="{{ $id }}">
                <x-icon name="close" />
            </button>
        </div>
        @endif

        <div class="p-6">
            {{ $slot }}
        </div>

    </div>
</div>
