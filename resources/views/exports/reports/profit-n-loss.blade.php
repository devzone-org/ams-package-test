<table class="min-w-full table-fixed divide-y divide-gray-200">
    <thead class="bg-gray-100">
    <tr>
        <th colspan="5">Profit and Loss Report</th>
    </tr>
    <tr>
        <th colspan="5">{{env('APP_NAME')}}</th>
    </tr>
    <tr>
        <th colspan="5">
            Statement Period from {{date('d M Y', strtotime($from))}} to {{date('d M Y', strtotime($to))}}
        </th>
    </tr>
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
                            {{$first['balance'] }}

                    @else
                        -
                    @endif
                </td>
            @endforeach
            <td class="px-2   py-2   text-center border-r text-sm text-gray-500">

                    {{ collect($report)->where('account_id',$key)->sum('balance')}}

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
                {{ collect($report)->where('type','Income')->where('month',$h)->sum('balance')}}
            </th>

        @endforeach

        <th scope="col"
            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
            {{ collect($report)->where('type','Income')->sum('balance') }}
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
                            {{ $first['balance'] }}
                    @else
                        -
                    @endif
                </td>
            @endforeach
            <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                    {{ collect($report)->where('account_id',$key)->sum('balance') }}
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
                {{ collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance') }}
            </th>

        @endforeach

        <th scope="col"
            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
            {{ collect($report)->where('p_ref','cost-of-sales-4')->sum('balance') }}
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
                {{ collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->where('month',$h)->sum('balance')}}
            </th>

        @endforeach

        <th scope="col"
            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
            {{ collect($report)->where('type','Income')->sum('balance') - collect($report)->where('p_ref','cost-of-sales-4')->sum('balance') }}
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
                            {{ $first['balance'] }}
                    @else
                        -
                    @endif
                </td>
            @endforeach
            <td class="px-2   py-2   text-center border-r text-sm text-gray-500">
                    {{ collect($report)->where('account_id',$key)->sum('balance') }}
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
                {{ collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->where('month',$h)->sum('balance')}}
            </th>

        @endforeach

        <th scope="col"
            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
            {{ collect($report)->where('type','Expenses')->where('p_ref','!=','cost-of-sales-4')->sum('balance')}}
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
                {{ collect($report)->where('type','Income')->where('month',$h)->sum('balance') - collect($report)->where('type','Expenses')->where('month',$h)->sum('balance')}}
            </th>

        @endforeach

        <th scope="col"
            class="  px-2   border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
            {{ collect($report)->where('type','Income')->sum('balance') - collect($report)->where('type','Expenses')->sum('balance') }}
        </th>

    </tr>
    </tbody>
</table>