<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/global.scss', 'resources/css/admin.scss', 'resources/js/library.js'])
    </head>
    <body>
        <div id="app">
            <x-header />
            <main>
                <div class="sidebar">
                    <ul>
                        <li><a href="{{ route('admin.index') }}">Overview</a></li>
                        <li>
                            <a href="{{ route('manga.index') }}">Manga</a>
                            <div class="dropdown">
                                <ul>
                                    <li><a href="#">All</a></li>
                                    <li><a href="#">Categories</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a href="#">Users</a></li>
                        <li><a href="#"></a></li>
                        <li><a href="#">Overview</a></li>
                    </ul>
                </div>
                <div class="conten">
                    {{ $slot }}
                </div>
            </main>
            <x-footer/>
        </div>
        @vite(['resources/js/admin.js'])
    </body>
</html>
