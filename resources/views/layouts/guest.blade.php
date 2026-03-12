<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="{{ get_setting('dark_mode_enabled', false) ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $siteName = get_setting('site_name', 'معهد البصائر');
            $siteFavicon = get_setting('site_favicon');
        @endphp

        <title>{{ $siteName }} - تسجيل الدخول</title>

        <!-- Favicon -->
        @if($siteFavicon)
            <link rel="icon" type="image/png" href="{{ $siteFavicon }}">
        @else
            <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        @endif

        @include('dashboard.partials.head-css')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans text-surface-800 antialiased min-h-screen relative selection:bg-primary-500 selection:text-white">
        <!-- Dashboard Style Background -->
        <div class="fixed inset-0 z-0 bg-surface-100 pointer-events-none" style="background-image: radial-gradient(var(--color-surface-300) 1px, transparent 1px); background-size: 24px 24px;"></div>

        <!-- Main Content Wrapper -->
        <div class="relative z-10 flex min-h-screen flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>
</html>
