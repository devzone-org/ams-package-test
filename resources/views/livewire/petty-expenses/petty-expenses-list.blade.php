<div>
    <div class="mb-5 shadow sm:rounded-md sm:overflow-hidden bg-white">
        <div class="p-4 px-6 flex justify-between border-b">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Search Filters</h3>
            <a href="/accounts/petty-expenses">
                <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Add Petty Expenses
                </button>
            </a>
        </div>
        <form wire:submit.prevent="search">
            <div class="py-6 px-4 space-y-6 sm:p-6">
                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-6 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Invoice Date </label>
                        <input type="date" wire:model.lazy="filter.invoice_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="col-span-6 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Name </label>
                        <input type="text" wire:model.lazy="filter.name" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Contact # </label>
                        <input type="text" wire:model.lazy="filter.contact_no" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Account Head </label>
                        <select wire:model.defer="filter.account_head_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            @foreach($fetch_account_heads as $a)
                                <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>


                <div class="w-full flex justify-end">
                    <div>
                        <button type="submit" wire:loading.attr="disabled"
                                class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>

                        <button type="button" wire:click="clear" wire:loading.attr="disabled"
                                class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reset
                        </button>
                    </div>

                </div>

            </div>
        </form>

    </div>
    <div class="shadow rounded-md">

        <div class="bg-white  mb-5 rounded-md">
            <div class="py-6 px-4 sm:p-6 flex justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">{{ucwords($type)}} Petty
                    Expenses</h3>
            </div>
            <table class="min-w-full table-fixed  ">
                <thead class="">
                <tr class="">
                    <th scope="col"
                        class="w-7 px-2 rounded-tl-md bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                    </th>
                    <th scope="col"
                        class="w-7 px-2 rounded-tl-md bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                        #
                    </th>
                    <th scope="col"
                        class="px-2 py-2   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Invoice Date
                    </th>
                    <th scope="col"
                        class="px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Name
                    </th>
                    <th scope="col"
                        class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Contact
                    </th>
                    <th scope="col"
                        class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Account Head
                    </th>
                    <th scope="col"
                        class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Attachment
                    </th>
                    <th scope="col"
                        class="px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                        Amount
                    </th>
                    <th scope="col"
                        class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Status
                    </th>
                    <th scope="col"
                        class="rounded-tr-md cursor-pointer bg-gray-100    border-t px-2 py-2     text-left  text-sm font-bold text-gray-500 uppercase tracking-wider">
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white  ">

                @forelse($petty_expenses_list as $pe)
                    <tr class="{{ $loop->first ? 'border-t': '' }} {{ $loop->even ? 'bg-gray-50':'' }}   border-b">
                        <td class="px-2 py-2  border-r text-sm text-gray-500">
                        </td>
                        <td class="px-2 py-2  border-r text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-2 py-2 border-r text-sm text-gray-500">
                            {{ date('d M, Y',strtotime($pe['invoice_date'])) }}
                        </td>
                        <td class="px-2 py-2 border-r text-sm text-gray-500">
                            {{ ucwords($pe['name']) }}
                        </td>
                        <td class="px-2 py-2 border-r text-sm text-gray-500">
                            {{ ucwords($pe['contact_no']) }}
                        </td>
                        <td class="px-2 py-2 border-r text-sm text-gray-500">
                            {{ ucwords($pe['account_head']) }}
                        </td>
                        <td class="px-2 py-2 border-r text-sm text-gray-500">
                            @if(empty($pe['attachment']))
                                -
                            @else
                                <a href="{{ env('AWS_URL').$pe['attachment'] }}"
                                   class="text-yellow-500 font-medium" target="_blank">
                                    View Attachment
                                </a>
                            @endif
                        </td>

                        <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                            {{ number_format($pe['amount'],2) }}
                        </td>
                        <td class="px-2 py-2 border-r text-sm text-gray-500"></td>

                        <td class="px-2 py-2 border-r text-sm text-gray-500">
                            <a href="/accounts/petty-expenses/{{$pe['id']}}"
                               class="text-indigo-500 font-medium" target="_blank">
                                Edit
                            </a>
                            |
                            <a href="/accounts/petty-expenses/{{$pe['id']}}"
                               class="text-red-500 font-medium" target="_blank">
                                Delete
                            </a>


                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-sm text-red-500">
                            <div class="flex items-center justify-center py-5">
                                <div class="flex justify-between">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                         viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-2">No Records Yet!</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                @endforelse


                </tbody>
            </table>
        </div>

    </div>
</div>