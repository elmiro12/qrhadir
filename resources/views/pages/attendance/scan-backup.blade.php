<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('app_name', 'Absensi QR') }} - Scan Kehadiran</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #f3f4f6; }
        .scan-area { position: relative; width: 100%; max-width: 500px; margin: 0 auto; aspect-ratio: 1/1; background: black; border-radius: 1rem; overflow: hidden; }
        #reader { width: 100%; height: 100%; }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    
    @include('layouts.partials.navbar-guest')

    {{-- Main Content --}}
    <main class="flex-grow flex flex-col items-center justify-center p-4">
        
        <div class="w-full max-w-6xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Scan QR Kehadiran</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                {{-- LEFT COLUMN: Manual Input / Scanner Gun --}}
                <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 h-full flex flex-col justify-center">
                    <div class="text-center mb-6">
                        <div class="inline-block p-4 bg-orange-100 text-orange-600 rounded-full mb-4">
                            <span class="material-icons text-4xl">keyboard</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Input Manual</h2>
                        <p class="text-gray-500">Gunakan Scanner Gun atau ketik kode</p>
                    </div>

                    <div class="space-y-4">
                        <input type="text" id="manual-token" 
                            class="w-full px-6 py-4 text-xl font-mono text-center border-2 border-gray-300 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition shadow-inner"
                            placeholder="Ketik / Scan Token disini..." 
                            autofocus 
                            autocomplete="off">
                        
                        <button onclick="submitManual()" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-xl text-lg shadow-lg hover:shadow-orange-200 transition flex items-center justify-center gap-2">
                            <span class="material-icons">send</span>
                            Cek Kehadiran
                        </button>
                    </div>
                    
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-sm text-gray-500 text-center">
                        <p><strong>Tips:</strong> Klik kolom input sebelum memindai dengan alat Scanner Gun agar kode otomatis terisi.</p>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Camera Scanner --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex flex-col items-center">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-icons text-orange-600">videocam</span>
                        Scan Kamera
                    </h2>
                    
                    {{-- Scanner Area --}}
                    <div class="scan-area shadow-inner border-4 border-gray-200 relative w-full aspect-square max-w-[400px] rounded-xl overflow-hidden bg-black">
                        <div id="reader" class="w-full h-full object-cover"></div>
                        {{-- Overlay animation line --}}
                        <div class="absolute inset-0 border-2 border-white/20 pointer-events-none">
                            <div class="w-full h-1 bg-red-500/80 shadow-[0_0_10px_rgba(239,68,68,0.8)] animate-scan-line absolute top-0"></div>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mt-4">Arahkan QR Code ke kamera</p>
                </div>

            </div>

            {{-- Hasil Scan (Global Result) --}}
            <div id="scan-result" class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div id="result-card" class="bg-white p-8 rounded-3xl shadow-2xl max-w-md w-full transform transition-all scale-100">
                    <div class="text-center">
                        <div id="result-icon-container" class="inline-flex p-6 rounded-full mb-6">
                            <span id="result-icon" class="material-icons text-6xl text-white">check</span>
                        </div>
                        <h3 id="result-title" class="font-bold text-3xl mb-2">Berhasil!</h3>
                        <p id="result-message" class="text-gray-600 text-lg">Peserta berhasil check-in.</p>
                    </div>
                    <div class="mt-8">
                        <button onclick="hideResult()" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition">
                            Tutup (Esc)
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </main>

    @include('layouts.partials.footer-guest')

    {{-- Scripts --}}
    <script type="module">
        // Import Html5Qrcode
        // Audio
        const audioSuccess = new Audio('https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3');
        const audioError = new Audio('https://assets.mixkit.co/active_storage/sfx/2003/2003-preview.mp3');

        let isScanning = true;
        let html5QrCode;
        
        // Manual Input handling
        const manualInput = document.getElementById('manual-token');
        
        // Auto-focus manual input on load
        window.addEventListener('load', () => {
           manualInput.focus();
        });

        // Listen for Enter key on manual input
        manualInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                submitManual();
            }
        });
        
        // Global ESC to close modal
        document.addEventListener('keydown', function(e) {
            if(e.key === "Escape") {
                hideResult();
            }
        });

        window.submitManual = function() {
            const token = manualInput.value.trim();
            if(!token) return;
            processToken(token);
            manualInput.value = ''; // clear
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;
            // Debounce for camera
            isScanning = false;
            console.log(`Code matched = ${decodedText}`, decodedResult);
            processToken(decodedText);
        }

        function processToken(token) {
             // Kirim ke Server Admin (Secure)
             fetch("{{ route('admin.attendance.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ token: token })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data.status, data.message);
                if(data.status === 'success' || data.status === 'warning') {
                    audioSuccess.play();
                } else {
                    audioError.play();
                }
            })
            .catch(err => {
                console.error(err);
                showResult('error', 'Terjadi kesalahan koneksi.');
                audioError.play();
            })
            .finally(() => {
                // Resume scanning logic handled in hideResult or timeout
            });
        }

        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }

        function showResult(status, message) {
            const resultDiv = document.getElementById('scan-result');
            const iconContainer = document.getElementById('result-icon-container');
            const icon = document.getElementById('result-icon');
            const title = document.getElementById('result-title');
            const msg = document.getElementById('result-message');
            
            resultDiv.classList.remove('hidden');
            
            // Reset classes
            iconContainer.className = "inline-flex p-6 rounded-full mb-6";
            
            if (status === 'success') {
                iconContainer.classList.add('bg-green-500');
                icon.innerText = 'check_circle';
                title.innerText = 'Berhasil Hadir!';
                title.className = "font-bold text-3xl mb-2 text-green-600";
            } else if (status === 'warning') {
                iconContainer.classList.add('bg-yellow-500');
                icon.innerText = 'info';
                title.innerText = 'Info Absensi';
                title.className = "font-bold text-3xl mb-2 text-yellow-600";
            } else {
                iconContainer.classList.add('bg-red-500');
                icon.innerText = 'error';
                title.innerText = 'Gagal';
                title.className = "font-bold text-3xl mb-2 text-red-600";
            }
            
            msg.innerText = message;
            
            // Auto close after 3s if success
            if(status === 'success' || status === 'warning') {
                setTimeout(() => {
                    hideResult();
                }, 3000);
            }
        }

        window.hideResult = function() {
            document.getElementById('scan-result').classList.add('hidden');
            isScanning = true;
            manualInput.focus(); // Refocus manual input
        }

        async function startScanner() {
            try {
                html5QrCode = new window.Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure);
                console.log("Scanner started");
            } catch (err) {
                console.error("Error starting scanner", err);
                // Optionally alert user, but manual input still works
            }
        }

        // Start
        startScanner();
        
    </script>
    
    <style>
        @keyframes scan-line {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan-line {
            animation: scan-line 2s linear infinite;
        }
    </style>
</body>
</html>
