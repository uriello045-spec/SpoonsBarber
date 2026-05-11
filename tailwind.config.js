import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // ESTA ES LA LÍNEA QUE FALTABA PARA QUE EL BOTÓN FUNCIONE
    darkMode: 'class', 

    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                'urban-dark': '#1a1a1a',
                'urban-gray': '#4a4a4a',
                'urban-white': '#f5f5f5',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};