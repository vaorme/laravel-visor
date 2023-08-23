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
        <!-- Stles -->
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script src="{{ asset('storage/assets/js/tom-select.js') }}"></script>
        
        {{-- @vite([
            'resources/css/global.scss',
            'resources/css/app.scss'
        ]) --}}
        @if (Route::is(['manga_detail.index']))
            <link href="{{ Vite::asset('resources/css/web/mangaDetail.scss') }}" rel="stylesheet">
        @endif

        @if (Route::is(['profile.index']))
            <link href="{{ Vite::asset('resources/css/web/profile.scss') }}" rel="stylesheet">
        @endif

        @if (Route::is(['account.index']))
            <link href="{{ Vite::asset('resources/css/web/account.scss') }}" rel="stylesheet">
        @endif

        @if (Route::is(['library.index']))
            <link href="{{ Vite::asset('resources/css/web/library.scss') }}" rel="stylesheet">
        @endif
        @if (Route::is(['members.index']))
            <link href="{{ Vite::asset('resources/css/web/users.scss') }}" rel="stylesheet">
        @endif
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
				<x-main-shortcuts />
            </main>
            <x-footer/>
        </div>
        {{-- SCRIPTS --}}
        <script type="module" src="{{ Vite::asset('resources/js/web/app.js') }}"></script>
        @if (Route::is(['web.index']))
            <script type="module" src="{{ Vite::asset('resources/js/web/home.js') }}"></script>
        @endif
        @if (Route::is(['profile.index']))
            <script type="module" src="{{ Vite::asset('resources/js/web/profile.js') }}"></script>
        @endif
        @if (Route::is(['manga_detail.index']))
            <script type="module" src="{{ Vite::asset('resources/js/web/mangaDetail.js') }}"></script>
        @endif
        @if (Route::is(['account.index']))
            <script type="module" src="{{ Vite::asset('resources/js/web/account.js') }}"></script>
        @endif
        @if (Route::is(['library.index']))
            <script type="module" src="{{ Vite::asset('resources/js/web/library.js') }}"></script>
        @endif
        @if (Route::is(['members.index']))
            <script type="module" src="{{ Vite::asset('resources/js/web/users.js') }}"></script>
        @endif
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