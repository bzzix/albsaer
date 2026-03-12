@php
    $primaryColor = get_setting('primary_color', '#3b82f6');
    $secondaryColor = get_setting('secondary_color', '#10b981');
    $fontFamily = get_setting('font_family', 'Cairo');
    $borderRadius = get_setting('border_radius', 'xl');
    $glassIntensity = get_setting('glass_intensity', 'middle');

    $primaryPalette = get_color_palette($primaryColor);
    $secondaryPalette = get_color_palette($secondaryColor);

    $radiusMap = [
        'none' => '0px',
        'md' => '0.375rem',
        'lg' => '0.5rem',
        'xl' => '0.75rem',
        '2xl' => '1rem',
        '3xl' => '1.5rem',
    ];
    $radiusValue = $radiusMap[$borderRadius] ?? '0.75rem';

    $glassMap = [
        'low' => ['blur' => '8px', 'opacity' => '0.4'],
        'middle' => ['blur' => '20px', 'opacity' => '0.75'],
        'high' => ['blur' => '40px', 'opacity' => '0.9'],
    ];
    $glassConfig = $glassMap[$glassIntensity] ?? $glassMap['middle'];
    $darkMode = (bool) get_setting('dark_mode_enabled', false);

    // Helper to convert hex to rgb for rgba usage
    function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r, $g, $b";
    }
    $primaryRgb = hexToRgb($primaryColor);
@endphp

<!-- Tailwind CSS: Loaded in Head to prevent FOUC -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    sans: ['{{ $fontFamily }}', 'Cairo', 'sans-serif'],
                    display: ['Outfit', 'sans-serif'],
                },
                colors: {
                    primary: {
                        50: 'var(--color-primary-50)',
                        100: 'var(--color-primary-100)',
                        500: 'var(--color-primary-500)',
                        600: 'var(--color-primary-600)',
                        700: 'var(--color-primary-700)',
                    },
                    secondary: {
                        50: 'var(--color-secondary-50)',
                        500: 'var(--color-secondary-500)',
                        600: 'var(--color-secondary-600)',
                    },
                    surface: {
                        50: 'var(--color-surface-50)',
                        100: 'var(--color-surface-100)',
                        200: 'var(--color-surface-200)',
                        300: 'var(--color-surface-300)',
                        400: 'var(--color-surface-400)',
                        500: 'var(--color-surface-500)',
                        600: 'var(--color-surface-600)',
                        700: 'var(--color-surface-700)',
                        800: 'var(--color-surface-800)',
                        900: 'var(--color-surface-900)',
                    }
                },
                boxShadow: {
                    'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.05)',
                    'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
                }
            }
        }
    }
</script>

<!-- Fonts: Dynamic Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ $fontFamily }}:wght@400;500;600;700;800&family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&family=Almarai:wght@400;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- iziToast -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

<style>
    :root {
        /* Primary Palette */
        @foreach($primaryPalette as $shade => $hex)
        --color-primary-{{ $shade }}: {{ $hex }};
        @endforeach

        /* Secondary Palette */
        @foreach($secondaryPalette as $shade => $hex)
        --color-secondary-{{ $shade }}: {{ $hex }};
        @endforeach

        /* Surface Color (Slate/Gray) - Light Mode Defaults */
        --color-surface-50: #f8fafc;
        --color-surface-100: #f1f5f9;
        --color-surface-200: #e2e8f0;
        --color-surface-300: #cbd5e1;
        --color-surface-400: #94a3b8;
        --color-surface-500: #64748b;
        --color-surface-600: #475569;
        --color-surface-700: #334155;
        --color-surface-800: #1e293b;
        --color-surface-900: #0f172a;

        /* Radius & Effects */
        --main-radius: {{ $radiusValue }};
        --color-primary-500-rgb: {{ $primaryRgb }};
    }

    /* Dark Mode Overrides */
    @if($darkMode)
    html.dark, :root {
        --color-surface-50: #1e293b;
        --color-surface-100: #0f172a;
        --color-surface-200: #334155;
        --color-surface-300: #475569;
        --color-surface-400: #64748b;
        --color-surface-500: #94a3b8;
        --color-surface-600: #cbd5e1;
        --color-surface-700: #e2e8f0;
        --color-surface-800: #f1f5f9;
        --color-surface-900: #f8fafc;
    }
    
    .glass-panel {
        background: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    body {
        color: var(--color-surface-900);
    }
    @endif

    body {
        font-family: '{{ $fontFamily }}', 'Cairo', sans-serif;
        background-color: var(--color-surface-100);
        background-image: radial-gradient(var(--color-surface-300) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    .glass-panel {
        background: rgba(255, 255, 255, {{ $glassConfig['opacity'] }});
        backdrop-filter: blur({{ $glassConfig['blur'] }}) saturate(180%);
        -webkit-backdrop-filter: blur({{ $glassConfig['blur'] }}) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: var(--main-radius);
    }

    /* Apply radius to all rounded-* elements if needed, or stick to main-radius for panels */
    .rounded-xl { border-radius: var(--main-radius); }
    .rounded-2xl { border-radius: calc(var(--main-radius) * 1.5); }

    @keyframes reveal-up {
        0% {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .reveal-anim {
        animation: reveal-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(var(--color-primary-500), 0.15);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--color-surface-300);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--color-surface-400);
    }

    /* Sidebar Transition */
    #sidebar {
        transition: width 0.3s ease;
    }

    .nav-text {
        transition: opacity 0.2s ease, width 0.2s ease;
        overflow: hidden;
        white-space: nowrap;
    }

    .sidebar-collapsed {
        width: 5rem !important;
    }

    .sidebar-collapsed .nav-text {
        opacity: 0;
        width: 0;
        display: none;
    }

    .sidebar-collapsed .logo-text {
        opacity: 0;
        width: 0;
        display: none;
    }

    .sidebar-collapsed .profile-details {
        display: none;
    }

    /* Input Field Refinement */
    .premium-input {
        background-color: var(--color-surface-50);
        border: 1px solid var(--color-surface-200);
        color: var(--color-surface-900);
        transition: all 0.2s ease;
        border-radius: var(--main-radius);
    }

    .premium-input:focus {
        background-color: var(--color-surface-50);
        border-color: var(--color-primary-500);
        box-shadow: 0 0 0 4px var(--color-primary-100);
        outline: none;
    }

    /* Global Dark Mode Fixes for common Tailwind classes */
    @if($darkMode)
    .dark .bg-white {
        background-color: var(--color-surface-50) !important;
    }
    .dark .bg-gray-50 {
        background-color: var(--color-surface-100) !important;
    }
    .dark .bg-gray-100 {
        background-color: var(--color-surface-100) !important;
    }
    .dark .bg-gray-200 {
        background-color: var(--color-surface-200) !important;
    }
    .dark .text-gray-900, .dark .text-surface-900 {
        color: var(--color-surface-900) !important;
    }
    .dark .text-gray-800, .dark .text-gray-700 {
        color: var(--color-surface-800) !important;
    }
    .dark .text-gray-600, .dark .text-gray-500 {
        color: var(--color-surface-500) !important;
    }
    .dark .border-gray-200, .dark .border-surface-200 {
        border-color: var(--color-surface-200) !important;
    }

    /* Fix for invisible buttons (white background with white text) */
    .dark button.bg-white, .dark a.bg-white {
        background-color: var(--color-surface-200) !important;
        color: var(--color-surface-900) !important;
    }
    
    /* Input & Interactive Element Focus States */
    .dark input, .dark select, .dark textarea {
        background-color: var(--color-surface-100) !important;
        color: var(--color-surface-900) !important;
        border-color: var(--color-surface-200) !important;
    }

    .dark input:focus, .dark select:focus, .dark textarea:focus, .dark .premium-input:focus {
        background-color: var(--color-surface-200) !important;
        border-color: var(--color-primary-500) !important;
        color: var(--color-surface-900) !important;
    }

    /* Fix for Accent Backgrounds (primary-50, orange-50, etc.) in Dark Mode */
    .dark .bg-primary-50, .dark .bg-blue-50 { background-color: rgba(var(--color-primary-500-rgb, 59, 130, 246), 0.1) !important; color: var(--color-primary-400) !important; }
    .dark .bg-secondary-50, .dark .bg-green-50 { background-color: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; }
    .dark .bg-orange-50 { background-color: rgba(249, 115, 22, 0.1) !important; color: #fb923c !important; }
    .dark .bg-purple-50 { background-color: rgba(168, 85, 247, 0.1) !important; color: #c084fc !important; }
    .dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; color: #f87171 !important; }
    
    /* Card Hover Shadow in Dark Mode */
    .dark .card-hover:hover {
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5) !important;
    }
    @endif
</style>
