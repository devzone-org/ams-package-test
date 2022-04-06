
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
<body>


{{--@include('ams::include.header')--}}

<div class="h-screen flex overflow-hidden bg-gray-100 " x-data="{sidebar:false}" x-cloak>

    @include('ams::include.sidebar')


    <div class="flex flex-col w-0 flex-1 overflow-hidden">

        <div class="relative flex-shrink-0 flex h-16 bg-white border-b border-gray-200 ">

            <!-- Search bar -->
            <div class="flex-1 px-4 flex justify-between ">
                <div class="flex-1 flex">
                    <span class=" inline-flex items-center px-4 py-2 border border-transparent text-lg font-medium rounded-md text-indigo-700 ">  </span>
                </div>
                <div class="ml-4 flex items-center md:ml-6">

                    <!-- Profile dropdown -->
                    <div class="ml-3 relative" x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false">
                        <div>
                            <button @click="open = !open" class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 lg:p-2 lg:rounded-md lg:hover:bg-gray-50" id="user-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                                @if(!empty(auth()->user()->attachment))
                                <img class="h-8 w-8 rounded-full" src="{{ env('AWS_URL') . auth()->user()->attachment }}" alt="">
                                @else
                                      <div class="rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                <span class="hidden ml-3 text-gray-700 text-sm font-medium lg:block"><span class="sr-only">Open user menu for </span>{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                                <svg class="hidden flex-shrink-0 ml-1 h-5 w-5 text-gray-400 lg:block" x-description="Heroicon name: solid/chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.2 93a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <div x-show="open" x-description="Profile dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 z-10 bg-white ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="user-menu"  >
                            <a href="{{url('ums')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                            <a href="{{url('ums')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                            <a href="{{url('logout')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <main class="flex-1 relative z-0  focus:outline-none overflow-auto" tabindex="0">
            @yield('content')
        </main>

    </div>
@yield('script')

</body>
</html>

