<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', setting('app_name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/png" href="@cachebust(setting('app_favicon'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Custom CSS per halaman --}}
    @yield('css')
</head>
<body class="flex flex-col min-h-screen">
    
    @include('layouts.partials.navbar-guest')
    
    <main class="flex-grow flex flex-col items-center justify-center p-4">
        @yield('content')
    </main>
    
    @include('layouts.partials.footer-guest')
    
</body>
@stack('scripts')
<x-swal/>
</html>
