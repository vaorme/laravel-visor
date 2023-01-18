<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/global.scss', 'resources/css/app.scss', 'resources/js/library.js'])
    </head>
    <body>
        <div id="app">
            <x-header />
            <main>
                {{ $slot }}
            </main>
            <x-footer/>
        </div>
        @vite(['resources/js/app.js'])
    </body>
</html>
