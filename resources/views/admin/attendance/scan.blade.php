@extends('layouts.app')

@section('title', 'Scan QR Code Kehadiran')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Manual Input / Barcode Scanner Input -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Scan / Input Token</h3>
        
        <div class="mb-4">
            <label for="token" class="block text-sm font-medium text-gray-700 mb-1">Token QR</label>
            <input type="text" id="tokenInput" name="token" 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                placeholder="Klik disini scan qr atau ketik manual..." autofocus autocomplete="off">
            <p class="text-xs text-gray-500 mt-1">Pastikan kursor aktif di kolom ini saat menggunakan Scanner Gun.</p>
        </div>

        <button id="btnSubmit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg my-2">Cek Absensi</button>
        
        <div id="resultArea" class="mt-6 hidden">
            <!-- Results will be shown here -->
        </div>
    </div>

    <!-- Camera Scan (Optional Enhancement) -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Kamera Scanner</h3>
        <div id="reader" class="w-full bg-gray-100 rounded-lg overflow-hidden" style="min-height: 300px;"></div>
        <p class="text-xs text-center text-gray-400 mt-2">Izin kamera diperlukan untuk fitur ini.</p>
    </div>
</div>

@push('scripts')
<script type="module">
    const tokenInput = document.getElementById('tokenInput');
    const resultArea = document.getElementById('resultArea');

    // Focus input on load
    tokenInput.focus();

    // Prevent loose focus
    document.addEventListener('click', function(e) {
        if(e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
            tokenInput.focus();
        }
    });

    // Handle Enter Key
    tokenInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            processAttendance(tokenInput.value);
        }
    });

    document.getElementById('btnSubmit').addEventListener('click', () => {
        processAttendance(tokenInput.value);
    });

    function processAttendance(token) {
        if(!token) return;

        // Reset display
        resultArea.innerHTML = '<p class="text-gray-500">Memproses...</p>';
        resultArea.classList.remove('hidden');

        fetch("{{ route('admin.attendance.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ token: token })
        })
        .then(response => response.json())
        .then(data => {
            let color = 'gray';
            if(data.status === 'success') color = 'green';
            if(data.status === 'warning') color = 'yellow';
            if(data.status === 'error') color = 'red';

            resultArea.innerHTML = `
                <div class="p-4 rounded-lg bg-${color}-100 border border-${color}-400 text-${color}-700">
                    <p class="font-bold uppercase text-sm">${data.status}</p>
                    <p class="text-lg">${data.message}</p>
                </div>
            `;
            
            // Clear input for next scan
            tokenInput.value = '';
            tokenInput.focus();
        })
        .catch(err => {
            console.error(err);
            resultArea.innerHTML = '<p class="text-red-500">Terjadi kesalahan sistem.</p>';
        });
    }

    // Custom Scanner Setup using Html5Qrcode (Auto Start)
    async function startScanner() {
        if (!window.Html5Qrcode) {
            console.error("Html5Qrcode not loaded");
            return;
        }

        const html5QrCode = new window.Html5Qrcode("reader");
        
        try {
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            // Start scanning with environment camera (back camera)
            await html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess,
                onScanFailure
            );
            
            console.log("Camera started successfully");
        } catch (err) {
            console.error("Error starting camera:", err);
            document.getElementById('reader').innerHTML = `
                <div class="text-center p-4">
                    <p class="text-red-500 mb-2">Gagal mengakses kamera.</p>
                    <button onclick="location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded text-sm">Coba Lagi</button>
                    <p class="text-xs text-gray-400 mt-2">${err}</p>
                </div>
            `;
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Scan result: ${decodedText}`, decodedResult);
        tokenInput.value = decodedText;
        processAttendance(decodedText);
        
        // Optional: Pause scanning for 1-2 seconds to avoid multiple reads
        // html5QrCode.pause();
        // setTimeout(() => html5QrCode.resume(), 2000);
    }

    function onScanFailure(error) {
        // console.warn(`Code scan error = ${error}`);
    }

    // Start on load
    startScanner();
</script>
@endpush
@endsection
