import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Support/Navigation.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"IBM Plex Sans Arabic"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eef4ff',
                    100: '#dbe6fe',
                    500: '#2f5bd3',
                    600: '#2549b5',
                    700: '#1d3a91',
                    900: '#14244f',
                },
            },
        },
    },

    plugins: [forms],
};
