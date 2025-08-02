<div>
    <div class=" {{ count($heading) > 7 ? '' : 'max-w-7xl' }}  py-6 sm:px-6 lg:px-8">
        <div class="mb-4 shadow sm:rounded-md sm:overflow-hiddens">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">


                <div class="grid grid-cols-8 gap-6">
                    <div class="col-span-6 sm:col-span-2">
                        <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="text" wire:model.lazy="from_date" readonly id="from_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>


                    <div class="col-span-6 sm:col-span-2">
                        <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="text" readonly wire:model.lazy="to_date" id="to_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="closing_vouchers" class="block text-sm font-medium text-gray-700">Closing Vouchers</label>
                        <select wire:model.defer='closing_vouchers'
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="show">Show</option>
                            <option value="hide">Hide</option>
                        </select>
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <label for="templates" class="block text-sm font-medium text-gray-700">Templates</label>
                        <select wire:model.defer='template_id'
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">--Select--</option>
                            @foreach($all_templates as $template)
                                <option value="{{$template['id']}}">{{ucwords($template['report_name'])}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-8 flex justify-end items-end mt-6">
                        <button type="button" wire:click="search" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading wire:target="search">Searching ...</span>
                            <span wire:loading.remove wire:target="search">Search</span>
                        </button>
                        @if(!empty($report))
                            @can('4.pnl-template-report')
                                <a href="{{ url('accounts/reports/profit-and-loss/date-wise/export') }}?from_date={{ urlencode($from_date) }}&to_date={{ urlencode($to_date) }}&closing_vouchers={{ $closing_vouchers }}&template_id={{$template_id}}"
                                   target="_blank"
                                   class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none">
                                    Export.csv
                                </a>
                            @endcan
                        @endif
                    </div>

                </div>

                <div>
                    <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Profit and Loss (P&L) - Date
                        Wise</h3>
                    <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                    <p class="text-md  font-sm text-gray-500 text-center">Statement
                        Period {{ date('d M, Y',strtotime($from_date)) }}
                        to {{ date('d M, Y',strtotime($to_date)) }} </p>

                </div>

                @if ($errors->any())

                    <div class="rounded-md bg-red-50 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Heroicon name: x-circle -->
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    @php
                                        $count = count($errors->all());
                                    @endphp
                                    There {{ $count > 1 ? "were {$count} errors": "was {$count} error" }} with your
                                    submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">

                                        @foreach ($errors->all() as $error)
                                            <li>{!! $error !!}</li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="overflow-auto" style="width: 100%"
                 @if(count($heading)>9)
                     style="width: {{count($heading)*100}}px;"
                    @endif>
                @if(!empty($heading))
                    <table class="min-w-full table-fixed divide-y divide-gray-200 ">
                        <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider bg-gray-100">
                                Account Head
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    {{ $h  }}
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
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                                Revenue
                            </th>
                            <th scope="col" colspan="{{ 1 + count($heading) }}"
                                class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                            </th>
                        </tr>
                        @foreach(collect($report)->where('type','Income')->groupBy('account_id') as $key => $en)
                            <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                                <td class="px-2   py-2   border-r text-sm   text-gray-500 {{ $loop->even ? 'bg-gray-50' :'bg-white' }}"
                                    style="position:sticky; left: 0;">

                                    {{ $en->first()['name'] }}
                                </td>
                                @foreach($heading as $h)
                                    @php
                                        $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                    @endphp
                                    <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                        @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                            @if(!empty($first))
                                                <a target="_blank" class="hover:text-gray-900"
                                                   href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($first['date']))}}&to={{date('d M Y',strtotime($first['date']))}}">
                                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        @endif

                                    </td>
                                @endforeach
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                        <a target="_blank" class="hover:text-gray-900"
                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider bg-white">
                                Total Revenue
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance'),2) }}
                                    @endif
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance'),2) }}
                                @endif
                            </th>

                        </tr>

                        <tr>
                            <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                        </tr>


                        <tr class="bg-white">
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                                Less Cost of Sales
                            </th>
                            <th scope="col" colspan="{{ 1 + count($heading) }}"
                                class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 ">
                            </th>
                        </tr>

                        @foreach(collect($report)->where('p_ref','cost-of-sales-4')->groupBy('account_id') as $key => $en)
                            <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                                <td class="px-2   py-2   border-r text-sm   text-gray-500 {{ $loop->even ? 'bg-gray-50' :'bg-white' }}"
                                    style="position:sticky; left: 0;">

                                    {{ $en->first()['name'] }}
                                </td>
                                @foreach($heading as $h)
                                    @php
                                        $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                    @endphp
                                    <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                        @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                            @if(!empty($first))
                                                <a target="_blank" class="hover:text-gray-900"
                                                   href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($first['date']))}}&to={{date('d M Y',strtotime($first['date']))}}">
                                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                        <a target="_blank" class="hover:text-gray-900"
                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider bg-white">
                                Total Expenses
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                    @endif
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                                @endif
                            </th>

                        </tr>


                        <tr>
                            <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                        </tr>


                        <tr>
                            <th scope="col"
                                class="  px-2 bg-white  border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider"
                                style="position:sticky; left: 0;">
                                Gross Profit
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                    @endif
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                                @endif
                            </th>

                        </tr>

                        <tr>
                            <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                        </tr>


                        <tr>
                            <th scope="col"
                                style="position:sticky; left: 0;"
                                class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 bg-white ">
                                Other Expenses
                            </th>
                            <th scope="col" colspan="{{ 1 + count($heading) }}"
                                class="  px-2  text-left border-r py-2  text-sm font-bold text-gray-500 bg-white ">
                            </th>

                        </tr>

                        @foreach(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->groupBy('account_id') as $key => $en)
                            <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                                <td class="px-2   py-2   border-r text-sm   text-gray-500 {{ $loop->even ? 'bg-gray-50' :'bg-white' }} "
                                    style="position:sticky; left: 0;">

                                    {{ $en->first()['name'] }}
                                </td>
                                @foreach($heading as $h)
                                    @php
                                        $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                    @endphp
                                    <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                        @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                            @if(!empty($first))
                                                <a target="_blank" class="hover:text-gray-900"
                                                   href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($first['date']))}}&to={{date('d M Y',strtotime($first['date']))}}">
                                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                        <a target="_blank" class="hover:text-gray-900"
                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider bg-white">
                                Total Other Expenses
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                    @endif
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->sum('balance'),2) }}
                                @endif
                            </th>

                        </tr>


                        <tr>
                            <th colspan="{{ 2+count($heading) }}">&nbsp;</th>
                        </tr>

                        <tr>
                            <th scope="col" style="position:sticky; left: 0;"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider bg-white">
                                Net Profit/(Loss)
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('type','Expenses')->where('month',$h)->sum('balance'),2) }}
                                    @endif
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @if(auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('type','Expenses')->sum('balance'),2) }}
                                @endif
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
</div>

