<div>
    <div class="mb-5 shadow sm:rounded-md sm:overflow-hidden bg-white">
        @if ($errors->any())
            <div class="px-6 pt-6">
                <div class="p-4 rounded-md bg-red-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There {{ $errors->count() > 1? 'were' : 'was' }} {{ $errors->count() }} {{
                                $errors->count() > 1? 'errors' : 'error' }}
                                with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="pl-5 space-y-1 list-disc">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($success) || session()->has('success'))
            <div class="px-6 pt-6">
                <div class="p-4  rounded-md bg-green-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check-circle -->
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                @if(!empty($success))
                                    {{ $success }}
                                @elseif(session()->has('success'))
                                    {{ session('success') }}
                                @endif
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" wire:click="$set('success', '')"
                                        class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                    <span class="sr-only">Dismiss</span>
                                    <!-- Heroicon name: x -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="px-6 pt-6">
                <div class="p-4 rounded-md bg-red-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There was an error with your submission.
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{session('error')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
        <form>
            <div class="bg-white  mb-5 rounded-md overflow-hidden">
                <div class="py-6 px-4 sm:p-6 flex justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Approved Petty
                        Expenses</h3>
                </div>
                <table class="min-w-full table-fixed  ">
                    <thead class="">
                    <tr class="">
                        <th scope="col"
                            class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            #
                        </th>
                        <th scope="col" style="width: 110px;"
                            class="px-2 py-2   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Invoice Date
                        </th>
                        <th scope="col"
                            class="px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Vendor
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Account Head
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Description
                        </th>

                        <th scope="col"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                            Amount
                        </th>
                        <th scope="col"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Claimed By
                        </th>
                        <th scope="col"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Approved By
                        </th>
                        <th scope="col"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Attachment
                        </th>

                    </tr>
                    </thead>
                    <tbody class="bg-white  ">

                    @forelse($petty_expenses_list as $pe)
                        <tr class="{{ $loop->first ? 'border-t': '' }}   border-b">
                            <td class="px-2 py-2  border-r text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ date('d M, Y',strtotime($pe['invoice_date'])) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($pe['vendor_name']) }}<br>{{ ucwords($pe['vendor_contact_no']) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($pe['account_head']) }}
                            </td>
                            <td class=" px-2 py-2 border-r text-sm text-gray-500 whitespace-initial"
                                style="width: 400px !important;">
                                {{ ucfirst($pe['description']) }}
                            </td>
                            <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                {{ number_format($pe['amount'],2) }}
                            </td>
                            <td class="px-2 py-2 border-r text-left text-sm text-gray-500">
                                {{ucwords($pe['claimed_by'])}}<br>
                                @ {{ date('d M, Y',strtotime($pe['claimed_at'])) }}
                            </td>
                            <td class="px-2 py-2 border-r text-left text-sm text-gray-500">
                                {{ucwords($pe['approved_by'])}}<br>
                                @ {{ date('d M, Y',strtotime($pe['approved_at'])) }}
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

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-sm text-red-500 rounded-md overflow-hidden">
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
        </form>
    </div>
</div>