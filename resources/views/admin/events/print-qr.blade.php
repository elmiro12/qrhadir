<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Event: {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .print-area { box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen py-10">

    <div class="no-print mb-6 flex gap-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 font-bold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak QR
        </button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-6 py-2 rounded shadow hover:bg-gray-600">
            Tutup
        </button>
    </div>

    <div class="print-area bg-white p-12 rounded-xl shadow-lg text-center max-w-lg w-full border border-gray-200">
        
        @if($event->logo_url)
            <div class="mb-6">
                <img src="{{ $event->logo_url }}" alt="{{ $event->name }}" class="h-20 mx-auto object-contain">
            </div>
        @endif

        <h1 class="text-3xl font-extrabold text-gray-800 mb-2 uppercase tracking-wide">{{ setting('app_name') }}</h1>
        <p class="text-gray-500 mb-8 border-b pb-4">Scan QR Code untuk Registrasi Event</p>

        <h2 class="text-2xl font-bold text-black mb-6 leading-tight">{{ $event->name }}</h2>

        <div class="flex justify-center mb-8">
            {!! QrCode::size(300)->generate(eventRegisterUrl($event)) !!}
        </div>

        <div class="text-left bg-gray-50 p-6 rounded-lg border border-gray-100">
            <p class="mb-2"><strong class="text-gray-700">Waktu:</strong> <br> {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('l, d F Y - H:i') }}</p>
            <p><strong class="text-gray-700">Lokasi:</strong> <br> {{ $event->location ?? 'Online' }}</p>
        </div>

        <div class="mt-8 text-sm text-gray-400">
            Link: {{ eventRegisterUrl($event) }}
        </div>
    </div>

</body>
</html>
