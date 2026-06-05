@php
    $a_default = "text-gray-300 hover:bg-gray-700 hover:text-white";
    $a_current = "bg-gray-100";
@endphp

<header class=" not-printable bg-white shadow">
    <div class="absolute">
        <a href="{{ url('dashboard') }}">
            <img class="p-4 mt-1 w-24" src="{{ url(env('CLIENT_LOGO')) }}" alt="">
        </a>
    </div>
    <div class="mx-auto ml-20 px-2 sm:px-4 lg:divide-y lg:divide-gray-200 lg:px-8">
        <div class="relative h-14 flex justify-between">
            <div class="relative z-10 px-2 flex lg:px-0">
                <div class="flex items-center p-2 pl-0">
                    @if(env('IS_HOSPITAL',false))
                        <a href="{{url('dashboard')}}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='hospitals' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Welcome Screen
                        </a>
                        <a href="{{url('hospital')}}"
                               class="ml-8 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='hospital' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reception Portal
                        </a>
                        <a href="{{url('pharmacy')}}"
                           class=" ml-8 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='pharmacy' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Pharmacy Portal
                        </a>
                        <a href="{{url('accounts')}}"
                           class="ml-8 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='accounts' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Accounts Portal
                        </a>
                    @elseif(env('PHARMACY_ONLY') == '1')
                        <a href="{{url('pharmacy')}}"
                           class=" inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='pharmacy' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Pharmacy Portal
                        </a>
                        <a href="{{url('accounts')}}"
                           class="ml-8 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='accounts' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Accounts Portal
                        </a>
                    @else
                        <h1 class="text-lg leading-6 font-semibold text-gray-900">
                            {{ env('APP_NAME') }}
                        </h1>

                    @endif
                </div>
            </div>

            <div class="relative z-10 flex items-center lg:hidden">
                <!-- Mobile menu button -->
                <button type="button"
                        class="rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-900"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="hidden lg:relative lg:z-10 lg:ml-4 lg:flex lg:items-center">

                @if (file_exists(public_path('css/dark-mode.css')))
                    <div class="ml-3 flex items-center space-x-3 mr-8">
                        <button
                                type="button"
                                class="theme-toggle-switch"
                                title="Toggle dark mode"
                                tabindex="0"
                                onclick="
                            const isDark = document.documentElement.classList.toggle('dark');
                            document.documentElement.classList.toggle('dark-ams', isDark);
                            localStorage.setItem('theme', isDark ? 'dark' : 'light');
                          "
                        >
                            <div class="theme-toggle-knob">
                                <!-- Sun icon for light mode -->
                                <svg class="theme-toggle-icon theme-sun-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                          clip-rule="evenodd"/>
                                </svg>

                                <!-- Moon icon for dark mode -->
                                <svg class="theme-toggle-icon theme-moon-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                @endif


                <p class="rounded-md py-2 px-3 text-sm inline-flex text-right font-medium text-gray-900   hover:text-gray-900">
                    {{ Auth::user()->name }}<br>{{date('d F Y h:i A')}}</p>
                <div class="flex-shrink-0 relative">
                    <div>
                        <button type="button" @click="dropdown=!dropdown;"
                                class="bg-white rounded-full flex focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            @if(!empty(auth()->user()->attachment))
                                <img class="h-8 w-8 rounded-full" src="{{ env('AWS_URL') . auth()->user()->attachment }}" alt="">
                            @else
                                <div class="rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </button>
                    </div>
                    <div x-show="dropdown" x-cloak="" @click.away="dropdown=false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md z-10 shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <a href="{{url('ums')}}" @click="dropdown=false" class="block px-4 py-2 text-sm text-gray-700"
                           @mouseenter="activeIndex = 1" @mouseleave="activeIndex = -1"
                           :class="{ 'bg-gray-100': activeIndex === 1 }" role="menuitem" tabindex="-1"
                           id="user-menu-item-1">Settings</a>
                        <form method="post" action="{{ url('logout') }}">
                            @csrf
                            <button type="submit" @click="dropdown=false"
                                    class="block px-4 py-2 w-full text-left text-sm text-gray-700"
                                    @mouseenter="activeIndex = 2" @mouseleave="activeIndex = -1"
                                    :class="{ 'bg-gray-100': activeIndex === 2 }" role="menuitem" tabindex="-1"
                                    id="user-menu-item-2">Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('ams::include.header-links')

    </div>


</header>
