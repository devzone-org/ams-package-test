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
                <a href="{{url('dashboard')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='hospital' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Welcome Screen
                    </a>
                    @if(empty(env('PHARMACY_ONLY')))
                    <a href="{{url('hospital')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='hospital' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reception Portal
                    </a>
                    @endif
                    <a href="{{url('pharmacy')}}" class="@if(empty(env('PHARMACY_ONLY'))) ml-8 @endif inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='pharmacy' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Pharmacy Portal
                    </a>
                    <a href="{{url('accounts')}}" class="ml-8 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 {{ Request::segment(1)=='accounts' ? 'bg-indigo-100' : 'bg-white' }} hover:bg-indigo-50  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Accounts Portal
                    </a>
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
                <p class="rounded-md py-2 px-3 text-sm inline-flex text-right font-medium text-gray-900   hover:text-gray-900">
                    {{ Auth::user()->name }}<br>{{date('d F Y h:i A')}}</p>
                <div class="flex-shrink-0 relative">
                    <div>
                        <button type="button" @click="dropdown=!dropdown;"
                                class="bg-white rounded-full flex focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full"
                                 src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80"
                                 alt="">
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
                        <a href="#" @click="dropdown=false" class="block px-4 py-2 text-sm text-gray-700   "
                           @mouseenter="activeIndex = 0" @mouseleave="activeIndex = -1"
                           :class="{ 'bg-gray-100': activeIndex === 0 }" role="menuitem" tabindex="-1"
                           id="user-menu-item-0">Your Profile</a>
                        <a href="#" @click="dropdown=false" class="block px-4 py-2 text-sm text-gray-700"
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
