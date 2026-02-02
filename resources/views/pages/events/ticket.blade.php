@extends('layouts.guest')

@section('title', 'Tiket - ' . $event->name)

@section('content')
<div class="w-full max-w-sm bg-white p-8 rounded-2xl shadow-xl border border-gray-100 text-center relative overflow-hidden">
    {{-- Decorative header --}}
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-amber-500"></div>

    @if($event->logo_url)
        <div class="mb-4 pt-4">
            <img src="{{ $event->logo_url }}" alt="{{ $event->name }}" class="h-16 mx-auto object-contain">
        </div>
    @endif

    <h2 class="text-2xl font-bold text-gray-800 mb-2 mt-4">TIKET PESERTA</h2>
    <p class="text-red-600 font-semibold text-lg">{{ $event->name }}</p>
    <div class="text-gray-500 text-sm mb-6">
        <x-icon name="calendar_today" class="text-gray-400"/>
        {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }} <br>
        <x-icon name="location_on" class="text-gray-400"/>
        {{ $event->location ?? 'Online' }}
    </div>

    {{-- QR Code Area --}}
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-white border-2 border-dashed border-gray-300 rounded-lg">
            {{-- Menggunakan simple-qrcode --}}
            {!! QrCode::size(200)->generate($qrToken->token) !!}
            
            <div class="text-[10px] text-gray-200 mt-2">
                {{ env('APP_URL', setting('app_name')) }}
            </div>
        </div>

    </div>

    <div class="text-left bg-gray-50 p-4 rounded-lg mb-6">
        <p class="text-xs text-gray-400 uppercase tracking-widest">Nama Peserta</p>
        <p class="font-bold text-gray-800 mb-2">{{ $qrToken->eventParticipant->participant->name }}</p>
        
        <p class="text-xs text-gray-400 uppercase tracking-widest">Tipe Peserta</p>
        <p class="font-bold text-gray-800">{{ $qrToken->eventParticipant->participantType->name }}</p>
    </div>

    <p class="text-xs text-gray-500 italic">
        Silakan simpan QR Code ini (screenshot) dan tunjukkan kepada panitia saat registrasi ulang di lokasi.
    </p>

    <div class="mt-6 space-y-3">
        @if($idCardTemplate)
            <x-link-button href="{{ route('event.id_card', [$event->slug, $qrToken->token]) }}" variant="primary" size="lg" icon="badge" class="w-full">
                Tampilkan ID Card
            </x-link-button>
        @endif

        <a href="{{ route('event.register', $event->slug) }}" class="block text-gray-500 hover:text-gray-700 text-sm font-semibold">
            &larr; Kembali ke Halaman Registrasi
        </a>
    </div>
</div>
@endsection
