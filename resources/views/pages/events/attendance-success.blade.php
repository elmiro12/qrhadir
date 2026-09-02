@extends('layouts.guest')
@section('title', 'Absensi Berhasil')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center mt-10">
    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <span class="material-icons text-5xl">check_circle</span>
    </div>
    
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Absensi Berhasil!</h2>
    <p class="text-gray-600 mb-6">
        Terima kasih <strong>{{ $qrToken->eventParticipant->participant->name }}</strong>, data kehadiran Anda pada acara <strong>{{ $event->name }}</strong> telah dicatat oleh sistem.
    </p>

    @if($idCardTemplate)
        <p class="text-sm text-gray-500 mb-4">
            Panitia telah menyediakan ID Card/Name Tag yang dapat Anda cetak.
        </p>
        <a href="{{ route('event.ticket', ['event' => $event->slug, 'qrToken' => $qrToken->token]) }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
            <span class="material-icons mr-2">badge</span> Lihat ID Card
        </a>
    @else
        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100 text-yellow-800 text-sm mb-6">
            <span class="material-icons inline-block align-middle mr-1 text-lg">info</span>
            Panitia belum atau tidak menyediakan template ID Card untuk dicetak pada acara ini.
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('events.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
