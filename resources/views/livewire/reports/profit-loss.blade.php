<div class=" {{ count($heading) > 7 ? '' : 'max-w-7xl' }} mx-auto py-6 sm:px-6 lg:px-8">

    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">


            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-2">
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="text" wire:model.lazy="from_date"  readonly  id="from_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>


                <div class="col-span-6 sm:col-span-2">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="text" readonly wire:model.lazy="to_date" id="to_date" autocomplete="off"
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
                <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Profit and Loss (P&L)</h3>
                <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                <p class="text-md  font-sm text-gray-500 text-center">Statement
                    Period {{ date('d M, Y',strtotime($from_date)) }} to {{ date('d M, Y',strtotime($to_date)) }} </p>

            </div>
        </div>
        <div>
            @if(!empty($heading))
                <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            Account Head
                        </th>

                        @foreach($heading as $h)
                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                {{ date('M Y',strtotime($h)) }}
                            </th>

                        @endforeach

                        <th scope="col"
                            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            Total
                        </th>

                    </tr>
                    </thead>
                    <tbody class="bg-white  ">
                    <tr>
                        <th scope="col" colspan="{{ 2 + count($heading) }}"
                            class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                            Revenue
                        </th>
                    </tr>
                    @foreach(collect($report)->where('type','Income')->groupBy('account_id') as $key => $en)
                        <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                            <td class="px-2   py-2   border-r text-sm   text-gray-500">

                                {{ $en->first()['name'] }}
                            </td>
                            @foreach($heading as $h)
                                @php
                                    $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                @endphp
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @if(!empty($first))
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th scope="col"
                            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            Total Revenue
                        </th>

                        @foreach($heading as $h)
                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance'),2) }}
                            </th>

                        @endforeach

                        <th scope="col"
                            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance'),2) }}
                        </th>

                    </tr>

                    <tr>
                        <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                    </tr>


                    <tr>
                        <th scope="col" colspan="{{ 2 + count($heading) }}"
                            class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                            Less Cost of Sales
                        </th>
                    </tr>

                    @foreach(collect($report)->where('p_ref','cost-of-sales-4')->groupBy('account_id') as $key => $en)
                        <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                            <td class="px-2   py-2   border-r text-sm   text-gray-500">

                                {{ $en->first()['name'] }}
                            </td>
                            @foreach($heading as $h)
                                @php
                                    $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                @endphp
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @if(!empty($first))
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th scope="col"
                            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            Total Expenses
                        </th>

                        @foreach($heading as $h)
                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                            </th>

                        @endforeach

                        <th scope="col"
                            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                        </th>

                    </tr>


                    <tr>
                        <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                    </tr>


                    <tr>
                        <th scope="col"
                            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            Gross Profit
                        </th>

                        @foreach($heading as $h)
                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                            </th>

                        @endforeach

                        <th scope="col"
                            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                        </th>

                    </tr>

                    <tr>
                        <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                    </tr>


                    <tr>
                        <th scope="col" colspan="{{ 2 + count($heading) }}"
                            class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                            Other Expenses
                        </th>
                    </tr>

                    @foreach(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->groupBy('account_id') as $key => $en)
                        <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                            <td class="px-2   py-2   border-r text-sm   text-gray-500">

                                {{ $en->first()['name'] }}
                            </td>
                            @foreach($heading as $h)
                                @php
                                    $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                @endphp
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @if(!empty($first))
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th scope="col"
                            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            Total Other Expenses
                        </th>

                        @foreach($heading as $h)
                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                            </th>

                        @endforeach

                        <th scope="col"
                            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->sum('balance'),2) }}
                        </th>

                    </tr>


                    <tr>
                        <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                    </tr>

                    <tr>
                        <th scope="col"
                            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            Net Profit/(Loss)
                        </th>

                        @foreach($heading as $h)
                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('type','Expenses')->where('month',$h)->sum('balance'),2) }}
                            </th>

                        @endforeach

                        <th scope="col"
                            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('type','Expenses')->sum('balance'),2) }}
                        </th>

                    </tr>
                    </tbody>
                </table>
            @endif
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
