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
            'resources/js/web/app.js'
        ])
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ Vite::asset('resources/css/web/coloris.css') }}"/>
        <link href="{{ Vite::asset('resources/css/web/viewer.scss') }}" rel="stylesheet">

        <script src="{{ asset('storage/assets/js/tom-select.js') }}"></script>
        <script src="{{ Vite::asset('resources/js/web/coloris.js') }}"></script>
        <script type="module" src="{{ Vite::asset('resources/js/web/viewer.js') }}"></script>
        @php
            $insertHead = config('app.head');
        @endphp
        @if ($insertHead)
            {!! $insertHead !!}
        @endif
    </head>
    <body>
        @php
            $insertBody = config('app.body');
        @endphp
        @if ($insertBody)
            {!! $insertBody !!}
        @endif
        <div id="app">
            <x-header />
            <main class="main">
                {{ $slot }}
            </main>
            <x-footer/>
        </div>
    </body>
</html>
