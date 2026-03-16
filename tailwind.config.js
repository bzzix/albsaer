import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/**/*.php',  // Scan PHP files for Livewire components
    ],

    safelist: [
        // Primary colors (all shades)
        { pattern: /bg-primary-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /text-primary-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /border-primary-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /ring-primary-(50|100|200|300|400|500|600|700|800|900)/ },
        // Secondary colors
        { pattern: /bg-secondary-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /text-secondary-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /border-secondary-(50|100|200|300|400|500|600|700|800|900)/ },
        // Surface colors
        { pattern: /bg-surface-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /text-surface-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /border-surface-(50|100|200|300|400|500|600|700|800|900)/ },
        // Common dynamic classes
        { pattern: /bg-(blue|green|red|yellow|orange|purple|pink|gray|slate)-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /text-(blue|green|red|yellow|orange|purple|pink|gray|slate)-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /border-(blue|green|red|yellow|orange|purple|pink|gray|slate)-(50|100|200|300|400|500|600|700|800|900)/ },
        // Focus ring
        { pattern: /focus:ring-(primary|secondary)-(50|100|200|300|400|500|600|700)/ },
        { pattern: /focus:border-(primary|secondary|surface)-(50|100|200|300|400|500|600|700)/ },
        // Dark mode variants
        { pattern: /dark:bg-(surface|primary|secondary)-(50|100|200|300|400|500|600|700|800|900)/ },
        { pattern: /dark:text-(surface|primary|secondary)-(50|100|200|300|400|500|600|700|800|900)/ },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', 'Cairo', ...defaultTheme.fontFamily.sans],
                tajawal: ['Tajawal', 'sans-serif'],
                almarai: ['Almarai', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: 'var(--color-primary-50)',
                    100: 'var(--color-primary-100)',
                    200: 'var(--color-primary-200)',
                    300: 'var(--color-primary-300)',
                    400: 'var(--color-primary-400)',
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
                },
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
            }
        },
    },

    plugins: [forms, typography],
};
