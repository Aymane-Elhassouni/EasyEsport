/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // DARK MODE (Gaming Cyber Elite)
                dark: {
                    bg: '#0b1120',
                    surface: '#111827',
                    card: '#1f2937',
                    border: '#374151',
                },
                // LIGHT MODE (SaaS Clean Premium)
                light: {
                    bg: '#f9fafb',
                    surface: '#ffffff',
                    card: '#ffffff',
                    border: '#e5e7eb',
                },
                // ACCENTS
                primary: {
                    DEFAULT: '#6366f1', // Indigo moderne
                    light: '#4f46e5',
                    glow: 'rgba(99, 102, 241, 0.5)',
                },
                secondary: {
                    DEFAULT: '#22d3ee', // Cyan néon soft
                    light: '#06b6d4',
                    glow: 'rgba(34, 211, 238, 0.5)',
                },
                success: {
                    DEFAULT: '#4ade80',
                    glow: 'rgba(74, 222, 128, 0.5)',
                },
                danger: {
                    DEFAULT: '#f87171',
                    glow: 'rgba(248, 113, 113, 0.5)',
                },
                warning: {
                    DEFAULT: '#facc15',
                    glow: 'rgba(250, 204, 21, 0.5)',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                display: ['Outfit', 'Inter', 'system-ui', 'sans-serif'],
            },
            animation: {
                'scan': 'scan 3s linear infinite',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                'float': 'float 6s ease-in-out infinite',
                'glow-line': 'glowLine 2s linear infinite',
                'fade-in': 'fadeIn 0.5s ease-out forwards',
                'slide-up': 'slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
            },
            keyframes: {
                scan: {
                    '0%': { top: '0%' },
                    '100%': { top: '100%' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                    '50%': { opacity: '0.8', transform: 'scale(0.98)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                glowLine: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(100%)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            boxShadow: {
                'neon-primary': '0 0 15px rgba(99, 102, 241, 0.3)',
                'neon-secondary': '0 0 15px rgba(34, 211, 238, 0.3)',
                'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
                'premium': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
            backgroundImage: {
                'grid-white': "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(255 255 255 / 0.05)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e\")",
                'grid-dark': "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(0 0 0 / 0.05)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e\")",
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}