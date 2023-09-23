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

        @if (Route::is(['account.index', 'my_shopping.index']))
            <link href="{{ Vite::asset('resources/css/web/account.scss') }}" rel="stylesheet">
        @endif

        @if (Route::is(['library.index']))
            <link href="{{ Vite::asset('resources/css/web/library.scss') }}" rel="stylesheet">
        @endif
        @if (Route::is(['members.index']))
            <link href="{{ Vite::asset('resources/css/web/users.scss') }}" rel="stylesheet">
        @endif
        @if (showAds())
            @php
                $insertHead = config('app.head');
            @endphp
            @if ($insertHead)
                {!! $insertHead !!}
            @endif
        @endif
    </head>
    <body>
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
        <div id="app">
            <x-header />
            <main class="main">
                {{ $slot }}
				<x-main-shortcuts />
            </main>
            <x-footer/>
            <div class="overlay"></div>
            <div class="modal" id="chapter-premium">
                <div class="modal__head">
                    <h4>Capítulo premium</h4>
                    <button>
                        <svg version="1.1" width="20" height="20" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="grid_system"></g>
                            <g id="_icons">
                                <path d="M5.3,18.7C5.5,18.9,5.7,19,6,19s0.5-0.1,0.7-0.3l5.3-5.3l5.3,5.3c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3   c0.4-0.4,0.4-1,0-1.4L13.4,12l5.3-5.3c0.4-0.4,0.4-1,0-1.4s-1-0.4-1.4,0L12,10.6L6.7,5.3c-0.4-0.4-1-0.4-1.4,0s-0.4,1,0,1.4   l5.3,5.3l-5.3,5.3C4.9,17.7,4.9,18.3,5.3,18.7z"></path>
                            </g>
                        </svg>
                    </button>
                </div>
                <div class="modal__body">
                    <div class="chapter__buy">
                        <div class="buy__box">
                            @if (Auth::check())
                                <form action="/" method="post" class="buy-chapter-form">
                                    @csrf
                                    <input type="hidden" name="chapter_id" value="">
                                    <h4></h4>
                                    <button>Comprar</button>
                                </form>
                            @else
                                <h2>Capítulo premium</h2>
                                <p>Debes <a href="{{ route('login') }}" target="_blank">Iniciar sesión</a> primero.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal__footer">

                </div>
            </div>
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