<div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
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
                    <input type="text" wire:model.lazy="from_date" readonly id="from_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="col-span-6 sm:col-span-1">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="text" wire:model.lazy="to_date" readonly id="to_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <div class="mt-6 flex-shrink-0 flex ">
                        <button type="button" wire:click="search" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading wire:target="search">Searching ...</span>
                            <span wire:loading.remove wire:target="search">Search</span>
                        </button>
                        <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"
                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reset
                        </button>

                        @if(!empty($report))
                        <a href="{{'day-closing/export'}}?id={{$user_account_id}}&from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}" target="_blank"
                           class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">
                            Export.xls
                        </a>
                            @endif
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
                        class="  px-2  text-center  border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                        Voucher
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
                        <td class="px-2 text-center py-2   border-r text-sm   text-gray-500">
                            <a class="font-medium text-indigo-600 hover:text-indigo-500" href="javascript:void(0);" onclick="window.open('{{ url('accounts/journal/voucher/print').'/'.$r['voucher_no'] }}','voucher-print-{{$r['voucher_no']}}','height=500,width=800');">{{ $r['voucher_no'] }}</a>
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
                            {{ number_format($r['closing_balance'],2) }}
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ number_format($r['physical_cash']) }}
                        </td>
                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            {{ number_format($r['cash_retained']) }}
                        </td>

                        <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                            @if($r['closing_balance'] - $r['physical_cash']<=0)
                            {{ number_format(abs($r['closing_balance'] - $r['physical_cash']),2) }}
                                @else
                                ({{number_format(abs($r['closing_balance'] - $r['physical_cash']),2) }})
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

    </script>
@endsection


