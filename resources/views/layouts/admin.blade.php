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
