<nav class="lg:py-2 lg:flex lg:space-x-8" aria-label="Global">
    <a href="{{ url('accounts') }}"
       class="{{ Request::segment(1)=='accounts' && empty(Request::segment(2))? $a_current : '' }} rounded-md py-2 px-3 inline-flex items-center text-sm font-medium text-gray-900 hover:bg-gray-50 hover:text-gray-900 active:bg-gray-50">
        Dashboard </a>

    <div class="relative" x-data="{open:false}" x-cloak>

        <button type="button" @click="open=true;"
                class="{{ Request::segment(1)=='accounts' && Request::segment(2) == 'accountant' ? $a_current : '' }}  cursor-pointer  rounded-md py-2 px-3 inline-flex items-center text-sm font-medium text-gray-900 hover:bg-gray-50 hover:text-gray-900 focus-within:bg-gray-50 focus-within:outline-none"
                aria-expanded="false">
            <span>Accountant</span>

            <svg class="text-gray-400   h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"/>
            </svg>
        </button>

        <div x-show="open" @click.away="open=false"

             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"

             class="absolute  z-10 -ml-4 mt-3 transform px-2 w-screen max-w-md sm:px-0 lg:ml-0 lg:left-1/2 lg:-translate-x-1/2">
            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">


                    <a href="{{ url('accounts/accountant/journal/add') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='journal' && (Request::segment(4) == 'add')  ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                General Journal
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Here you can add temporary general journal entries.
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/accountant/journal') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ (Request::segment(3)=='journal' && empty(Request::segment(4)) || Request::segment(3)=='journal' && (Request::segment(4) == 'edit'))  ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Temp General Journal
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                List of temporary general journal entries.
                            </p>
                        </div>
                    </a>
                    <a href="{{ url('accounts/accountant/trace-voucher') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ (Request::segment(3)=='trace-voucher' )  ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                               Trace Voucher
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                List of trace vouchers by filters.
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/accountant/ledger') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ (Request::segment(3)=='ledger' && empty(Request::segment(4)) )  ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Ledger
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Book keeping record of an account
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/accountant/day-close') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{   (Request::segment(3) == 'day-close')  ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Day Close
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage Cash and Cash Equivalents
                            </p>
                        </div>
                    </a>
                    <a href="{{ url('accounts/accountant/payments') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{     (Request::segment(3) == 'payments') ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Payments & Receiving
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage Expenses and Payments
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/petty-expenses-list/unclaimed')
}}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{     (Request::segment(2) == 'petty-expenses' || Request::segment(2) == 'petty-expenses-list') ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Petty Expenses
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage Petty Expenses
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/accountant/chart-of-accounts') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='chart-of-accounts' && empty(Request::segment(4))  ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Chart of Accounts
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                List of all accounts.
                            </p>
                        </div>
                    </a>


                    <a href="{{ url('accounts/accountant/chart-of-accounts/add') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='chart-of-accounts' && (Request::segment(4) == 'add')  ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Create Account
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                You can add chart of account at level 4 or 5.
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/accountant/equity-ratio') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{   (Request::segment(3) == 'equity-ratio')  ? 'bg-gray-100' : ''}} ">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>

                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Equity Ratio
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Equity ratio of accounts.
                            </p>
                        </div>
                    </a>

                </div>

            </div>
        </div>
    </div>
    <div class="relative" x-data="{open:false}" x-cloak>

        <button type="button" @click="open=true;"
                class="{{ Request::segment(1)=='accounts' && Request::segment(2) == 'reports' ? $a_current : '' }}   cursor-pointer  rounded-md py-2 px-3 inline-flex items-center text-sm font-medium text-gray-900 hover:bg-gray-50 hover:text-gray-900 focus-within:bg-gray-50 focus-within:outline-none"
                aria-expanded="false">
            <span>Reports</span>

            <svg class="text-gray-400   h-5 w-5 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"/>
            </svg>
        </button>

        <div x-show="open" @click.away="open=false"

             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"

             class="absolute  z-10 -ml-4 mt-3 transform px-2 w-screen max-w-md sm:px-0 lg:ml-0 lg:left-1/2 lg:-translate-x-1/2">
            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">


                    <a href="{{ url('accounts/reports/trial-balance') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='trial-balance'    ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Trial Balance
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Statement of all debits and credits in a double-entry account book
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/reports/balance-sheet') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='balance-sheet'  ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Statement of Financial Position
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Statement of Financial Position
                            </p>
                        </div>
                    </a>

                    <a href="{{ url('accounts/reports/profit-and-loss') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='profit-and-loss'  ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Profit and Loss
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Financial statement that summarizes the revenues, costs, and expenses incurred during a
                                specified period.
                            </p>
                        </div>
                    </a>
                    <a href="{{ url('accounts/reports/profit-and-loss/date-wise') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='profit-and-loss'  ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Profit and Loss Date Wise
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Financial statement that summarizes the revenues, costs, and expenses incurred during a
                                specified period.
                            </p>
                        </div>
                    </a>


                    <a href="{{ url('accounts/reports/day-closing') }}"
                       class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-100 {{ Request::segment(3)=='day-closing'  ? 'bg-gray-100' : ''}} ">

                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                                Day Closing Report
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Day Closing Report
                            </p>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</nav>


