<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.title') }}</title>
        <x-seo/>
        <link rel="shortcut icon" href="{{ config('app.favicon') ? asset('storage/'.config('app.favicon')): asset('storage/images/favicon.png') }}" type="image/png">
        <link href="{{ Vite::asset('resources/dashboard/css/dependencies/tabler.min.css') }}" rel="stylesheet"/>
        <link href="{{ Vite::asset('resources/dashboard/css/dependencies/tabler-flags.min.css') }}" rel="stylesheet"/>
        <link href="{{ Vite::asset('resources/dashboard/css/dependencies/tabler-payments.min.css') }}" rel="stylesheet"/>
        <link href="{{ Vite::asset('resources/dashboard/css/dependencies/tabler-vendors.min.css') }}" rel="stylesheet"/>
        <link href="{{ Vite::asset('resources/dashboard/libs/flatpickr/dist/flatpickr.min.css') }}" rel="stylesheet"/>
        <link href="{{ Vite::asset('resources/dashboard/css/dependencies/demo.min.css') }}" rel="stylesheet"/>
        <link href="{{ Vite::asset('resources/dashboard/css/global.scss') }}" rel="stylesheet"/>
        @if (Route::is(['comics.*']))
            <link href="{{ Vite::asset('resources/dashboard/css/comics.scss') }}" rel="stylesheet"/>
        @endif
		@if (Route::is(['users.*']))
            <link href="{{ Vite::asset('resources/dashboard/css/users.scss') }}" rel="stylesheet"/>
        @endif
        @if (Route::is(['settings.*']))
            <link href="{{ Vite::asset('resources/dashboard/css/settings.scss') }}" rel="stylesheet"/>
        @endif
        @if (Route::is(['products.*']))
            <link href="{{ Vite::asset('resources/dashboard/css/products.scss') }}" rel="stylesheet"/>
        @endif
        <style>
            @import url('https://rsms.me/inter/inter.css');
            :root {
                --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            }
            body {
                font-feature-settings: "cv03", "cv04", "cv11";
            }
        </style>
    </head>
    <body>
        <script src="{{ asset('assets/dashboard/js/dependencies/demo-theme.min.js') }}"></script>
        <x-dashboard.header />
        {{ $slot }}
        <x-dashboard.footer/>
        <!-- Libs JS -->
        <script src="{{ asset('assets/dashboard/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/jsvectormap/dist/js/jsvectormap.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
        <!-- Tabler Core -->
        <script src="{{ asset('assets/dashboard/libs/list.js/dist/list.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/flatpickr/dist/flatpickr.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/litepicker/dist/litepicker.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/tom-select/dist/js/tom-select.complete.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/libs/tinymce/tinymce.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/js/dependencies/tabler.min.js') }}" defer></script>
        <script src="{{ asset('assets/dashboard/js/dependencies/demo.min.js') }}" defer></script>
		
		@if (Route::is(['comics.index', 'comics.create', 'comics.edit']))
			<script type="module" src="{{ Vite::asset('resources/dashboard/js/comics.js') }}" defer></script>
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/chapters.js') }}" defer></script>
        @endif
		@if (Route::is(['users.index', 'users.create', 'users.edit']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/users.js') }}" defer></script>
        @endif
		@if (Route::is(['comics.types.*']))
			<script type="module" src="{{ Vite::asset('resources/dashboard/js/comics.types.js') }}" defer></script>
        @endif
		@if (Route::is(['comics.status.*']))
			<script type="module" src="{{ Vite::asset('resources/dashboard/js/comics.status.js') }}" defer></script>
        @endif
		@if (Route::is(['comics.demographies.*']))
			<script type="module" src="{{ Vite::asset('resources/dashboard/js/comics.demographies.js') }}" defer></script>
        @endif
		@if (Route::is(['comics.categories.*']))
			<script type="module" src="{{ Vite::asset('resources/dashboard/js/comics.categories.js') }}" defer></script>
        @endif
		@if (Route::is(['comics.tags.*']))
			<script type="module" src="{{ Vite::asset('resources/dashboard/js/comics.tags.js') }}" defer></script>
        @endif
        @if (Route::is(['roles.index', 'roles.create', 'roles.edit']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/roles.js') }}" defer></script>
        @endif
        @if (Route::is(['permissions.index']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/permissions.js') }}" defer></script>
        @endif
        @if (Route::is(['settings.index']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/settings.js') }}" defer></script>
        @endif
        @if (Route::is(['settings.ads.index']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/settings.ads.js') }}" defer></script>
        @endif
        @if (Route::is(['settings.seo.index']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/settings.seo.js') }}" defer></script>
        @endif
        @if (Route::is(['products.*']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/products.js') }}" defer></script>
        @endif
        @if (Route::is(['products.types.*']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/products.types.js') }}" defer></script>
        @endif
        @if (Route::is(['orders.*']))
            <script type="module" src="{{ Vite::asset('resources/dashboard/js/orders.js') }}" defer></script>
        @endif
    </body>
</html>