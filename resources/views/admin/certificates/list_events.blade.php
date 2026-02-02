@extends('layouts.app')

@section('title', 'Kelola Sertifikat')

@section('content')
<div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Event dengan Sertifikat</h1>
    <x-link-button href="{{ route('admin.events.create') }}" variant="secondary" icon="add" size="sm">
        Buat Event Baru
    </x-link-button>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-x-auto p-4">
    <table class="w-full text-sm datatable">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Nama Event</th>
                <th class="px-4 py-3 text-center">Tanggal</th>
                <th class="px-4 py-3 text-center">Info Sertifikat</th>
                <th class="px-4 py-3 text-center">Laporan Masalah</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($events as $event)
            <tr class="border-t">
                <td class="px-4 py-3 font-medium">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100">
                            @if($event->logo)
                                <img src="{{ $event->logo_url }}" alt="{{ $event->name }}" class="w-full h-full object-contain">
                            @else
                                <span class="material-icons text-gray-300">event</span>
                            @endif
                        </div>
                        <div>
                            {{ $event->name }}
                            <div class="text-xs text-gray-400 mt-1">{{ $event->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-center">
                    <div>{{ $event->start_date->format('d M Y') }}</div>
                    @if($event->status == 'closed')
                        <span class="text-xs text-red-500 font-semibold">Selesai</span>
                    @elseif($event->status == 'active')
                        <span class="text-xs text-green-500 font-semibold">Aktif</span>
                    @else
                        <span class="text-xs text-gray-500">Draft</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex flex-col gap-1 text-xs">
                         <span class="inline-flex items-center justify-center px-2 py-0.5 rounded {{ $event->signatures_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $event->signatures_count }} Tanda Tangan
                         </span>
                         <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                            {{ $event->participant_types_count }} Tipe Peserta
                         </span>
                    </div>
                </td>
                <td class="px-4 py-3 text-center">
                    @if($event->pending_reports_count > 0)
                        <x-badge type="danger">{{ $event->pending_reports_count }} Pending</x-badge>
                    @else
                        <span class="text-green-500 text-xs flex items-center justify-center gap-1">
                            <x-icon name="check_circle" class="text-xs"/> Aman
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center space-x-2">
                    <x-link-button href="{{ route('admin.events.certificates.index', $event) }}" variant="primary" size="sm" icon="settings">
                        Kelola
                    </x-link-button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <x-icon name="workspace_premium" class="text-4xl text-gray-300 mb-2"/>
                        <p>Belum ada event yang mengaktifkan sertifikat.</p>
                        <p class="text-xs mt-1">Aktifkan fitur sertifikat saat membuat atau mengedit event.</p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
