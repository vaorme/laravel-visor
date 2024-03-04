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
				'resources/dashboard/js/dependencies/tabler.min.js',
				'resources/dashboard/css/dependencies/tabler.min.css',
				'resources/dashboard/css/dependencies/tabler-flags.min.css',
				'resources/dashboard/css/dependencies/tabler-payments.min.css',
				'resources/dashboard/css/dependencies/tabler-vendors.min.css',
				'resources/dashboard/libs/flatpickr/dist/flatpickr.min.css',
				'resources/dashboard/css/dependencies/demo.min.css',
				'resources/dashboard/js/dependencies/demo-theme.min.js',
				'resources/dashboard/libs/apexcharts/dist/apexcharts.min.js',
        		'resources/dashboard/libs/jsvectormap/dist/js/jsvectormap.min.js',
        		'resources/dashboard/libs/jsvectormap/dist/maps/world.js',
        		'resources/dashboard/libs/jsvectormap/dist/maps/world-merc.js',
        		'resources/dashboard/libs/list.js/dist/list.min.js',
        		'resources/dashboard/libs/flatpickr/dist/flatpickr.min.js',
        		'resources/dashboard/libs/litepicker/dist/litepicker.js',
        		'resources/dashboard/libs/tom-select/dist/js/tom-select.complete.min.js',
        		'resources/dashboard/libs/tinymce/tinymce.min.js',
				'resources/dashboard/js/dependencies/demo.min.js',
				'resources/dashboard/js/comics.js',
				'resources/dashboard/js/chapters.js',
				'resources/dashboard/js/users.js',
				'resources/dashboard/js/comics.types.js',
				'resources/dashboard/js/comics.status.js',

				// ?:DASH GLOBAL
				'resources/dashboard/css/global.scss',
				'resources/dashboard/css/comics.scss',
				'resources/dashboard/css/users.scss'

            ],
            refresh: true,
        }),
    ],
});
