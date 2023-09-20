<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Visor' }}</title>
        <x-seo/>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">
        @vite([
            'resources/css/global.scss',
            'resources/css/app.scss',
        ])
        <script type="module" src="{{ Vite::asset('resources/js/web/app.js') }}"></script>
    </head>
    <body>
        <div id="app">
            {{ $slot }}
        </div>
    </body>
</html>
