import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // :ADMIN
                'resources/js/admin/library.js',
                'resources/js/admin/admin.js',
                'resources/css/admin/admin.scss',
                'resources/js/admin/slider.js',
                'resources/css/admin/slider.scss',
                // :MANGA
                'resources/js/admin/manga.js',
                'resources/js/admin/manga_extras.js',

                // :PRODUCTS
                'resources/js/admin/products.js',
                'resources/css/admin/products.scss',

                // :ORDERS
                'resources/js/admin/orders.js',

                // :WEB

                // LOGIN / REGISTER
                'resources/css/loreg.scss',
                'resources/js/loreg.js',

                // TOM SELECT
                'resources/css/web/tom-select.css',
                // 'resources/js/web/tom-select.js',

                // COLORIS
                'resources/css/web/coloris.css',
                'resources/js/web/coloris.js',

                // WEB
                'resources/js/web/app.js',
                'resources/js/web/home.js',

                // MEMBERS
                'resources/css/web/users.scss',
                'resources/js/web/users.js',

                // :LIBRARY
                'resources/js/web/library.js',
                'resources/css/web/library.scss',

                // :SETTINGS
                'resources/css/admin/settings.scss',
                'resources/js/admin/settings.js',

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
                
                // :SHOP
                'resources/css/web/shop.scss',
                'resources/js/web/shop.js',

                'resources/js/library.js',
                'resources/css/global.scss',
                'resources/css/app.scss',
            ],
            refresh: true,
        }),
    ],
});
