<table class="min-w-full table-fixed divide-y divide-gray-200">
    <thead class="bg-gray-100">
    <tr>
        <th colspan="11">Day Closing Report</th>
    </tr>
    <tr>
        <th colspan="11">{{env('APP_NAME')}}</th>
    </tr>
    <tr>
        <th colspan="11">
            Statement Period from {{date('d M Y', strtotime($from))}} to {{date('d M Y', strtotime($to))}}
        </th>
    </tr>

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
                <a class="font-medium text-indigo-600 hover:text-indigo-500" >{{ $r['voucher_no'] }}</a>
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
                {{ $r['closing_balance'] }}
            </td>

            <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                {{$r['physical_cash'] }}
            </td>
            <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                {{ $r['cash_retained'] }}
            </td>

            <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                @if($r['closing_balance'] - $r['physical_cash']<=0)
                    {{ abs($r['closing_balance'] - $r['physical_cash']) }}
                @else
                {{abs($r['closing_balance'] - $r['physical_cash'])}}
                @endif
            </td>

            <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                {{$r['physical_cash'] - $r['cash_retained'] }}
            </td>

            <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                {{ $r['transfer_name'] }}
            </td>


        </tr>
    @endforeach
    </tbody>
</table>