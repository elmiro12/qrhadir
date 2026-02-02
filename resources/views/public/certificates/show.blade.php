@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8 rounded-lg shadow-lg">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center">
            <div>
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-2 block">&larr; Kembali ke Daftar</a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h1>
                <p class="text-gray-600">Sertifikat untuk {{ $eventParticipant->participant->name }}</p>
            </div>
            
            <div class="mt-4 md:mt-0 flex space-x-3">
                <x-button type="button" variant="secondary" onclick="showReportModal()">
                    Lapor Kesalahan
                </x-button>
                <x-link-button href="{{ route('certificates.download', ['event' => $event->slug, 'qrToken' => $qrToken]) }}" variant="info" icon="download">
                    Download .JPG
                </x-link-button>
            </div>
        </div>

        <!-- Preview Area -->
        <div class="bg-white p-2 rounded shadow-lg overflow-hidden flex justify-center items-center h-auto min-h-[400px]">
            <img src="{{ route('certificates.download', ['event' => $event->slug, 'qrToken' => $qrToken]) }}" alt="Certificate Preview" class="max-w-full h-auto shadow border transform hover:scale-[1.01] transition duration-300">
        </div>
        
    </div>
</div>

<!-- Report Modal using Component -->
<x-modal id="reportModal" title="Lapor Kesalahan Data">
    <p class="text-sm text-gray-500 mt-2 mb-4">
        Jika terdapat kesalahan penulisan (Nama, Gelar, dll), mohon jelaskan di bawah ini. Admin akan meninjau dan memperbaiki data Anda.
    </p>
    <form action="{{ route('certificates.report', ['event' => $event->slug, 'qrToken' => $qrToken]) }}" method="POST">
        @csrf
        <textarea name="message" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2" placeholder="Contoh: Nama saya seharusnya 'Budi Santoso, S.Kom'..." required></textarea>
        
        <div class="mt-6 flex justify-end space-x-3">
            <x-button type="button" variant="default" size="sm" onclick="hideReportModal()">
                Batal
            </x-button>
            <x-button type="submit" variant="danger" size="sm">
                Kirim Laporan
            </x-button>
        </div>
    </form>
</x-modal>

<script>
    // Simple JS to toggle the modal since we are not using flowbite's data-attributes fully or don't want to rely on them
    const reportModal = document.getElementById('reportModal');
    
    function showReportModal() {
        reportModal.classList.remove('hidden');
        reportModal.classList.add('flex');
    }

    function hideReportModal() {
        reportModal.classList.add('hidden');
        reportModal.classList.remove('flex');
    }
    
    // Bind close button from x-modal component
    document.querySelectorAll('[data-modal-hide="reportModal"]').forEach(btn => {
        btn.addEventListener('click', hideReportModal);
    });
</script>
@endsection
