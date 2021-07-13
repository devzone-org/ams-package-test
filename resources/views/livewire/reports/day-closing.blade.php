<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-2">
                    <label for="users" class="block text-sm font-medium text-gray-700">User</label>
                    <select wire:model.defer="user_account_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""></option>
                        @foreach($users as $u)
                            <option value="{{ $u['account_id'] }}">{{ $u['account_name'] }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-span-6 sm:col-span-1">
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" wire:model.defer="from_date" id="from_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="col-span-6 sm:col-span-1">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" wire:model.defer="to_date" id="to_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <div class="mt-6 flex-shrink-0 flex ">
                        <button type="button" wire:click="search"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                        <button type="button" wire:click="resetSearch"
                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reset
                        </button>
                    </div>
                </div>

            </div>
            <div>
                <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Day Closing Report</h3>
                <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                <p class="text-md  font-sm text-gray-500 text-center">Statement
                    Period {{ date('d M, Y',strtotime($from_date)) }} to {{ date('d M, Y',strtotime($to_date)) }} </p>
            </div>
        </div>
        <div>
            <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th scope="col"
                        class="  px-2  text-center  border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                        Closing Date
                    </th>
                    <th scope="col"
                        class="  px-2  text-center border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                        User ID
                    </th>

                    <th scope="col"
                        class="  px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Closed By
                    </th>
                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Close At
                    </th>

                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        System Cash
                    </th>

                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Physical Cash
                    </th>

                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Amount Retained
                    </th>

                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Adjustment
                    </th>

                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Amount Transferred
                    </th>


                    <th scope="col"
                        class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                        Transfer To
                    </th>


                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                @foreach($report as $r)
                    <tr class="">
                        <td class="px-2  text-center py-2   border-r text-sm   text-gray-500">
                            {{ date('d M Y',strtotime($r['created_at'])) }}
                        </td>
                        <td class="px-2  text-center py-2    border-r text-sm text-gray-500">
                            {{ $r['user_id'] }}
                        </td>
                        <td class="px-2  py-2  text-center  border-r text-sm text-gray-500">
                            {{ $r['close_by'] }}
                        </td>
                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ date('h:i A',strtotime($r['created_at'])) }}
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ number_format($r['closing_balance']) }}
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ number_format($r['physical_cash']) }}
                        </td>
                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ number_format($r['cash_retained']) }}
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            @if($r['closing_balance'] - $r['physical_cash']<=0)
                            {{ number_format(abs($r['closing_balance'] - $r['physical_cash'])) }}
                                @else
                                ({{number_format(abs($r['closing_balance'] - $r['physical_cash'])) }})
                            @endif
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ number_format($r['physical_cash'] - $r['cash_retained']) }}
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ $r['transfer_name'] }}
                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

