<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.title') }}</title>
        <x-seo/>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">
        <link href="{{ Vite::asset('resources/css/global.scss') }}" rel="stylesheet">
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <link href="{{ Vite::asset('resources/css/web/shop.scss') }}" rel="stylesheet">
        <!-- Scripts -->
        <script src="{{ asset('storage/assets/js/tom-select.js') }}"></script>
        <script type="module" src="{{ Vite::asset('resources/js/web/shop.js') }}"></script>
        @if (!Route::is(['shop.index']))
            @if (showAds())
                @php
                    $insertHead = config('app.head');
                @endphp
                @if ($insertHead)
                    {!! $insertHead !!}
                @endif
            @endif
        @endif
    </head>
    <body>
        @if (!Route::is(['shop.index']))
            @if (showAds())    
                @php
                    $insertBody = config('app.body');
                @endphp
                @if ($insertBody)
                    <div class="bdy vealo">
                        {!! $insertBody !!}
                    </div>
                @endif
            @endif
        @endif
        <div id="app">
            <x-header />
            <main class="main">
                {{ $slot }}
				<x-main-shortcuts />
            </main>
            <x-footer/>
        </div>
        {{-- SCRIPTS --}}
        @if (showAds())
            <script type="module" src="{{ Vite::asset('resources/js/web/whelp.js') }}"></script>
        @endif
        <script type="module" src="{{ Vite::asset('resources/js/web/app.js') }}"></script>
    </body>
</html>