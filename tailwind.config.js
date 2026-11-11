import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,jsx}',
    ],
    safelist: [
        // ⚠️ Legacy classes — كنخليوهم دابا حيت مازال كاين ملفات ما تحولوش لـ Tailwind.
        'badge--success',
        'badge--warning',
        'badge--danger',
        'badge--neutral',
        'badge--success-outline',
        'badge--blue',
        'stat-card--orange',
        'stat-card--green',
        'stat-card--blue',
        'stat-card--yellow',
        'stat-card--red',
        'btn--orange',
        'btn--green',
        'btn--blue',
        'btn--purple',
        'btn--ghost',
        'btn--sm',
        'btn--md',
        'btn--lg',
        'flash-success',
        'flash-error',
        'flash-warning',
    ],
    theme: {
        extend: {
          fontFamily: {
                sans: ['Helvetica Neue', 'Helvetica', 'Arial', ...defaultTheme.fontFamily.sans],
                mono: ['Helvetica Neue', 'Helvetica', 'Arial', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Neutrals — 1:1 مع admin.css :root
                bg:      '#F7F6F3', // page background
                page:    '#F7F6F3', // alias — كتستعملها SubscriptionManager.jsx و Blade views
                surface: '#FFFFFF', // cards / topbar / sidebar
                border:  '#ECEAE4',
                line:    '#ECEAE4', // alias — كتستعملها SubscriptionManager.jsx و Blade views
                text:    '#18181B',
                muted:   '#7D7C78',
                // Brand
                orange: {
                    DEFAULT: '#F97316',
                    light:   '#FFF3EA',
                    dim:     '#FDE8D4',
                },
                // Status / accent colors — كلهم DEFAULT + bg (فاتح للخلفيات)
                green: {
                    DEFAULT: '#16A34A',
                    bg:      '#F0FDF4',
                },
                blue: {
                    DEFAULT: '#3B82F6',
                    bg:      '#EFF6FF',
                },
                purple: {
                    DEFAULT: '#8B5CF6',
                    bg:      '#F5F3FF',
                },
                red: {
                    DEFAULT: '#DC2626',
                    bg:      '#FEF2F2',
                },
                yellow: {
                    DEFAULT: '#CA8A04',
                    bg:      '#FEF9C3',
                },
            },
            borderRadius: {
                '10': '10px',
                '14': '14px',
                '16': '16px',
            },
            boxShadow: {
                'card':     '0 1px 3px rgba(16,24,40,0.05)',
                'modal':    '0 20px 60px rgba(0,0,0,0.15)',
                'dropdown': '0 8px 24px rgba(0,0,0,0.08)',
            },
        },
    },
    plugins: [forms],
};