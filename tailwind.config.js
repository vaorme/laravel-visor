/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'vo-red': '#9b2e2e',
                'vo-red-over': '#ad3636',
                'vo-black': '#141414',
                'vo-orange': '#cf9129',
                'vo-orange-over': '#e39e2a',
                'vo-green': '#64873b',
                'vo-green-over': '#6f9742'
            }
        }
    },

    plugins: [],
};
