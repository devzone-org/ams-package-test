<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="{{ asset('ams/css/app.css') }}" rel="stylesheet">
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireScripts
    <script src="{{ asset('ams/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
</head>
<body>

<div class="h-screen flex overflow-hidden bg-gray-100" x-data="{sidebar:false}" x-cloak>

{{--    @include('ams::include.header')--}}


    @include('ams::include.sidebar')
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0">
            @yield('content')
        </main>

    </div>
        @yield('script')


</body>
</html>
