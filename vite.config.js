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
                'resources/js/web/whelp.js',
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

				// ?:DASH DEPENDENCIES
				'resources/dashboard/js/comics.js',
				'resources/dashboard/js/chapters.js',
				'resources/dashboard/js/users.js',
				'resources/dashboard/js/comics.types.js',
				'resources/dashboard/js/comics.status.js',
				'resources/dashboard/js/comics.categories.js',
				'resources/dashboard/js/comics.demographies.js',
				'resources/dashboard/js/comics.tags.js',
				'resources/dashboard/js/own-dropzone.js',
				'resources/dashboard/js/own-helpers.js',
				'resources/dashboard/js/own-validator.js',

				// ?:DASH GLOBAL
				'resources/dashboard/css/global.scss',
				'resources/dashboard/css/comics.scss',
				'resources/dashboard/css/users.scss'

            ],
            refresh: true,
        }),
    ],
});
