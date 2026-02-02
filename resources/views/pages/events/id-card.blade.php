@extends('layouts.guest')

@section('title', 'ID Card - ' . $event->name)

@section('content')
<div class="w-full max-w-md mx-auto">
    {{-- Container ID Card --}}
    <div id="id-card-wrap" class="p-4 bg-gray-50 rounded-2xl mb-4 border border-dashed border-gray-300">
        {{-- Gambar hasil generate dari server --}}
        <div class="relative w-full shadow-2xl rounded-xl overflow-hidden bg-white min-h-[400px] flex items-center justify-center">
            @if($isGenerated)
                <img src="{{ asset($filePath) }}?v={{ filemtime(public_path($filePath)) }}" 
                     alt="ID Card {{ $qrToken->eventParticipant->participant->name }}"
                     class="w-full h-auto block">
            @else
                <div class="text-center p-8">
                    <span class="material-icons text-6xl text-gray-300 mb-4">image_not_supported</span>
                    <p class="text-gray-500 font-medium">ID Card belum tersedia</p>
                    <p class="text-gray-400 text-sm">Silakan generate ID Card terlebih dahulu</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-4 flex flex-col gap-3 px-4">
        @if($isGenerated)
            <a href="{{ asset($filePath) }}" 
               download="ID_Card_{{ Str::slug($qrToken->eventParticipant->participant->name) }}.png"
               class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <span class="material-icons">download</span>
                Unduh Gambar (PNG)
            </a>
        @else
            <button onclick="generateSingle()" id="btnGenerateSingle"
               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <span class="material-icons">sync</span>
                Generate ID Card
            </button>
        @endif

        <div class="flex gap-3">
            <x-link-button href="{{ route('event.ticket', [$event->slug, $qrToken->token]) }}" 
               variant="secondary" size="lg" icon="receipt" class="flex-1">
                Mode Tiket
            </x-link-button>
            @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.events.participants.index', $event->id) }}" 
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-500 font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <span class="material-icons">person</span>
                    Lihat Data
                </a>
            @else
            <a href="{{ route('event.register', $event->slug) }}" 
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-500 font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                <span class="material-icons">receipt</span>
                Form Pendaftaran
            </a>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #id-card-wrap, #id-card-wrap img {
            visibility: visible;
        }
        #id-card-wrap {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 500px;
            padding: 0;
            border: none;
            background: none;
        }
        main { background: white !important; }
    }
</style>
@push('scripts')
<script>
    async function generateSingle() {
        const btn = document.getElementById('btnGenerateSingle');
        const originalContent = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="material-icons animate-spin">sync</span> Memproses...';

        try {
            const response = await fetch("{{ route('event.id_card_generate', [$event->slug, $qrToken->token]) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message
                });
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan sistem.'
            });
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    }
</script>
@endpush
@endsection
