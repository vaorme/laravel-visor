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
            <div class="bdy vealo">
                {!! $insertBody !!}
            </div>
        @endif
        <div id="app">
            <x-header />
            <main class="main">
                {{ $slot }}
            </main>
            <x-footer/>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.8.4/dist/lazyload.min.js"></script>
        <script>
            (function() {
                function logElementEvent(eventName, element) {
                    console.log("Error cargando imagen:", eventName);
                }
                var callback_loaded = function(element) {
                    element.style.padding = "0";
                };
                var callback_error = function(element) {
                    logElementEvent("💀 ERROR", element);
                    element.src = "https://via.placeholder.com/440x560/?text=Error+Loading+Image";
                };
    
                ll = new LazyLoad({
                    elements_selector: ".lazy",
                    thresholds:"600%",
                    callback_loaded: callback_loaded,
                    callback_error: callback_error,
                });
            })();
        </script>
    </body>
</html>
