<div class="lg:hidden" x-show="sidebar">
    <div class="fixed inset-0 flex z-40">

        <div class="fixed inset-0"
             @click="sidebar = false"
             x-show="sidebar"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        >
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>

        <div
                x-show="sidebar"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"

                class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebar=false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <!-- Heroicon name: x -->
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5  pb-4 overflow-y-auto"
                 @if(env('SIDEBAR_NAME') == 'pos') style="background-color:rgb(30 41 59);" @endif>

                <div class="flex-shrink-0 flex items-center px-4 mx-auto">
                    <img class=" w-auto" src="{{ url(env('CLIENT_LOGO')) }}" alt="Logo">
                </div>

                @if(env('SIDEBAR_NAME') == 'pos')
                    @livewire('sidebar.sidebar-links')
                @else
                    @include('ams::include.sidebar-links')
                @endif
            </div>

        </div>
        <div class="flex-shrink-0 w-14">
            <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden lg:flex md:flex-shrink-0">
    <div class="flex flex-col w-60">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex flex-col h-0 flex-1 border-gray-200 bg-slate-800 bg-white "
             @if(env('SIDEBAR_NAME') == 'pos') style="background-color:rgb(30 41 59);" @endif>
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4 mx-auto">
                    <a href="{{ url('/') }}">
                        <img class=" w-auto h-22" src="{{ url(env('CLIENT_LOGO')) }}" alt="Logo">
                    </a>
                </div>
                @if(env('SIDEBAR_NAME') == 'pos')
                    @livewire('sidebar.sidebar-links')
                @else
                    @include('ams::include.sidebar-links')
                @endif

            </div>
        </div>
    </div>
</div>
