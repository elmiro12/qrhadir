<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('app_name'))</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/png" href="@cachebust(setting('app_favicon'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4 flex flex-col items-center justify-center">

    <div class="w-full max-w-5xl space-y-4">

        {{-- SECTION 1: EVENT LIST (OPTIONAL) --}}
        <div class="bg-white rounded-2xl border-t-4 border-red-600 shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
            
            {{-- KOLOM KIRI: BRAND --}}
            <div class="p-8 md:p-12 flex flex-col items-center justify-center bg-red-500 text-center border-b md:border-b-0 md:border-r border-gray-100">
                <x-app-logo size="w-32 md:w-40 mb-6"/>
                
                <h1 class="text-2xl md:text-3xl font-extrabold uppercase text-white tracking-wide">
                    {{ setting('app_name') }}
                </h1>
                <p class="mt-4 text-white font-semibold max-w-xs mx-auto text-sm leading-relaxed">
                    {{ setting('app_description') }}
                </p>
            </div>

            {{-- KOLOM KANAN: DYNAMIC CONTENT --}}
            <div class="p-8 md:p-12 bg-gray-50 flex items-center justify-center">
                <div class="w-full">
                    @yield('content')
                </div>
            </div>

        </div>

        {{-- SECTION 2: BRAND & CONTENT (2 KOLOM) --}}
        {{-- Hanya muncul jika variabel $events ada dan tidak kosong --}}
        @if(isset($events) && $events->count() > 0)
        <div id="event-list" class="bg-white rounded-2xl shadow-xl p-6 border-b-4 border-red-600">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-icons text-red-600">event_available</span>
                    Event Tersedia
                </h2>
                <span class="text-sm text-gray-400">Geser untuk melihat lainnya &rarr;</span>
            </div>

            {{-- Horizontal Scrollable List --}}
            <div class="flex overflow-x-auto gap-4 pb-4 hide-scrollbar snap-x snap-mandatory">
                @foreach($events as $event)
                <div class="min-w-[280px] md:min-w-[320px] snap-center bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-orange-400 cursor-pointer transition group flex flex-col relative overflow-hidden">
                    <a href="{{ route('event.register', $event->slug) }}" class="absolute inset-0 z-10"></a>
                    
                    <div class="flex justify-between items-start mb-2">
                         <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-0.5 rounded uppercase">Open</span>
                    </div>

                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100">
                            @if($event->logo)
                                <img src="{{ $event->logo_url }}" alt="{{ $event->name }}" class="w-full h-full object-contain">
                            @else
                                <span class="material-icons text-gray-300">event</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-red-600 line-clamp-2 leading-tight">
                            {{ $event->name }}
                        </h3>
                    </div>

                    <div class="mt-auto space-y-2 pt-3 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <x-icon name="calendar_today" class="text-gray-400"/>
                            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y, H:i') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <x-icon name="location_on" class="text-gray-400"/>
                            <span class="truncate max-w-[200px]">{{ $event->location ?? 'Online' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @include('layouts.partials.footer-guest')

    </div>

@stack('scripts')
<x-swal />
</body>
</html>
