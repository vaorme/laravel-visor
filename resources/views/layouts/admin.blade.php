<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/global.scss', 'resources/css/admin.scss', 'resources/js/library.js'])
        
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
        @vite('resources/js/admin.js')
    </body>
</html>
