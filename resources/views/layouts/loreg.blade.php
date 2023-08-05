<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.title') }}</title>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">

        <!-- Scripts -->
        @vite(['resources/css/global.scss', 'resources/css/loreg.scss', 'resources/js/library.js'])
    </head>
    <body>
        <div id="app">
            <div class="loreg">
                {{ $slot }}
            </div>
        </div>
        @vite(['resources/js/loreg.js'])
    </body>
</html>
