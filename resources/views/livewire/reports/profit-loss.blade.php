@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <h1>Profit and Loss</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h4 class="card-title"><b>Search</b></h4>
                                    <div class="card-tools">
                                        {{--                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
                                        {{--                                        <i class="fas fa-minus"></i>--}}
                                        {{--                                    </button>--}}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="from_date" class="font-weight-normal">From Date</label>
                                                <input type="text" wire:model.lazy="from_date" id="from_date"
                                                       autocomplete="off"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">

                                                <label for="to_date" class="font-weight-normal">To Date</label>
                                                <input type="text" wire:model.lazy="to_date" id="to_date"
                                                       autocomplete="off"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4" style="margin-top: 32px">
                                            <div class="form-group">
                                                <button type="button" wire:click="search" wire:loading.attr="disabled"
                                                        class="btn btn-primary">
                                                    <span wire:loading wire:target="search">Searching ...</span>
                                                    <span wire:loading.remove wire:target="search">Search</span>
                                                </button>
                                                <button type="button" wire:click="resetSearch"
                                                        wire:loading.attr="disabled"
                                                        class="btn btn-danger">
                                                    Reset
                                                </button>
                                                @if(!empty($report))
                                                    <a href="{{'profit-and-loss/export'}}?from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}"
                                                       target="_blank"
                                                       class="btn btn-success">
                                                        Export.csv
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header pt-0">
                                    <h5 class="d-flex justify-content-center p-0 m-0"><b>Profit and Loss (P&L)</b></h5>
                                    <p class="text-center p-0 m-0 text-muted">{{ env('APP_NAME') }}</p>
                                    <p class="text-center p-0 m-0 text-muted">Statement
                                        Period {{ date('d M, Y',strtotime($from_date)) }}
                                        to {{ date('d M, Y',strtotime($to_date)) }} </p>
                                </div>

                                <div class="card-body p-0">
                                    @if(!empty($heading))
                                        <table class="table table-bordered border-0">
                                            <thead class="">
                                            <tr>
                                                <th class="text-muted px-2 py-1">Account Head</th>
                                                @foreach($heading as $h)
                                                    <th scope="col"
                                                        class="text-center text-muted px-2 py-1">
                                                        {{ date('M Y',strtotime($h)) }}
                                                    </th>
                                                @endforeach
                                                <th class="text-center text-muted px-2 py-1">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="col" colspan="{{ 2 + count($heading) }}"
                                                    class=" px-2 py-1 bg-white ">
                                                    Revenue
                                                </th>
                                            </tr>
                                            @foreach(collect($report)->where('type','Income')->groupBy('account_id') as $key => $en)
                                                <tr>
                                                    <td class="px-2 py-1 text-muted">
                                                        {{ $en->first()['name'] }}
                                                    </td>
                                                    @foreach($heading as $h)
                                                        @php
                                                            $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                                        @endphp
                                                        <td class="text-center px-2 py-1">
                                                            @cannot('2.hide-income')

                                                                @if(!empty($first))
                                                                    <a target="_blank" class="text-muted"
                                                                       href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($h.'-01'))}}&to={{date('t M Y',strtotime($h.'-01'))}}">
                                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            @endcannot
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center px-2 py-1">
                                                        @cannot('2.hide-income')

                                                            <a target="_blank" class="text-muted"
                                                               href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                                            </a>
                                                        @endcannot
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th scope="col"
                                                    class="px-2 py-1 bg-white">
                                                    Total Revenue
                                                </th>

                                                @foreach($heading as $h)
                                                    <th scope="col"
                                                        class="text-center bg-white px-2 py-1">
                                                        @cannot('2.hide-income')

                                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance'),2) }}
                                                        @endcannot
                                                    </th>

                                                @endforeach

                                                <th scope="col"
                                                    class="text-center bg-white px-2 py-1">
                                                    @cannot('2.hide-income')

                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance'),2) }}
                                                    @endcannot
                                                </th>

                                            </tr>

                                            <tr>
                                                <th colspan="{{ 2+count($heading) }}" class="bg-white px-2 py-1">
                                                    &nbsp;
                                                </th>
                                            </tr>


                                            <tr>
                                                <th scope="col" colspan="{{ 2 + count($heading) }}"
                                                    class="px-2 py-1 bg-white">
                                                    Less Cost of Sales
                                                </th>
                                            </tr>

                                            @foreach(collect($report)->where('p_ref','cost-of-sales-4')->groupBy('account_id') as $key => $en)
                                                <tr class="">
                                                    <td class="px-2 py-1 text-muted">
                                                        {{ $en->first()['name'] }}
                                                    </td>
                                                    @foreach($heading as $h)
                                                        @php
                                                            $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                                        @endphp
                                                        <td class="text-center px-2 py-1">
                                                            @cannot('2.hide-expenses')
                                                                @if(!empty($first))
                                                                    <a target="_blank" class="text-muted"
                                                                       href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($h.'-01'))}}&to={{date('t M Y',strtotime($h.'-01'))}}">
                                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            @endcannot
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center px-2 py-1">
                                                        @cannot('2.hide-expenses')
                                                            <a target="_blank" class="text-muted"
                                                               href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                                                {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                                            </a>
                                                        @endcannot
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th scope="col"
                                                    class="px-2 py-1 bg-white">
                                                    Total Expenses
                                                </th>

                                                @foreach($heading as $h)
                                                    <th scope="col"
                                                        class="text-center px-2 py-1 bg-white">
                                                        @cannot('2.hide-expenses')
                                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                                        @endcannot
                                                    </th>

                                                @endforeach

                                                <th scope="col"
                                                    class="text-center px-2 py-1 bg-white">
                                                    @cannot('2.hide-expenses')
                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                                                    @endcannot
                                                </th>
                                            </tr>


                                            <tr>
                                                <th colspan="{{ 2+count($heading) }}" class="px-2 py-1 bg-white">
                                                    &nbsp;
                                                </th>
                                            </tr>


                                            <tr>
                                                <th scope="col"
                                                    class="px-2 py-1 bg-white">
                                                    Gross Profit
                                                </th>

                                                @foreach($heading as $h)
                                                    <th scope="col"
                                                        class="text-center px-2 py-1 bg-white">
                                                        @cannot('2.hide-income')

                                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                                        @endcannot
                                                    </th>

                                                @endforeach

                                                <th scope="col"
                                                    class="text-center px-2 py-1 bg-white">
                                                    @cannot('2.hide-income')

                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                                                    @endcannot
                                                </th>

                                            </tr>

                                            <tr>
                                                <th colspan="{{ 2+count($heading) }}" class="px-2 py-1 bg-white">
                                                    &nbsp;
                                                </th>
                                            </tr>


                                            <tr>
                                                <th scope="col" colspan="{{ 2 + count($heading) }}"
                                                    class="px-2 py-1 bg-white">
                                                    Other Expenses
                                                </th>
                                            </tr>

                                            @foreach(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->groupBy('account_id') as $key => $en)
                                                <tr class="">
                                                    <td class="px-2 py-1  text-muted">
                                                        {{ $en->first()['name'] }}
                                                    </td>
                                                    @foreach($heading as $h)
                                                        @php
                                                            $first =  collect($report)->where('account_id',$key)->where('month',$h)->first();
                                                        @endphp
                                                        <td class="text-center px-2 py-1 ">
                                                            @cannot('2.hide-expenses')

                                                                @if(!empty($first))
                                                                    <a target="_blank" class="text-muted"
                                                                       href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($h.'-01'))}}&to={{date('t M Y',strtotime($h.'-01'))}}">
                                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            @endcannot
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center px-2 py-1 ">
                                                        <a target="_blank" class="text-muted"
                                                           @cannot('2.hide-expenses')
                                                               href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                                            @endcannot
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th scope="col"
                                                    class="px-2 py-1 bg-white">
                                                    Total Other Expenses
                                                </th>

                                                @foreach($heading as $h)
                                                    <th scope="col"
                                                        class="text-center px-2 py-1 bg-white ">
                                                        @cannot('2.hide-expenses')
                                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                                        @endcannot
                                                    </th>

                                                @endforeach

                                                <th scope="col"
                                                    class="text-center px-2 py-1 bg-white">
                                                    @cannot('2.hide-expenses')
                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->sum('balance'),2) }}
                                                    @endcannot
                                                </th>

                                            </tr>


                                            <tr>
                                                <th colspan="{{ 2+count($heading) }}" class="px-2 py-1 bg-white">
                                                    &nbsp;
                                                </th>
                                            </tr>

                                            <tr>
                                                <th scope="col"
                                                    class=" px-2 py-1 bg-white">
                                                    Net Profit/(Loss)
                                                </th>

                                                @foreach($heading as $h)
                                                    <th scope="col"
                                                        class="text-center px-2 py-1 bg-white">
                                                        @cannot('2.hide-income')
                                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('type','Expenses')->where('month',$h)->sum('balance'),2) }}
                                                        @endcannot
                                                    </th>

                                                @endforeach

                                                <th scope="col"
                                                    class="text-center px-2 py-1 bg-white">
                                                    @cannot('2.hide-income')

                                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('type','Expenses')->sum('balance'),2) }}
                                                    @endcannot
                                                </th>

                                            </tr>
                                            </tbody>
                                        </table>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
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
    @endpush
@else
    <div class=" {{ count($heading) > 7 ? '' : 'max-w-7xl' }} mx-auto py-6 sm:px-6 lg:px-8">

        <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">


                <div class="grid grid-cols-6 gap-6">
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
                                <a href="{{'profit-and-loss/export'}}?from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}"
                                   target="_blank"
                                   class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">
                                    Export.csv
                                </a>
                            @endif

                        </div>


                    </div>

                </div>

                <div>
                    <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Profit and Loss (P&L)</h3>
                    <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                    <p class="text-md  font-sm text-gray-500 text-center">Statement
                        Period {{ date('d M, Y',strtotime($from_date)) }}
                        to {{ date('d M, Y',strtotime($to_date)) }} </p>

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
                                        @cannot('2.hide-income')
                                            @if(!empty($first))
                                                <a target="_blank" class="hover:text-gray-900"
                                                   href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($h.'-01'))}}&to={{date('t M Y',strtotime($h.'-01'))}}">
                                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        @endcannot
                                    </td>
                                @endforeach
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @cannot('2.hide-income')

                                        <a target="_blank" class="hover:text-gray-900"
                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                        </a>
                                    @endcannot
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
                                    @cannot('2.hide-income')
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance'),2) }}
                                    @endcannot
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @cannot('2.hide-income')

                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance'),2) }}
                                @endcannot

                            </th>

                        </tr>

                        <tr>
                            <th colspan="{{ 2+count($heading) }}" class="bg-white">&nbsp;</th>
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
                                        @cannot('2.hide-expenses')
                                            @if(!empty($first))
                                                <a target="_blank" class="hover:text-gray-900"
                                                   href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($h.'-01'))}}&to={{date('t M Y',strtotime($h.'-01'))}}">
                                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        @endcannot
                                    </td>
                                @endforeach
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @cannot('2.hide-expenses')
                                        <a target="_blank" class="hover:text-gray-900"
                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                        </a>
                                    @endcannot
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
                                    @cannot('2.hide-expenses')
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                    @endcannot
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @cannot('2.hide-expenses')
                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                                @endcannot
                            </th>

                        </tr>


                        <tr>
                            <th colspan="{{ 2+count($heading) }}" class="bg-white">&nbsp;</th>
                        </tr>


                        <tr>
                            <th scope="col"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                                Gross Profit
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @cannot('2.hide-income')
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                    @endcannot
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @cannot('2.hide-income')
                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->sum('balance'),2) }}
                                @endcannot
                            </th>

                        </tr>

                        <tr>
                            <th colspan="{{ 2+count($heading) }}" class="bg-white">&nbsp;</th>
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
                                        @cannot('2.hide-expenses')

                                            @if(!empty($first))
                                                <a target="_blank" class="hover:text-gray-900"
                                                   href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($h.'-01'))}}&to={{date('t M Y',strtotime($h.'-01'))}}">
                                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($first['balance'],2) }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        @endcannot
                                    </td>
                                @endforeach
                                <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                                    @cannot('2.hide-expenses')
                                        <a target="_blank" class="hover:text-gray-900"
                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$key}}&from={{date('d M Y',strtotime($from_date))}}&to={{date('t M Y',strtotime($to_date))}}">
                                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('account_id',$key)->sum('balance'),2) }}
                                        </a>
                                    @endcannot

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
                                    @cannot('2.hide-expenses')
                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->where('month',$h)->sum('balance'),2) }}
                                    @endcannot

                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @cannot('2.hide-expenses')
                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->sum('balance'),2) }}
                                @endcannot
                            </th>

                        </tr>


                        <tr>
                            <th colspan="{{ 2+count($heading) }}" class="bg-white">&nbsp;</th>
                        </tr>

                        <tr>
                            <th scope="col"
                                class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                                Net Profit/(Loss)
                            </th>

                            @foreach($heading as $h)
                                <th scope="col"
                                    class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                    @cannot('2.hide-income')

                                        {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('type','Expenses')->where('month',$h)->sum('balance'),2) }}
                                    @endcannot
                                </th>

                            @endforeach

                            <th scope="col"
                                class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                                @cannot('2.hide-income')

                                    {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat(collect($report)->where('type','Income')->sum('balance') - collect($report)->where('type','Expenses')->sum('balance'),2) }}
                                @endcannot
                            </th>

                        </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endif




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
