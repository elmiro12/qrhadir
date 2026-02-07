<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', setting('app_name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/png" href="@cachebust(setting('app_favicon'))">
    <link rel="apple-touch-icon" href="{{ asset('pwa-icon.svg') }}">
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
    {{-- Custom CSS per halaman --}}
    @yield('css')
</head>
<body class="flex flex-col min-h-screen">
    
    @include('layouts.partials.navbar-guest')
    
    <main class="flex-grow flex flex-col items-center justify-center p-4">
        @yield('content')
        
        <!-- PWA Install Button -->
        <button id="pwa-install-btn" class="fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg z-50 hidden hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="material-icons">download</i>
            <span class="font-medium">Install App</span>
        </button>
    </main>
    
    @include('layouts.partials.footer-guest')

    <script>
        let deferredPrompt;
        const installBtn = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn.classList.remove('hidden');
        });

        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response: ${outcome}`);
            deferredPrompt = null;
            installBtn.classList.add('hidden');
        });

        window.addEventListener('appinstalled', () => {
            installBtn.classList.add('hidden');
            deferredPrompt = null;
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
@stack('scripts')
<x-swal/>
</html>
