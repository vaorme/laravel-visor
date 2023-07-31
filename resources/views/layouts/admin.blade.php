<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Controller' }}</title>
        <!-- Styles -->
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script type="module" src="{{ Vite::asset('resources/js/admin/library.js') }}"></script>
        @vite(['resources/css/global.scss', 'resources/css/admin/admin.scss'])
        
        <script src="{{ Vite::asset('resources/js/web/tom-select.js') }}"></script>
        @if (Route::is(['slider.index']))
            <script type="module" src="{{ Vite::asset('resources/js/admin/slider.js') }}"></script>
            <link href="{{ Vite::asset('resources/css/admin/slider.scss') }}" rel="stylesheet">
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
        @vite('resources/js/admin/admin.js')
    </body>
</html>
