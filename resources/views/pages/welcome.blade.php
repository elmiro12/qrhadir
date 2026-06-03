@extends('layouts.guest')

@section('title', 'Welcome - ' . setting('app_name'))

@section('content')
<div class="w-full mx-auto flex flex-col items-center">
    
    {{-- Hero Section --}}
    <div class="w-full text-center py-16 md:py-24 px-4 bg-gradient-to-br from-orange-400 via-orange-500 to-red-600 rounded-3xl shadow-sm border border-gray-100 mb-12">
        <x-app-logo size="w-24 md:w-32 mx-auto mb-8 animate-bounce-slow" />
        <h1 class="text-4xl md:text-5xl font-extrabold text-black tracking-tight mb-6">
            Selamat Datang di <span class="text-white">{{ setting('app_name') }}</span>
        </h1>
        <p class="text-lg text-gray-900 max-w-2xl mx-auto leading-relaxed mb-10">
            {{ setting('app_description') }}
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-700 hover:shadow-xl transition-all hover:-translate-y-1 flex items-center justify-center gap-2 text-lg">
                <span class="material-icons">person_add</span> Daftar Sekarang
            </a>
            <a href="{{ route('events.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white text-gray-800 font-bold border-2 border-gray-200 rounded-xl hover:border-red-600 hover:text-red-600 transition-all flex items-center justify-center gap-2 text-lg">
                <span class="material-icons">event_available</span> Lihat Event Aktif
            </a>
        </div>
    </div>

    {{-- Highlight / Info Section --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full px-4 mb-12">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:border-red-400 transition-colors">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <span class="material-icons text-3xl">qr_code_scanner</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Check-in Mudah</h3>
            <p class="text-gray-500 text-sm">Validasi kehadiran peserta secara instan melalui pemindaian QR Code yang cepat dan akurat.</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:border-red-400 transition-colors">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <span class="material-icons text-3xl">confirmation_number</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Manajemen Tiket</h3>
            <p class="text-gray-500 text-sm">Peserta dapat mencari dan mengunduh tiket elektronik mereka secara mandiri dengan portal yang tersedia.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:border-red-400 transition-colors">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <span class="material-icons text-3xl">workspace_premium</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">E-Sertifikat</h3>
            <p class="text-gray-500 text-sm">Distribusi sertifikat otomatis setelah acara selesai. Peserta cukup mengunduhnya melalui portal sistem.</p>
        </div>
    </div>
</div>
@endsection
