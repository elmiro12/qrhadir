@extends('layouts.app')
@section('title', 'Template ID Card - ' . $event->name)

@section('content')
<div class="p-6">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.events.index') }}" class="p-2 bg-white rounded-full shadow-sm border hover:bg-gray-50 transition">
            <span class="material-icons">arrow_back</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Template ID Card Event</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Upload --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 flex items-center gap-2">
                    <span class="material-icons text-orange-600">upload_file</span>
                    Upload Template Baru
                </h2>

                <form action="{{ route('admin.events.id-cards.template.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desain ID Card (PNG)</label>
                        <input type="file" name="id_card_template" accept="image/png" required
                            class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 w-full">
                        <p class="text-[10px] text-gray-400 mt-2">
                            Pastikan format file PNG dengan latar belakang transparan jika diperlukan. 
                            Ukuran maksimal 3MB.
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                        <span class="material-icons">save</span>
                        Simpan Template
                    </button>
                </form>
            </div>
            
            <div class="mt-6 bg-blue-50 p-4 rounded-2xl border border-blue-100 text-sm text-blue-800 space-y-2">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <span class="material-icons text-sm">info</span>
                    Tips Desain
                </div>
                <p>Gunakan resolusi tinggi agar hasil cetak tidak pecah.</p>
                <p>Sediakan area kosong di tengah untuk penempatan QR Code.</p>
                <p>Nama event dan peserta akan otomatis diletakkan di atas dan bawah QR Code.</p>
            </div>
        </div>

        {{-- Preview --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4 flex items-center gap-2">
                    <span class="material-icons text-orange-600">preview</span>
                    Pratinjau Template Saat Ini
                </h2>

                @if($template)
                    <div class="relative bg-gray-50 rounded-2xl border border-dashed border-gray-300 p-8 flex items-center justify-center min-h-[500px]">
                        <div class="relative max-w-sm w-full shadow-2xl rounded-xl overflow-hidden bg-white">
                            <img src="{{ asset('assets/images/templates/' . $event->id . '/' . $template->file_path) }}" 
                                 class="w-full h-auto block" alt="Current Template">
                            
                            {{-- Preview Overlay --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-between p-8 pointer-events-none">
                                <div class="text-white font-bold text-center text-sm uppercase opacity-50">NAMA EVENT</div>
                                <div class="w-32 h-32 bg-white/80 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <span class="material-icons text-gray-300 text-4xl">qr_code_2</span>
                                </div>
                                <div class="space-y-1 text-center opacity-50">
                                    <div class="font-bold text-gray-800">NAMA PESERTA</div>
                                    <div class="text-orange-600 text-[10px] font-bold">KATEGORI</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-center">
                         <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Aktif</span>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-300 p-20 flex flex-col items-center justify-center text-gray-400">
                        <span class="material-icons text-6xl mb-4">image_not_supported</span>
                        <p class="font-medium">Belum ada template terupload untuk event ini.</p>
                        <p class="text-xs">Silakan upload desain menggunakan form di samping.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
