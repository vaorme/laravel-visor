<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Visor' }}</title>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">
        @vite([
            'resources/css/global.scss',
            'resources/css/app.scss',
            'resources/js/web/app.js'
        ])

        <!-- Stles -->
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script src="{{ Vite::asset('resources/js/web/tom-select.js') }}"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
        <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>

        @if (Route::is(['chapter_viewer.index']))
            <link href="{{ Vite::asset('resources/css/web/viewer.scss') }}" rel="stylesheet">
            <script type="module" src="{{ Vite::asset('resources/js/web/viewer.js') }}"></script>
        @endif
    </head>
    <body>
        <div id="app">
            <x-header />
            <main class="main">
                {{ $slot }}
            </main>
            <x-footer/>
        </div>
    </body>
</html>
