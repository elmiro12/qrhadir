<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/png" href="@cachebust(setting('app_favicon'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        document.documentElement.classList.add('no-transition');
    </script>

    {{ --  Test Komentar Tambahkan ini-- }}

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

{{-- Custom Script per halaman --}}
@stack('scripts')

</body>
</html>
