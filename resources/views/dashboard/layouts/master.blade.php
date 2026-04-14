<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="{{ get_setting('dark_mode_enabled', false) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'الرئيسية') | {{ get_setting('site_name', 'معهد البصائر') }}</title>
    
    <!-- Favicon -->
    @if(get_setting('site_favicon'))
        <link rel="icon" type="image/png" href="{{ get_setting('site_favicon') }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif
    
    <!-- Fonts: Cairo for body, Outfit for numbers/display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    @include('dashboard.partials.head-css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="text-surface-800 antialiased h-screen flex overflow-hidden" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" 
        class="fixed inset-0 bg-surface-900/50 z-40 lg:hidden backdrop-blur-sm" 
        x-transition.opacity style="display: none;"></div>

    <!-- Sidebar -->
    @include('dashboard.partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 h-full overflow-y-auto w-full flex flex-col relative transition-all duration-300">
        
        <!-- Header -->
        @include('dashboard.partials.header')

        <!-- Page Content -->
        <div class="p-4 lg:p-8 max-w-[1600px] w-full mx-auto space-y-6 lg:space-y-8 flex-1">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
        
        <!-- Footer -->
        @include('dashboard.partials.footer')
    </main>

    @include('dashboard.partials.footer-scripts')
    @livewireScripts
</body>
</html>
