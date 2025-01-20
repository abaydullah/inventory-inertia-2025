import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/**/*.vue',
        './resources/js/**/**/**/*.vue',
        './resources/js/**/**/**/**/*.vue',
        './resources/js/**/**/**/**/*.vue',
        './resources/js/**/**/**/**/**/*.vue',
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                'menu-bar': '#64748b',
                // 'menu-bar': 'rgb(170,170,170)',
                // 'menu-bg': '#e2e8f0',
                'menu-bg': '#f2f2f2',
                'menu-active': '#1DB3B1',
                'menu-active-text': '#4F4342',
                'primary': '#3f3cbb',
                'midnight': '#121063',
                'metal': '#565584',
                'tahiti': '#3ab7bf',
                'silver': '#ecebff',
                'bubble-gum': '#ff77e9',
                'bermuda': '#78dcca',
                'top-menu-bg': '#fbfbfb',
                'top-border-color': '#ececec',
                'fmenu-bg': '#2d2d2d',
                'menu-shadow': '#393939',
                'menu-border': '#f88c00',
                'menu-color': '#ddd',
                'menu-color-active': '#fff',
                'primary-bg': '#f7f7f7',
                'fprimary': '#333',
            },
            rotate: {
                '360': '360deg',
            },
            animation: {
                marquee: 'marquee 25s linear infinite',
                marquee2: 'marquee2 25s linear infinite',
                'spin-slow': 'spin 3s linear infinite',
                'spin': 'spin 1s linear infinite',
                'wiggle': 'wiggle 1s ease-in-out infinite',
            },
            keyframes: {
                marquee: {
                    '0%': {transform: 'translateX(0%)'},
                    '100%': {transform: 'translateX(-100%)'},
                },
                marquee2: {
                    '0%': {transform: 'translateX(100%)'},
                    '100%': {transform: 'translateX(0%)'},
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

        },
        screens: {
            'sm': '640px',
            // => @media (min-width: 640px) { ... }
            'md': '768px',
            // => @media (min-width: 1024px) { ... }
            'lg': '1024px',
            // => @media (min-width: 1024px) { ... }
            'xl': '1280px',
            // => @media (min-width: 1280px) { ... }
            '2xl': '1536px',
            // => @media (min-width: 1536px) { ... }
        }
    },

    plugins: [forms],
};
