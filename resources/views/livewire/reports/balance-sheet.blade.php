@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <h1>Balance Sheet</h1>
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
                                <div class="card-body p-0 px-4 pt-3">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="from_date" class="font-weight-normal">As At</label>
                                                <input type="text" wire:model.lazy="asat" id="from_date"
                                                       autocomplete="off"
                                                       class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4 pt-4">

                                            <div class="form-group" id="action-buttons" wire:key="action-buttons">
                                                <button type="button" wire:click="search" wire:loading.attr="disabled"
                                                        class="btn btn-primary" id="search-btn" wire:key="search-btn">
                                                    <span wire:loading wire:target="search">Searching ...</span>
                                                    <span wire:loading.remove wire:target="search">Search</span>
                                                </button>

                                                <button type="button" wire:click="resetSearch"
                                                        wire:loading.attr="disabled"
                                                        class="btn btn-danger" id="reset-btn" wire:key="reset-btn">
                                                    Reset
                                                </button>

                                                <a href="{{'balance-sheet/export'}}?asat={{$asat}}" target="_blank"
                                                   class="btn btn-success" id="export-btn" wire:key="export-btn">
                                                    Export.csv
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header pt-0">
                                    <h5 class="d-flex justify-content-center p-0 m-0"><b>Statement of Financial
                                            Position</b></h5>
                                    <p class="text-center p-0 m-0 text-muted">{{ env('APP_NAME') }}</p>
                                    <p class="text-center text-muted">As At {{ date('d F Y',strtotime($asat)) }} </p>
                                </div>

                                <div class="card-body p-0 m-0">
                                    <table class="table table-bordered border-0">
                                        <thead class="">
                                        <tr>
                                            <td class="col-8 border-right-0 px-2 py-1"></td>
                                            <td class="col-2 text-muted border-right-0 border-left-0 px-2 py-1">{{ env('CURRENCY','PKR') }}</td>
                                            <td class="col-2 text-muted border-right-0 border-left-0 px-2 py-1">{{ env('CURRENCY','PKR') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $count=0;
                                        @endphp
                                        @foreach(collect($level3)->groupBy('type')->toArray() as $type => $lvl3)
                                            <tr class="">
                                                <th class="bg-white border-0 px-2 py-1"><u>{{ $type }}</u></th>
                                            </tr>
                                            @foreach($lvl3 as $l3)
                                                <tr class="">
                                                    <td class="border-0 px-2 py-1">
                                                        <div>
                                                            {{--                                                                <button class="outline-none border-0 p-0">--}}
                                                            {{--                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                                                            {{--                                                                         viewBox="0 0 24 24"--}}
                                                            {{--                                                                         stroke-width="1.5" stroke="currentColor" class=""--}}
                                                            {{--                                                                         style="width: 15px;">--}}
                                                            {{--                                                                        <path stroke-linecap="round" stroke-linejoin="round"--}}
                                                            {{--                                                                              d="M12 4.5v15m7.5-7.5h-15"/>--}}
                                                            {{--                                                                    </svg>--}}
                                                            {{--                                                                </button>--}}
                                                            {!! str_repeat('&nbsp;', 10) !!} {{ $l3['name'] }}
                                                        </div>
                                                    </td>
                                                    <td class="border-0 px-2 py-1">

                                                    </td>
                                                    <td class="border-0 px-2 py-1">


                                                        @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                            @cannot('2.hide-assets')
                                                                @if($l3['balance']>=0)
                                                                    {{ number_format($l3['balance'],2) }}
                                                                @else
                                                                ({{ number_format(abs($l3['balance']),2) }})
                                                                @endif
                                                            @endcannot
                                                        @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                            @cannot('2.hide-liabilities')
                                                                @if($l3['balance']>=0)
                                                                    {{ number_format($l3['balance'],2) }}
                                                                @else
                                                                ({{ number_format(abs($l3['balance']),2) }})
                                                                @endif
                                                            @endcannot
                                                        @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                            @cannot('2.hide-equity')
                                                                @if($l3['balance']>=0)
                                                                    {{ number_format($l3['balance'],2) }}
                                                                @else
                                                                ({{ number_format(abs($l3['balance']),2) }})
                                                                @endif
                                                            @endcannot
                                                        @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                            @cannot('2.hide-income')
                                                                @if($l3['balance']>=0)
                                                                    {{ number_format($l3['balance'],2) }}
                                                                @else
                                                                ({{ number_format(abs($l3['balance']),2) }})
                                                                @endif
                                                            @endcannot
                                                        @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                            @cannot('2.hide-expenses')
                                                                @if($l3['balance']>=0)
                                                                    {{ number_format($l3['balance'],2) }}
                                                                @else
                                                                ({{ number_format(abs($l3['balance']),2) }})
                                                                @endif
                                                            @endcannot
                                                        @else
                                                            @if($l3['balance']>=0)
                                                                {{ number_format($l3['balance'],2) }}
                                                            @else
                                                            ({{ number_format(abs($l3['balance']),2) }})
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @foreach(collect($level4)->where('sub_account',$l3['id']) as $key => $l4)
                                                    @if ($l4['name']  == 'Drawings')

                                                        @continue
                                                    @endif
                                                    <tr class="">
                                                        <td class="border-0 px-2 py-1"
                                                            onclick="showHideRow('expandable-details{{str_replace(' ', '', $key)}}');">
                                                            <div>
                                                                    <span class="mx-3">
                                                                        {!! str_repeat('&nbsp;', 15) !!}<button
                                                                                class="outline-none border-0 p-0 bg-white"
                                                                                id="">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             fill="none"
                                                                             viewBox="0 0 24 24"
                                                                             stroke-width="1.5" stroke="currentColor"
                                                                             class=""
                                                                             style="width: 15px;">
                                                                            <path stroke-linecap="round"
                                                                                  stroke-linejoin="round"
                                                                                  d="M12 4.5v15m7.5-7.5h-15"/>
                                                                        </svg>
                                                                    </button>
                                                                      {{ $l4['name'] }}
                                                                    </span>
                                                            </div>
                                                        </td>
                                                        <td class="border-0 px-2 py-1">

                                                        </td>
                                                        <td class="border-0 px-2 py-1">


                                                            @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                @cannot('2.hide-assets')
                                                                    @if($l4['balance']>=0)
                                                                        {{ number_format($l4['balance'],2) }}
                                                                    @else
                                                                    ({{ number_format(abs($l4['balance']),2) }})
                                                                    @endif
                                                                @endcannot
                                                            @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                @cannot('2.hide-liabilities')
                                                                    @if($l4['balance']>=0)
                                                                        {{ number_format($l4['balance'],2) }}
                                                                    @else
                                                                    ({{ number_format(abs($l4['balance']),2) }})
                                                                    @endif
                                                                @endcannot
                                                            @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                @cannot('2.hide-equity')
                                                                    @if($l4['balance']>=0)
                                                                        {{ number_format($l4['balance'],2) }}
                                                                    @else
                                                                    ({{ number_format(abs($l4['balance']),2) }})
                                                                    @endif
                                                                @endcannot
                                                            @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                @cannot('2.hide-income')
                                                                    @if($l4['balance']>=0)
                                                                        {{ number_format($l4['balance'],2) }}
                                                                    @else
                                                                    ({{ number_format(abs($l4['balance']),2) }})
                                                                    @endif
                                                                @endcannot
                                                            @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                @cannot('2.hide-expenses')
                                                                    @if($l4['balance']>=0)
                                                                        {{ number_format($l4['balance'],2) }}
                                                                    @else
                                                                    ({{ number_format(abs($l4['balance']),2) }})
                                                                    @endif
                                                                @endcannot
                                                            @else
                                                                @if($l4['balance']>=0)
                                                                    {{ number_format($l4['balance'],2) }}
                                                                @else
                                                                ({{ number_format(abs($l4['balance']),2) }})
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @foreach(collect($level5)->where('sub_account',$l4['id']) as $l5)
                                                        <tr class="expandable-details@php echo str_replace(' ', '', $key); @endphp">
                                                            <td class="border-0 px-2 py-1">
                                                                <span class="mx-5">{!! str_repeat('&nbsp;', 20) !!}{{ $l5['name'] }}</span>

                                                            </td>
                                                            <td class="border-0 px-2 py-1">

                                                                @if($l5['type'] != 'Equity')
                                                                    <a href="{{ url('accounts/accountant/ledger') }}?account_id={{ $l5['id'] }}&from={{date('d M Y',strtotime("-2 months".$asat))}}&to={{date('d M Y',strtotime($asat))}}"
                                                                       target="_blank"
                                                                    >
                                                                        @endif






                                                                        @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                            @cannot('2.hide-assets')
                                                                                @if($l5['balance']>=0)
                                                                                    {{ number_format($l5['balance'],2) }}
                                                                                @else
                                                                                ({{ number_format(abs($l5['balance']),2) }})
                                                                                @endif
                                                                            @endcannot
                                                                        @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                            @cannot('2.hide-liabilities')
                                                                                @if($l5['balance']>=0)
                                                                                    {{ number_format($l5['balance'],2) }}
                                                                                @else
                                                                                ({{ number_format(abs($l5['balance']),2) }})
                                                                                @endif
                                                                            @endcannot
                                                                        @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                            @cannot('2.hide-equity')
                                                                                @if($l5['balance']>=0)
                                                                                    {{ number_format($l5['balance'],2) }}
                                                                                @else
                                                                                ({{ number_format(abs($l5['balance']),2) }})
                                                                                @endif
                                                                            @endcannot
                                                                        @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                            @cannot('2.hide-income')
                                                                                @if($l5['balance']>=0)
                                                                                    {{ number_format($l5['balance'],2) }}
                                                                                @else
                                                                                ({{ number_format(abs($l5['balance']),2) }})
                                                                                @endif
                                                                            @endcannot
                                                                        @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                                            @cannot('2.hide-expenses')
                                                                                @if($l5['balance']>=0)
                                                                                    {{ number_format($l5['balance'],2) }}
                                                                                @else
                                                                                ({{ number_format(abs($l5['balance']),2) }})
                                                                                @endif
                                                                            @endcannot
                                                                        @else
                                                                            @if($l5['balance']>=0)
                                                                                {{ number_format($l5['balance'],2) }}
                                                                            @else
                                                                            ({{ number_format(abs($l5['balance']),2) }})
                                                                            @endif
                                                                        @endif
                                                                        @if($l5['type'] != 'Equity')
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td class="border-0 px-2 py-1">

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                            <tr class="   bg-white">
                                                <th colspan="2" class=" text-left px-2 py-1 bg-white">
                                                    Total {{ $type }}
                                                </th>

                                                <th class=" text-left px-2 py-1 bg-white">
                                                    @php
                                                        $total = collect($level3)->where('type',$type)->sum('balance');
                                                    @endphp

                                                    @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                        @cannot('2.hide-assets')
                                                            @if($total>=0)
                                                                {{ number_format($total,2) }}
                                                            @else
                                                            ({{ number_format(abs($total),2) }})
                                                            @endif
                                                        @endcannot
                                                    @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                        @cannot('2.hide-liabilities')
                                                            @if($total>=0)
                                                                {{ number_format($total,2) }}
                                                            @else
                                                            ({{ number_format(abs($total),2) }})
                                                            @endif
                                                        @endcannot
                                                    @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                        @cannot('2.hide-equity')
                                                            @if($total>=0)
                                                                {{ number_format($total,2) }}
                                                            @else
                                                            ({{ number_format(abs($total),2) }})
                                                            @endif
                                                        @endcannot
                                                    @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                        @cannot('2.hide-income')
                                                            @if($total>=0)
                                                                {{ number_format($total,2) }}
                                                            @else
                                                            ({{ number_format(abs($total),2) }})
                                                            @endif
                                                        @endcannot
                                                    @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                        @cannot('2.hide-expenses')
                                                            @if($total>=0)
                                                                {{ number_format($total,2) }}
                                                            @else
                                                            ({{ number_format(abs($total),2) }})
                                                            @endif
                                                        @endcannot
                                                    @else
                                                        @if($total>=0)
                                                            {{ number_format($total,2) }}
                                                        @else
                                                        ({{ number_format(abs($total),2) }})
                                                        @endif
                                                    @endif


                                                </th>
                                            </tr>
                                        @endforeach

                                        <tr class="">
                                            <th colspan="2" class="text-left px-2 py-1 bg-white border-0">
                                                Total Liabilities & Equity
                                            </th>

                                            <th class=" text-left px-2 py-1 bg-white border-0">
                                                @php
                                                    $liabilities = collect($level3)->where('type','Liabilities')->sum('balance');
                                                    $equity = collect($level3)->where('type','Equity')->sum('balance');
                                                    $total = $liabilities + $equity;
                                                @endphp
                                                @if(auth()->user()->cannot('2.hide-liabilities')  || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                                    @if(auth()->user()->cannot('2.hide-equity')  || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)

                                                        @if($total>=0)
                                                            {{ number_format($total,2) }}
                                                        @else
                                                        ({{ number_format(abs($total),2) }})
                                                        @endif

                                                    @endif
                                                @endif
                                            </th>
                                        </tr>
                                        </tbody>
                                    </table>
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
            $(document).ready(function () {

                $('tr[class^="expandable-details"]').hide();
            });

            function showHideRow(row) {
                $("." + row).toggle();

            }

            // $("#buttontoshowdata").click(function(){
            //     $("#selecteddara").toggle();
            // });

            // $('.qwert').click(function() {
            //     // Select all of the elements generated by the foreach loop and hide them
            //     $('.abcdef').each(function() {
            //         $(this).toggle();
            //     });
            // });

            let from_date = new Pikaday({
                field: document.getElementById('from_date'),
                format: "DD MMM YYYY"
            });


            from_date.setDate(new Date('{{ $asat }}'));


        </script>
    @endpush
@else
    <div class="  max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-2">
                        <label for="from_date" class="block text-sm font-medium text-gray-700">As At</label>
                        <input type="text" wire:model.lazy="asat" readonly id="from_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>


                    <div class="col-span-6 sm:col-span-2">
                        <div class="mt-6 flex-shrink-0 flex" id="action-toolbar" wire:key="action-toolbar">
                            <button type="button" wire:click="search" wire:loading.attr="disabled"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span wire:loading wire:target="search">Searching ...</span>
                                <span wire:loading.remove wire:target="search">Search</span>
                            </button>
                            <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"
                                    class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Reset
                            </button>
                            <a href="{{'balance-sheet/export'}}?asat={{$asat}}" target="_blank"
                               class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">
                                Export.csv
                            </a>
                        </div>
                    </div>

                </div>

                <div>
                    <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Statement of Financial
                        Position</h3>
                    <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                    <p class="text-md  font-sm text-gray-500 text-center">
                        As At {{ date('d F Y',strtotime($asat)) }} </p>
                </div>
            </div>


            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="w-3/5 px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                    </th>
                    <th scope="col"
                        class="w-1/5 px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ env('CURRENCY','PKR') }}
                    </th>
                    <th scope="col"
                        class="w-1/5 px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ env('CURRENCY','PKR') }}
                    </th>

                </tr>
                </thead>
                <tbody x-data="{{ $data }}">
                @php
                    $count=0;
                @endphp
                @foreach(collect($level3)->groupBy('type')->toArray() as $type => $lvl3)
                    <tr class="bg-white">
                        <th colspan="3" class=" text-left  underline  px-2 py-1">

                            {{ $type }}
                        </th>
                    </tr>
                    @foreach($lvl3 as $l3)
                        <tr class="bg-white hover:bg-gray-100">
                            <td class="px-2 py-1 whitespace-nowrap text-sm  flex  font-medium text-gray-900">
                                <div @click="l3{{$l3['id']}} = ! l3{{$l3['id']}}" class="cursor-pointer">
                                    <template x-if="! l3{{$l3['id']}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                             fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </template>
                                    <template x-if=" l3{{$l3['id']}}">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                             fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </template>
                                </div>
                                <div>
                                    {{ $l3['name'] }}
                                </div>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                            </td>
                            <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-900 font-medium">
                                @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                    @cannot('2.hide-assets')
                                        @if($l3['balance']>=0)
                                            {{ number_format($l3['balance'],2) }}
                                        @else
                                        ({{ number_format(abs($l3['balance']),2) }})
                                        @endif
                                    @endcannot
                                @elseif($type == 'Liabilities'  && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                    @cannot('2.hide-liabilities')
                                        @if($l3['balance']>=0)
                                            {{ number_format($l3['balance'],2) }}
                                        @else
                                        ({{ number_format(abs($l3['balance']),2) }})
                                        @endif
                                    @endcannot
                                @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                    @cannot('2.hide-equity')
                                        @if($l3['balance']>=0)
                                            {{ number_format($l3['balance'],2) }}
                                        @else
                                        ({{ number_format(abs($l3['balance']),2) }})
                                        @endif
                                    @endcannot
                                @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                    @cannot('2.hide-income')
                                        @if($l3['balance']>=0)
                                            {{ number_format($l3['balance'],2) }}
                                        @else
                                        ({{ number_format(abs($l3['balance']),2) }})
                                        @endif
                                    @endcannot
                                @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                    @cannot('2.hide-expenses')
                                        @if($l3['balance']>=0)
                                            {{ number_format($l3['balance'],2) }}
                                        @else
                                        ({{ number_format(abs($l3['balance']),2) }})
                                        @endif
                                    @endcannot
                                @else
                                    @if($l3['balance']>=0)
                                        {{ number_format($l3['balance'],2) }}
                                    @else
                                    ({{ number_format(abs($l3['balance']),2) }})
                                    @endif
                                @endif

                            </td>
                        </tr>
                        @foreach(collect($level4)->where('sub_account',$l3['id']) as $l4)
                            @if ($l4['name']  == 'Drawings')
                                @continue
                            @endif
                            <tr class="bg-white hover:bg-gray-100" x-show="l3{{$l3['id']}}" wire:key="l3{{$l3['id']}}"
                            >
                                <td class="pl-20 px-2 py-1 whitespace-nowrap text-sm font-medium  text-gray-900 flex">
                                    <div @click="l4{{$l4['id']}} = ! l4{{$l4['id']}}" class="cursor-pointer">
                                        <template x-if="! l4{{$l4['id']}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                 fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </template>
                                        <template x-if="l4{{$l4['id']}}">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                 fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </template>
                                    </div>
                                    <div>
                                        {{ $l4['name'] }}
                                    </div>
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-900 font-medium ">
                                    @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                        @cannot('2.hide-assets')
                                            @if($l4['balance']>=0)
                                                {{ number_format($l4['balance'],2) }}
                                            @else
                                            ({{ number_format(abs($l4['balance']),2) }})
                                            @endif
                                        @endcannot
                                    @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                        @cannot('2.hide-liabilities')
                                            @if($l4['balance']>=0)
                                                {{ number_format($l4['balance'],2) }}
                                            @else
                                            ({{ number_format(abs($l4['balance']),2) }})
                                            @endif
                                        @endcannot
                                    @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                        @cannot('2.hide-equity')
                                            @if($l4['balance']>=0)
                                                {{ number_format($l4['balance'],2) }}
                                            @else
                                            ({{ number_format(abs($l4['balance']),2) }})
                                            @endif
                                        @endcannot
                                    @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                        @cannot('2.hide-income')
                                            @if($l4['balance']>=0)
                                                {{ number_format($l4['balance'],2) }}
                                            @else
                                            ({{ number_format(abs($l4['balance']),2) }})
                                            @endif
                                        @endcannot
                                    @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                        @cannot('2.hide-expenses')
                                            @if($l4['balance']>=0)
                                                {{ number_format($l4['balance'],2) }}
                                            @else
                                            ({{ number_format(abs($l4['balance']),2) }})
                                            @endif
                                        @endcannot
                                    @else
                                        @if($l4['balance']>=0)
                                            {{ number_format($l4['balance'],2) }}
                                        @else
                                        ({{ number_format(abs($l4['balance']),2) }})
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @foreach(collect($level5)->where('sub_account',$l4['id']) as $l5)
                                <tr class="bg-white hover:bg-gray-100" x-show="l4{{$l4['id']}} && l3{{$l3['id']}}" wire:key="l4{{$l4['id']}}">
                                    <td class="pl-32 px-2 py-1 whitespace-nowrap text-sm   text-gray-500">
                                        {{ $l5['name'] }}
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                                        @if($l5['type'] != 'Equity')
                                            <a href="{{ url('accounts/accountant/ledger') }}?account_id={{ $l5['id'] }}&from={{date('d M Y',strtotime("-2 months".$asat))}}&to={{date('d M Y',strtotime($asat))}}"
                                               target="_blank"
                                            >
                                                @endif





                                                @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                    @cannot('2.hide-assets')
                                                        @if($l5['balance']>=0)
                                                            {{ number_format($l5['balance'],2) }}
                                                        @else
                                                        ({{ number_format(abs($l5['balance']),2) }})
                                                        @endif
                                                    @endcannot
                                                @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                    @cannot('2.hide-liabilities')
                                                        @if($l5['balance']>=0)
                                                            {{ number_format($l5['balance'],2) }}
                                                        @else
                                                        ({{ number_format(abs($l5['balance']),2) }})
                                                        @endif
                                                    @endcannot
                                                @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                    @cannot('2.hide-equity')
                                                        @if($l5['balance']>=0)
                                                            {{ number_format($l5['balance'],2) }}
                                                        @else
                                                        ({{ number_format(abs($l5['balance']),2) }})
                                                        @endif
                                                    @endcannot
                                                @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                    @cannot('2.hide-income')
                                                        @if($l5['balance']>=0)
                                                            {{ number_format($l5['balance'],2) }}
                                                        @else
                                                        ({{ number_format(abs($l5['balance']),2) }})
                                                        @endif
                                                    @endcannot
                                                @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                                    @cannot('2.hide-expenses')
                                                        @if($l5['balance']>=0)
                                                            {{ number_format($l5['balance'],2) }}
                                                        @else
                                                        ({{ number_format(abs($l5['balance']),2) }})
                                                        @endif
                                                    @endcannot
                                                @else
                                                    @if($l5['balance']>=0)
                                                        {{ number_format($l5['balance'],2) }}
                                                    @else
                                                    ({{ number_format(abs($l5['balance']),2) }})
                                                    @endif
                                                @endif

                                                @if($l5['type'] != 'Equity')
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    <tr class="   bg-white">
                        <th colspan="2" class=" text-left align-top  pb-12  px-2 py-1">
                            Total {{ $type }}
                        </th>

                        <th class=" text-left   align-top   px-2 py-1">
                            @php
                                $total = collect($level3)->where('type',$type)->sum('balance');
                            @endphp

                            @if($type == 'Assets' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                @cannot('2.hide-assets')
                                    @if($total>=0)
                                        {{ number_format($total,2) }}
                                    @else
                                    ({{ number_format(abs($total),2) }})
                                    @endif
                                @endcannot
                            @elseif($type == 'Liabilities' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                @cannot('2.hide-liabilities')
                                    @if($total>=0)
                                        {{ number_format($total,2) }}
                                    @else
                                    ({{ number_format(abs($total),2) }})
                                    @endif
                                @endcannot
                            @elseif($type == 'Equity' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                @cannot('2.hide-equity')
                                    @if($total>=0)
                                        {{ number_format($total,2) }}
                                    @else
                                    ({{ number_format(abs($total),2) }})
                                    @endif
                                @endcannot
                            @elseif($type == 'Income' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                @cannot('2.hide-income')
                                    @if($total>=0)
                                        {{ number_format($total,2) }}
                                    @else
                                    ({{ number_format(abs($total),2) }})
                                    @endif
                                @endcannot
                            @elseif($type == 'Expenses' && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true)
                                @cannot('2.hide-expenses')
                                    @if($total>=0)
                                        {{ number_format($total,2) }}
                                    @else
                                    ({{ number_format(abs($total),2) }})
                                    @endif
                                @endcannot
                            @else
                                @if($total>=0)
                                    {{ number_format($total,2) }}
                                @else
                                ({{ number_format(abs($total),2) }})
                                @endif
                            @endif


                        </th>
                    </tr>
                @endforeach


                <tr class=" bg-white ">
                    <th colspan="2" class=" text-left align-top  pb-12  px-2 py-1">
                        Total Liabilities & Equity
                    </th>

                    <th class=" text-left   align-top   px-2 py-1">
                        @php
                            $liabilities = collect($level3)->where('type','Liabilities')->sum('balance');
                            $equity = collect($level3)->where('type','Equity')->sum('balance');
                            $total = $liabilities + $equity;
                        @endphp
                        @if(auth()->user()->cannot('2.hide-liabilities')  || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                            @if(auth()->user()->cannot('2.hide-equity')  || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true)
                                @if($total>=0)
                                    {{ number_format($total,2) }}
                                @else
                                ({{ number_format(abs($total),2) }})
                                @endif
                            @endif
                        @endif

                    </th>
                </tr>
                </tbody>
            </table>


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


        from_date.setDate(new Date('{{ $asat }}'));


    </script>
@endsection
