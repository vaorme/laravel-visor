<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Controller' }}</title>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">
        <!-- Styles -->
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script src="https://cdn.tiny.cloud/1/it8xtzzcm77gv4mg2r0cs0456xmy9w93n4mstt2u1r5ek0f6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script type="module" src="{{ Vite::asset('resources/js/admin/library.js') }}"></script>
        @vite(['resources/css/global.scss', 'resources/css/admin/admin.scss'])
        
        <script src="{{ asset('storage/assets/js/tom-select.js') }}"></script>
        @if (Route::is(['slider.index']))
            <script type="module" src="{{ Vite::asset('resources/js/admin/slider.js') }}"></script>
            <link href="{{ Vite::asset('resources/css/admin/slider.scss') }}" rel="stylesheet">
        @endif
        @if (Route::is(['manga.index', 'manga.create', 'manga.edit']))
            <script type="module" src="{{ Vite::asset('resources/js/admin/manga.js') }}"></script>
        @endif
        @if (Route::is([
            'products.index',
            'products.create',
            'products.edit',
            'product_types.index'
        ]))
            <script type="module" src="{{ Vite::asset('resources/js/admin/products.js') }}"></script>
            <link href="{{ Vite::asset('resources/css/admin/products.scss') }}" rel="stylesheet">
        @endif
        @if (Route::is([
            'orders.edit',
            'orders.index'
        ]))
            <script type="module" src="{{ Vite::asset('resources/js/admin/orders.js') }}"></script>
        @endif
        @if (Route::is([
            'manga_book_status.index',
            'manga_book_status.create',
            'manga_book_status.edit',
            'manga_demography.index',
            'manga_demography.create',
            'manga_demography.edit',
            'categories.index',
            'categories.create',
            'categories.edit',
            'manga_types.index',
            'manga_types.create',
            'manga_types.edit',
        ]))
            <script type="module" src="{{ Vite::asset('resources/js/admin/manga_extras.js') }}"></script>
        @endif
        @if (Route::is(['settings.index']) || Route::is(['settings.ads.index']) || Route::is(['settings.seo.index']))
            <link href="{{ Vite::asset('resources/css/admin/settings.scss') }}" rel="stylesheet">
        @endif
    </head>
    <body>
        @routes
		<script>
            let currentUrl = window.location.href;
            let ziggyUrl;

            // Check if the current URL contains a specific domain or path
            if (currentUrl.includes("nartag.com")) {
                ziggyUrl = 'https://nartag.com'; // Change this to the appropriate URL for domain1
            } else if (currentUrl.includes("lectornartag.com")) {
                ziggyUrl = 'https://lectornartag.com'; // Change this to the appropriate URL for domain2
            } else {
                ziggyUrl = '{{ env('APP_URL') }}'; // Fallback to the default URL from environment variable
            }

            Ziggy.url = ziggyUrl;
        </script>
        <div id="app">
            <x-header />
            <main class="main flex">
                <x-admin.aside />
                <div class="box">
                    {{ $slot }}
                </div>
            </main>
            <x-footer/>
        </div>
        <script type="module" src="{{ Vite::asset('resources/js/admin/admin.js') }}"></script>
        @if (Route::is(['settings.index']) || Route::is(['settings.ads.index']) || Route::is(['settings.seo.index']))
            <script type="module" src="{{ Vite::asset('resources/js/admin/settings.js') }}"></script>
        @endif
    </body>
</html>
