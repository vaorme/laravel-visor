import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // GLOBAL

                // ADMIN
                'resources/js/admin/library.js',
                'resources/js/admin/admin.js',
                'resources/css/admin/admin.scss',
                'resources/js/admin/slider.js',
                'resources/css/admin/slider.scss',

                // LOGIN / REGISTER
                'resources/css/loreg.scss',

                // TOM SELECT
                'resources/css/web/tom-select.css',
                'resources/js/web/tom-select.js',

                // WEB
                'resources/js/web/app.js',
                'resources/js/web/home.js',

                // :MANGA DETAIL
                'resources/js/web/mangaDetail.js',
                'resources/css/web/mangaDetail.scss',

                // :VIEWER
                'resources/css/web/viewer.scss',
                'resources/js/web/viewer.js',

                // :PROFILE
                'resources/css/web/profile.scss',
                'resources/js/web/profile.js',

                // :ACCOUNT
                'resources/css/web/account.scss',
                'resources/js/web/account.js',
                
                'resources/js/library.js',
                'resources/css/global.scss',
                'resources/css/app.scss',
            ],
            refresh: true,
        }),
    ],
});
