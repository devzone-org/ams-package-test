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
@endphp

<nav class="flex-1 px-2 mt-5 space-y-1 bg-white"
     @if(env('SIDEBAR_NAME') == 'pos') style="background-color:rgb(30 41 59 / var(--tw-bg-opacity));" @endif>

    <div @if(Request::segment(2)=='accountant' || (Request::segment(2) == 'petty-expenses' || Request::segment(2) == 'petty-expenses-list')) x-data="{ isExpanded: true }" @else x-data="{ isExpanded: false }"
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
                     class="w-5 h-5 text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
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

            <a href="{{ url('accounts/accountant/journal/add') }}"
               class="{{ (Request::segment(3)=='journal' && Request::segment(4)=='add') ? $a_current : $a_default }} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                General Journal
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1">

            <a href="{{ url('accounts/accountant/journal') }}"
               class="{{ (Request::segment(3)=='journal' && empty(Request::segment(4))) ? $a_current : $a_default }} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Temp General Journal
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1">

            <a href="{{ url('accounts/accountant/trace-voucher') }}"
               class="{{ (Request::segment(3)=='trace-voucher')? $a_current : $a_default }} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Trace Voucher
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/ledger') }}"
               class="{{ (Request::segment(3)=='ledger')  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Ledger
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/day-close') }}"
               class="  {{   (Request::segment(3) == 'day-close')  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Day Close
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/payments') }}"
               class=" {{     (Request::segment(3) == 'payments') ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Payment & Receiving
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/petty-expenses-list/unclaimed') }}"
               class=" {{     (Request::segment(2) == 'petty-expenses' || Request::segment(2) == 'petty-expenses-list') ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Petty Expenses
            </a>
        </div>


        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/chart-of-accounts') }}"
               class=" {{ Request::segment(3)=='chart-of-accounts' && empty(Request::segment(4))  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Chart of Accounts
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/chart-of-accounts/add') }}"
               class=" {{ Request::segment(3)=='chart-of-accounts' && (Request::segment(4) == 'add')  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Create Account

            </a>
        </div>

    </div>


    <div @if(Request::segment(2)=='reports') x-data="{ isExpanded: true }" @else x-data="{ isExpanded: false }"
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
                     class="w-5 h-5 text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
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
            <a href="{{ url('accounts/reports/trial-balance') }}"
               class="{{ Request::segment(3)=='trial-balance'    ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Trial Balance
            </a>
        </div>


        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/balance-sheet') }}"
               class="{{ Request::segment(3)=='balance-sheet'  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Statement of Financial Position
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/profit-and-loss') }}"
               class="{{ Request::segment(3)=='profit-and-loss'  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Profit & Loss
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/profit-and-loss/date-wise') }}"
               class="{{ (Request::segment(3) == 'profit-and-loss' && Request::segment(4)=='date-wise')  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Profit & Loss Date Wise
            </a>
        </div>


        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/day-closing') }}"
               class="{{ Request::segment(3)=='day-closing'  ? $a_current : $a_default}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Day Closing
            </a>
        </div>

    </div>
</nav>

