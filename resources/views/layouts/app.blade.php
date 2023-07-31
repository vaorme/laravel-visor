<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Nartag | Traducciones amistosas' }}</title>
        <link href="{{ Vite::asset('resources/css/global.scss') }}" rel="stylesheet">
        <!-- Stles -->
        <link href="{{ Vite::asset('resources/css/web/tom-select.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script src="{{ Vite::asset('resources/js/web/tom-select.js') }}"></script>
        
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
    </head>
    <body>
        <div id="app">
            <x-header />
            <main class="main">
                {{ $slot }}
				<x-main-shortcuts/>
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
    </body>
</html>