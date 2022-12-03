@php
    if(env('SIDEBAR_NAME') == 'pos'){
        $a_current = "bg-gray-900 text-white";
        $a_default = "text-gray-200 hover:bg-gray-700 hover:text-white ";
    }else{
        $a_current = "bg-gray-100 text-gray-900";
        $a_default = "text-gray-600 hover:bg-gray-50 hover:text-gray-900";
    }

    $svg_default = "text-gray-400 group-hover:text-gray-500";
    $svg_current = "text-gray-500";

$favourite = [];
        if (!empty($fetch_favourites)){
            $favourite = array_column($fetch_favourites,'url');
        }
@endphp

<nav class="flex-1 px-2 mt-5 space-y-1 bg-white"
     @if(env('SIDEBAR_NAME') == 'pos') style="background-color:rgb(30 41 59 / var(--tw-bg-opacity));" @endif>

    <div @if(Request::segment(2)=='accountant') x-data="{ isExpanded: true }" @else x-data="{ isExpanded: false }"
         @endif x-cloak
         class="space-y-1">
        <button
                class="flex items-center w-full py-2 pl-2 pr-1 text-sm font-medium @if(env('SIDEBAR_NAME') == 'pos') text-gray-200  @else text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900  focus:ring-indigo-500  @endif rounded-md group   focus:outline-none focus:ring-2 "
                @click.prevent="isExpanded = !isExpanded" x-bind:aria-expanded="isExpanded">
            @if(!env('SIDEBAR_NAME') == 'pos')
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="{{ Request::segment(1)=='accounts' && Request::segment(2) == 'accountant' ? $a_current : '' }} mr-3 h-6 w-6"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            @else
                <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }"
                     x-state:on="Expanded"
                     x-state:off="Collapsed"
                     class="w-5 h-5 ml-3 text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
                     viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
                </svg>
            @endif

            Accountant
            @if(!env('SIDEBAR_NAME') == 'pos')
                <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }"
                     x-state:on="Expanded"
                     x-state:off="Collapsed"
                     class="w-5 h-5 ml-auto text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
                     viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
                </svg>
            @endif
        </button>

        <div x-show="isExpanded" class="space-y-1">

            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/journal/add') }}"
                   class="{{ (Request::segment(3)=='journal' && Request::segment(4)=='add') ? $a_current : $a_default }} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    General Journal
                </a>
                <div wire:click.prevent="selectedFavourite('General Journal','accounts/accountant/journal/add')"
                     class="{{ (Request::segment(3)=='journal' && Request::segment(4)=='add') ? $a_current : $a_default }} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/accountant/journal/add',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/journal') }}"
                   class="{{ (Request::segment(3)=='journal' && empty(Request::segment(4))) ? $a_current : $a_default }} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Temp General Journal
                </a>
                <div wire:click.prevent="selectedFavourite('Temp General Journal','accounts/accountant/journal')"
                     class="{{ (Request::segment(3)=='journal' && empty(Request::segment(4))) ? $a_current : $a_default }} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/accountant/journal',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1">

            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/trace-voucher') }}"
                   class="{{ (Request::segment(3)=='trace-voucher')? $a_current : $a_default }} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Trace Voucher
                </a>
                <div wire:click.prevent="selectedFavourite('Trace Voucher','accounts/accountant/trace-voucher')"
                     class="{{ (Request::segment(3)=='trace-voucher')? $a_current : $a_default }} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/accountant/trace-voucher',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/ledger') }}"
                   class="{{ (Request::segment(3)=='ledger')  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Ledger
                </a>
                <div wire:click.prevent="selectedFavourite('Ledger','accounts/accountant/ledger')"
                     class="{{ (Request::segment(3)=='ledger')  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/accountant/ledger',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/day-close') }}"
                   class="  {{   (Request::segment(3) == 'day-close')  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Day Close
                </a>

                <div wire:click.prevent="selectedFavourite('Day Close','accounts/accountant/day-close')"
                     class="{{  (Request::segment(3) == 'day-close')  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/accountant/day-close',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/payments') }}"
                   class=" {{     (Request::segment(3) == 'payments') ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Payment & Receiving
                </a>

                <div wire:click.prevent="selectedFavourite('Payments & Receiving','accounts/accountant/payments')"
                     class="{{     (Request::segment(3) == 'payments') ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/accountant/payments',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/accountant/chart-of-accounts') }}"
                   class=" {{ Request::segment(3)=='chart-of-accounts' && empty(Request::segment(4))  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Chart of Accounts
                </a>
                <div wire:click.prevent="selectedFavourite('Charts of Accounts','accounts/accountant/chart-of-accounts')"
                   class="{{ Request::segment(3)=='chart-of-accounts' && empty(Request::segment(4))  ? $a_current : $a_default}} pt-2
                     rounded-tr-md rounded-br-md cursor-pointer">

                {{--                            favourite--}}
                @if(in_array('accounts/accountant/chart-of-accounts',$favourite))
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="w-5 h-5 text-white">
                        <path fill-rule="evenodd"
                              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                              clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                @endif
            </div>
        </div>
    </div>

    <div x-show="isExpanded" class="space-y-1"
    >
        <div class="flex flex-row item-center">

            <a href="{{ url('accounts/accountant/chart-of-accounts/add') }}"
               class=" {{ Request::segment(3)=='chart-of-accounts' && (Request::segment(4) == 'add')  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Create Account

            </a>
            <div wire:click.prevent="selectedFavourite('Create Account','accounts/accountant/chart-of-accounts/add')"
                 class=" {{ Request::segment(3)=='chart-of-accounts' && (Request::segment(4) == 'add')  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                {{--                            favourite--}}
                @if(in_array('accounts/accountant/chart-of-accounts/add',$favourite))
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="w-5 h-5 text-white">
                        <path fill-rule="evenodd"
                              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                              clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                @endif
            </div>

        </div>

    </div>


    <div @if(Request::segment(2)=='reports') x-data="{ isExpanded: true }"
         @else x-data="{ isExpanded: false }"
         @endif x-cloak
         class="space-y-1">
        <button
                class="flex items-center w-full py-2 pl-2 pr-1 text-sm font-medium @if(env('SIDEBAR_NAME') == 'pos') text-gray-200  @else text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900  focus:ring-indigo-500  @endif rounded-md group   focus:outline-none focus:ring-2 "
                @click.prevent="isExpanded = !isExpanded" x-bind:aria-expanded="isExpanded">

            @if(!env('SIDEBAR_NAME') == 'pos')
                <svg class="{{ Request::segment(1)=='accounts' && Request::segment(2) == 'reports' ? $a_current : '' }} mr-3 h-6 w-6"
                     fill="none"
                     stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            @else
                <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }"
                     x-state:on="Expanded"
                     x-state:off="Collapsed"
                     class="w-5 h-5 ml-3 text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
                     viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
                </svg>
            @endif

            Reports
            @if(!env('SIDEBAR_NAME') == 'pos')
                <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }"
                     x-state:on="Expanded"
                     x-state:off="Collapsed"
                     class="w-5 h-5 ml-auto text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
                     viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
                </svg>
            @endif
        </button>
        <div x-show="isExpanded" class="space-y-1"
        >
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/reports/trial-balance') }}"
                   class="{{ Request::segment(3)=='trial-balance'    ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
                >
                    Trial Balance
                </a>
                <div wire:click.prevent="selectedFavourite('Trial Balance','accounts/reports/trial-balance')"
                     class="{{ Request::segment(3)=='trial-balance'    ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/reports/trial-balance',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>


        <div x-show="isExpanded" class="space-y-1">
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/reports/balance-sheet') }}"
                   class="{{ Request::segment(3)=='balance-sheet'  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                    Statement of Financial Position
                </a>
                <div wire:click.prevent="selectedFavourite('Statement of Financial Position','accounts/reports/balance-sheet')"
                     class="{{ Request::segment(3)=='balance-sheet'  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/reports/balance-sheet',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/reports/profit-and-loss') }}"
                   class="{{ Request::segment(3)=='profit-and-loss'  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                    Profit & Loss
                </a>
                <div wire:click.prevent="selectedFavourite('Profit and Loss','accounts/reports/profit-and-loss')"
                     class="{{ Request::segment(3)=='profit-and-loss'  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/reports/profit-and-loss',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/reports/profit-and-loss/date-wise') }}"
                   class="{{ (Request::segment(3) == 'profit-and-loss' && Request::segment(4)=='date-wise')  ? $a_current : $a_default}} group w-full rounded-tl-mdpr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                    Profit & Loss Date Wise
                </a>
                <div wire:click.prevent="selectedFavourite('Profit and Loss Datewise','accounts/reports/profit-and-loss/date-wise')"
                     class="{{ (Request::segment(3) == 'profit-and-loss' && Request::segment(4)=='date-wise')  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/reports/profit-and-loss/date-wise',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>


        <div x-show="isExpanded" class="space-y-1">

            <div class="flex flex-row item-center">

                <a href="{{ url('accounts/reports/day-closing') }}"
                   class="{{ Request::segment(3)=='day-closing'  ? $a_current : $a_default}} group w-full rounded-tl-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                    Day Closing
                </a>
                <div wire:click.prevent="selectedFavourite('Day Closing','accounts/reports/day-closing')"
                     class="{{ Request::segment(3)=='day-closing'  ? $a_current : $a_default}} pt-2 rounded-tr-md rounded-br-md cursor-pointer">

                    {{--                            favourite--}}
                    @if(in_array('accounts/reports/day-closing',$favourite))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-5 h-5 text-white">
                            <path fill-rule="evenodd"
                                  d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                  clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>

    </div>
        <div class="text-white mt-5">Favourites</div>
        @foreach($fetch_favourites as $f)
            <div class="flex flex-row">
                <a href="/{{$f['url']}}"
                   class="{{ Request::is($f['url']) ? $a_current : $a_default }} group w-full flex items-center pl-7 pr-2 py-2 text-sm font-medium rounded-md">{{$f['name']}}</a>
                <div>
                    <div class="pt-2 h-full cursor-pointer" wire:click="deleteFavourite('{{$f['id']}}')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5"
                             stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                        </svg>
                    </div>
                </div>
            </div>
    @endforeach
</nav>

