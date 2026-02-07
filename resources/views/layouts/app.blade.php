<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
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
    
    <script>
        document.documentElement.classList.add('no-transition');
    </script>

    {{-- Custom CSS per halaman --}}
    @yield('css')
</head>
<body class="bg-gray-50">

@include('layouts.partials.navbar')
@include('layouts.partials.sidebar')

<main class="p-4 md:ml-64 pt-20 min-h-screen bg-linear-to-br from-white to-gray-200 transition-transform" id="main-content">
    {{-- Page Title --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800 uppercase">
            @yield('title')
        </h1>

        @hasSection('page-desc')
            <p class="mt-1 text-sm text-gray-500">
                @yield('page-desc')
            </p>
        @endif
    </div>

    @yield('content')
</main>

@include('layouts.partials.scripts')

@include('layouts.partials.footer')

<x-swal />

<!-- PWA Install Button -->
<button id="pwa-install-btn" class="fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg z-50 hidden hover:bg-blue-700 transition-colors flex items-center gap-2">
    <i class="material-icons">download</i>
    <span class="font-medium">Install App</span>
</button>

<script>
    let deferredPrompt;
    const installBtn = document.getElementById('pwa-install-btn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        // Update UI notify the user they can install the PWA
        installBtn.classList.remove('hidden');
    });

    installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        // Show the install prompt
        deferredPrompt.prompt();
        // Wait for the user to respond to the prompt
        const { outcome } = await deferredPrompt.userChoice;
        console.log(`User response to the install prompt: ${outcome}`);
        // We've used the prompt, so user can't use it again until next page load/event
        deferredPrompt = null;
        installBtn.classList.add('hidden');
    });

    window.addEventListener('appinstalled', () => {
        // Hide the app-provided install promotion
        installBtn.classList.add('hidden');
        // Clear the deferredPrompt so it can be garbage collected
        deferredPrompt = null;
        console.log('PWA was installed');
    });
</script>

{{-- Custom Script per halaman --}}
@stack('scripts')

</body>
</html>
