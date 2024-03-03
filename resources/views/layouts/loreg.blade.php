<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.title') }}</title>
        <x-seo/>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">

        <!-- Scripts -->
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @vite(['resources/css/global.scss', 'resources/css/loreg.scss', 'resources/js/library.js'])
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
            <div class="loreg">
                {{ $slot }}
            </div>
        </div>
        @if (showAds())
            <script type="module" src="{{ Vite::asset('resources/js/web/whelp.js') }}"></script>
        @endif
        @vite(['resources/js/loreg.js'])
    </body>
</html>
