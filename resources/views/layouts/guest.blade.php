<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- CDN fallback -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @php
        $bs = file_exists(public_path('assets/css/bootstrap.min.css')) ? filemtime(public_path('assets/css/bootstrap.min.css')) : null;
        $pl = file_exists(public_path('assets/css/plugins.min.css')) ? filemtime(public_path('assets/css/plugins.min.css')) : null;
        $ka = file_exists(public_path('assets/css/kaiadmin.min.css')) ? filemtime(public_path('assets/css/kaiadmin.min.css')) : null;
    @endphp

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}{{ $bs ? '?v='.$bs : '' }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}{{ $pl ? '?v='.$pl : '' }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}{{ $ka ? '?v='.$ka : '' }}">

    @if(app()->environment('local') || file_exists(public_path('build')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @stack('head')
</head>
<body class="font-sans antialiased">
    <main>
        @yield('content')
    </main>

    <!-- Core JS -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
