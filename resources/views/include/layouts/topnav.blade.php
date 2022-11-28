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
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

    @livewireScripts
    <script src="{{ asset('ams/js/app.js') }}"></script>



</head>
<body class="bg-gray-200">
<div x-data="{menu:false,dropdown:false,activeIndex:-1}">
    @include('ams::include.header')
    @if(!empty(env('PAYMENT_DUE_MODAL')))
        @include('include.payment-due-modal')
    @endif
    <main>
        @yield('content')
    </main>

    @yield('script')
</div>
</body>
</html>
