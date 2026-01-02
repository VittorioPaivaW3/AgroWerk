import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
 
                'verdes' : {
                   
                    verde_claro:`#8DC63F`,
                    verde_folha:`#63BE15`,
                    verde_escuro:`#00381B`,
                    verde_bandeira:`#00843D`,
 
                },
            },
 
 
            fontFamily: {
                sans: ['Josefin Sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],

    darkMode: 'class'
};
