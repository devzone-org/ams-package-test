@php
    $a_default = "text-gray-600 hover:bg-gray-50 hover:text-gray-900";
    $a_current = "bg-gray-100 text-gray-900";
    $svg_default = "text-gray-400 group-hover:text-gray-500";
    $svg_current = "text-gray-500";
@endphp

<nav class="flex-1 px-2 mt-5 space-y-1 bg-white">

    <div @if(Request::segment(2)=='accountant') x-data="{ isExpanded: true }" @else x-data="{ isExpanded: false }"
         @endif x-cloak
         class="space-y-1">
        <button
                class="flex items-center w-full py-2 pl-2 pr-1 text-sm font-medium text-gray-600 bg-white rounded-md group hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                @click.prevent="isExpanded = !isExpanded" x-bind:aria-expanded="isExpanded">
            <svg  class="{{ Request::segment(1)=='accounts' && Request::segment(2) == 'accountant' ? $a_current : '' }} mr-3 h-6 w-6"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Accountant
            <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }" x-state:on="Expanded"
                 x-state:off="Collapsed"
                 class="w-5 h-5 ml-auto text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
                 viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
            </svg>
        </button>

        <div x-show="isExpanded" class="space-y-1">

            <a href="{{ url('accounts/accountant/journal/add') }}"
               class="{{ (Request::segment(3)=='journal' && Request::segment(4)=='add') ? $a_current : $a_default }} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                General Journal
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/ledger') }}"
               class="{{ (Request::segment(3)=='ledger')  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Ledger
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/day-close') }}"
             class="  {{   (Request::segment(3) == 'day-close')  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Day Close
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/payments') }}"
              class=" {{     (Request::segment(3) == 'payments') ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
            Payment & Receiving
            </a>
        </div>

  <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/chart-of-accounts') }}"
              class=" {{ Request::segment(3)=='chart-of-accounts' && empty(Request::segment(4))  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Chart of Accounts
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/accountant/chart-of-accounts/add') }}"
              class=" {{ Request::segment(3)=='chart-of-accounts' && (Request::segment(4) == 'add')  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                 Create Account

            </a>
        </div>

    </div>


    <div @if(Request::segment(2)=='reports') x-data="{ isExpanded: true }" @else x-data="{ isExpanded: false }"
         @endif x-cloak
         class="space-y-1">
        <button
                class="flex items-center w-full py-2 pl-2 pr-1 text-sm font-medium text-gray-600 bg-white rounded-md group hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                @click.prevent="isExpanded = !isExpanded" x-bind:aria-expanded="isExpanded">

            <svg class="{{ Request::segment(1)=='accounts' && Request::segment(2) == 'reports' ? $a_current : '' }} mr-3 h-6 w-6" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Reports
            <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }" x-state:on="Expanded"
                 x-state:off="Collapsed"
                 class="w-5 h-5 ml-auto text-gray-300 transition-colors duration-150 ease-in-out transform group-hover:text-gray-400"
                 viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
            </svg>
        </button>
        <div x-show="isExpanded" class="space-y-1"
        >
            <a href="{{ url('accounts/reports/trial-balance') }}"
               class="{{ Request::segment(3)=='trial-balance'    ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal"
            >
                Trial Balance
            </a>
        </div>


        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/balance-sheet') }}"
               class="{{ Request::segment(3)=='balance-sheet'  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Statement of Financial Position
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/profit-and-loss') }}"
               class="{{ Request::segment(3)=='profit-and-loss'  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Profit & Loss
            </a>
        </div>

        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/profit-and-loss/date-wise') }}"
               class="{{ (Request::segment(3) == 'profit-and-loss' && Request::segment(4)=='date-wise')  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Profit & Loss Date Wise
            </a>
        </div>


        <div x-show="isExpanded" class="space-y-1">
            <a href="{{ url('accounts/reports/day-closing') }}"
               class="{{ Request::segment(3)=='day-closing'  ? 'bg-gray-100' : ''}} group rounded-md pr-2 pl-11 pl-3 py-2 flex items-center text-sm font-normal">
                Day Closing
            </a>
        </div>

    </div>
</nav>

