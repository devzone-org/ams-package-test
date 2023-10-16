<div>
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <form wire:submit.prevent="fetch">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Transactions Allocation</h3>
                </div>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Heroicon name: x-circle -->
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    @php
                                        $count = count($errors->all());
                                    @endphp
                                    There {{ $count > 1 ? "were {$count} errors" : "was {$count} error" }}
                                    with
                                    your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">

                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($success))
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Heroicon name: check-circle -->
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ $success }}
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" wire:click="$set('success', '')"
                                        class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                        <span class="sr-only">Dismiss</span>
                                        <!-- Heroicon name: x -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-6 gap-6">

                    <div class="col-span-6 sm:col-span-2">
                        <label for="account" class="block text-sm font-medium text-gray-700">Account Name</label>
                        <input type="text" readonly
                            wire:click="searchableOpenModal('account_id','account_name','accounts')"
                            wire:model="account_name" id="account" autocomplete="off"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="text" readonly wire:model.lazy="from_date" id="from_date" autocomplete="off"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="text" readonly wire:model.lazy="to_date" id="to_date" autocomplete="off"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="type" class="block text-sm font-medium text-gray-700">Transaction
                            Type</label>
                        <select wire:model.defer="type"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="unallocated">Unallocated</option>
                            <option value="allocated">Allocated</option>
                        </select>
                    </div>

                </div>

            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit"
                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    Fetch
                </button>
            </div>
        </form>
    </div>

    <div class="shadow sm:rounded-md sm:overflow-hidden mt-5">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $type == 'unallocated' ? 'Unallocated' : 'Allocated' }} Transactions
                </h3>
            </div>

            <div class="flex flex-col">
                <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle">
                        @if ($type == 'unallocated')
                            <div class="flex gap-4 px-6">

                                <div class="flex-1">
                                    <table class="border min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50 border-t">
                                            <tr>
                                                <th colspan="7" scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Credit Transactions - Unallocated Deposits
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                                    <input type="checkbox" wire:model="unselect_all_credit"
                                                        @if (empty($unselect_all_credit)) disabled @endif
                                                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    #
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Posting Date
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Voucher #
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Reference
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Amount
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Unallocated
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @if (!empty($unsettled_credit))
                                                @foreach ($unsettled_credit as $key => $uc)
                                                    <tr>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">

                                                            <input type="checkbox"
                                                                wire:model="credit_checkbox.{{ $uc['voucher_no'] }}"
                                                                @if ($first_check == 'credit' && $first_voucher != $uc['voucher_no']) disabled @endif
                                                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $uc['posting_date'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $uc['voucher_no'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $uc['reference'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                            {{ number_format($uc['credit'], 2) }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                            {{ number_format($uc['unallocated'], 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="5"
                                                        class="whitespace-nowrap border-r bg-gray-50 py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                        Total
                                                    </th>
                                                    <th
                                                        class="whitespace-nowrap border-r bg-gray-50 py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                        {{ number_format(collect($unsettled_credit)->sum('credit'), 2) }}
                                                    </th>
                                                    <th
                                                        class="whitespace-nowrap bg-gray-50 py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                        {{ number_format(collect($unsettled_credit)->sum('unallocated'), 2) }}
                                                    </th>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7"
                                                        class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-red-500 text-center">
                                                        No Entries found!
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex-1">
                                    <table class="border min-w-full divide-gray-300">
                                        <thead class="bg-gray-50 border-t">
                                            <tr>
                                                <th colspan="7" scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Unpaid - Sales Invoices
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                                    <input type="checkbox" wire:model="unselect_all_debit"
                                                        @if (empty($unselect_all_debit)) disabled @endif
                                                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    #
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Posting Date
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Voucher #
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Reference
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Amount
                                                </th>
                                                <th scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                    Unallocated
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @if (!empty($unsettled_debit))
                                                @foreach ($unsettled_debit as $key => $ud)
                                                    <tr>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">

                                                            <input type="checkbox"
                                                                wire:model="debit_checkbox.{{ $ud['voucher_no'] }}"
                                                                @if ($first_check == 'debit' && $first_voucher != $ud['voucher_no']) disabled @endif
                                                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $ud['posting_date'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $ud['voucher_no'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $ud['reference'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                            {{ number_format($ud['debit'], 2) }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                            {{ number_format($ud['unallocated'], 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="5"
                                                        class="whitespace-nowrap border-r bg-gray-50 py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                        Total
                                                    </th>
                                                    <th
                                                        class="whitespace-nowrap border-r bg-gray-50 py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                        {{ number_format(collect($unsettled_debit)->sum('debit'), 2) }}
                                                    </th>
                                                    <th
                                                        class="whitespace-nowrap bg-gray-50 py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                        {{ number_format(collect($unsettled_debit)->sum('unallocated'), 2) }}
                                                    </th>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7"
                                                        class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-red-500 text-center">
                                                        No Entries found!
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif ($type == 'allocated')
                            <div class="px-6">
                                <table class="border min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50 border-t">
                                        <tr>
                                            <th colspan="2" scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                            </th>
                                            <th colspan="5" scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Transactions - Allocated Deposits
                                            </th>
                                            <th colspan="1" scope="col"
                                                class="sticky border-r border-l top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                            </th>
                                            <th colspan="5" scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Paid - Sales Invoices
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 pl-2 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                                <input type="checkbox" wire:model="unselect_all_deallocate"
                                                    @if (empty($unselect_all_deallocate)) disabled @endif
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-r border-gray-300 bg-gray-50 bg-opacity-75 px-2 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                #
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Posting Date
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Voucher #
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Reference
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Amount
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Allocated
                                            </th>

                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-r border-l border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Posting Date
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Voucher #
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Reference
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Amount
                                            </th>
                                            <th scope="col"
                                                class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-right text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                                Allocated
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @if (!empty($settled_data))
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach ($settled_data as $key => $data)
                                                @php
                                                    $credit_count = count($data['credit']);
                                                    $count++;
                                                @endphp
                                                @for ($i = 0; $i < $credit_count; $i++)
                                                    <tr>
                                                        @if ($i == 0)
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap py-2 pl-2 text-sm text-gray-500 text-center">
                                                                <input type="checkbox"
                                                                    wire:model="deallocate_checkbox.{{ $data['debit'][0]['ledger_id'] }}"
                                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                            </td>
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap border-r py-2 pl-2 pr-2 text-sm text-gray-500 text-center">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                        @endif
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ date('d M Y', strtotime($data['credit'][$i]['credit_posting_date'])) }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $data['credit'][$i]['voucher_no'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                            {{ $data['credit'][$i]['reference'] }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                            {{ number_format($data['credit'][$i]['credit_amount'], 2) }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                            {{ number_format($data['credit'][$i]['allocated'], 2) }}
                                                        </td>

                                                        @if ($i == 0)
                                                            <th rowspan="{{ $credit_count }}"
                                                                class="sticky top-0 z-10 border-b border-r border-l border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">

                                                            </th>
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                                {{ date('d M Y', strtotime($data['debit'][0]['debit_posting_date'])) }}
                                                            </td>
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                                {{ $data['debit'][0]['voucher_no'] }}
                                                            </td>
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-center">
                                                                {{ $data['debit'][0]['reference'] }}
                                                            </td>
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                                {{ number_format($data['debit'][0]['debit_amount'], 2) }}
                                                            </td>
                                                            <td rowspan="{{ $credit_count }}"
                                                                class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-gray-500 text-right">
                                                                {{ number_format($data['debit'][0]['settled_amount'], 2) }}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endfor
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="13"
                                                    class="whitespace-nowrap py-2 pl-3 pr-3 text-sm text-red-500 text-center">
                                                    No Entries found!
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        @if ($type == 'unallocated')
            <div class="bg-white px-3 pb-4 text-right sm:px-6">

                <div class="space-x-2 justify-end">
                    <span class="align-middle pt-1 pr-4 text-gray-500 font-medium">Selected Credit</span>
                    <input type="text" value="{{ !empty($credit_checkbox) ? count($credit_checkbox) : 0 }}"
                        disabled
                        class="w-12 text-center text-gray-500 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <input type="text" value="{{ number_format($selected_credit_amount, 2) }}" readonly
                        class="w-48 text-right text-gray-500 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="space-x-2 justify-end align-middle pt-2 pb-4">
                    <span class="align-middle pt-1 pr-4 text-gray-500 font-medium">Selected Debit</span>
                    <input type="text" value="{{ !empty($debit_checkbox) ? count($debit_checkbox) : 0 }}" disabled
                        class="w-12 text-center text-gray-500 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <input type="text" value="{{ number_format($selected_debit_amount, 2) }}" readonly
                        class="w-48 text-right text-gray-500 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="button" wire:click.prevent="allocate" @if (empty($unsettled_credit) || empty($unsettled_debit)) disabled @endif
                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    Allocate
                </button>
            </div>
        @elseif ($type == 'allocated')
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="button" wire:click.prevent="deallocate" @if (empty($settled_data)) disabled @endif
                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    Deallocate
                </button>
            </div>
        @endif
    </div>

    @include('ams::include.searchable')
</div>

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script>
        let from_date = new Pikaday({
            field: document.getElementById('from_date'),
            format: "DD MMM YYYY"
        });

        let to_date = new Pikaday({
            field: document.getElementById('to_date'),
            format: "DD MMM YYYY"
        });

        from_date.setDate(new Date('{{ $from_date }}'));
        to_date.setDate(new Date('{{ $to_date }}'));

        document.addEventListener('livewire:load', () => {
            Livewire.on('focusInput', postId => {
                setTimeout(() => {
                    document.getElementById('searchable_query').focus();
                }, 300);
            });


        });

        window.addEventListener('title', event => {
            document.title = "GL: " + event.detail.name;
        })
    </script>
@endsection
