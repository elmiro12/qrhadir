<div class="bg-linear-to-r from-red-500 to-red-800 shadow sticky top-0 z-50 h-14">
    <div class="max-w-4xl mx-auto px-4 h-full flex items-center justify-between">
        <div class="flex items-center gap-2">
            <x-app-logo size="w-8" />
            <span class="font-bold text-lg text-white">{{ setting('app_name') }}</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-white hover:text-orange-200 flex items-center gap-1 text-sm font-medium transition">
                <x-icon name="home" />
                Beranda
            </a>
        </div>
    </div>
</div>