<div>
    <table>
        <tr>
            <th colspan="6">{{ $account_details['name'] }}</th>
        </tr>
        <tr>
            <th colspan="6">{{env('APP_NAME')}}</th>
        </tr>
        <tr>
         <th colspan="6">
                  Statement Period from {{date('d M Y', strtotime($from))}} to {{date('d M Y', strtotime($to))}}
         </th>
        </tr>


    <tr>

        <th scope="col"
            class="w-20 px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
            V. #
        </th>
        <th scope="col"
            class="w-28 px-2   border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
            Date
        </th>
        <th scope="col"
            class="px-2 py-2   border-r text-left text-sm font-bold text-gray-500  tracking-wider">
            Description
        </th>
        <th scope="col"
            class="w-28 px-2 py-2   border-r text-right text-sm font-bold text-gray-500  tracking-wider">
            Dr
        </th>
        <th scope="col"
            class="w-28 px-2 py-2   border-r text-right text-sm font-bold text-gray-500  tracking-wider">
            Cr
        </th>

        <th scope="col"
            class="w-28 px-2 py-2   border-r text-right text-sm font-bold text-gray-500  tracking-wider">
            Balance
        </th>

    </tr>

    <tbody>

    <tr class>
        <th colspan="3" class="px-2 py-2  text-right   text-sm   text-gray-500">
            Opening Balance
        </th>
        <th></th>
        <th></th>
        <th class="px-2   py-2    text-sm  text-right  text-gray-500">{{ ($opening_balance) }}</th>
    </tr>
    @php
        $balance = $opening_balance;
    @endphp
    @foreach($ledger as $key => $en)
        @php
            if ($account_details['nature'] == 'd') {
                if($account_details['is_contra']=='f'){
                    $balance = $balance+ $en['debit'] - $en['credit'];
                } else {
                    $balance = $balance- $en['debit'] + $en['credit'];
                }

            } else {
                if($account_details['is_contra']=='f') {
                    $balance = $balance- $en['debit'] + $en['credit'];
                } else {
                    $balance = $balance+ $en['debit'] - $en['credit'];
                }
            }
        @endphp
        <tr class="{{ $loop->odd ? 'bg-gray-50' :'' }}">
            <td class="px-2   py-2   border-r text-sm   text-gray-500">
                {{ $en['voucher_no'] }}
            </td>
            <td class="px-2   py-2    border-r text-sm text-gray-500">
                {{ date('d M, Y',strtotime($en['posting_date'])) }}
            </td>
            <td class="px-2    py-2    border-r  text-sm text-gray-500">
                @if(!empty($en['reference']))  {{ ucwords($en['reference']) }} @endif   {{ $en['description'] }}
            </td>
            <td class="px-2  py-2  text-right  border-r text-sm text-gray-500">
                {{ ($en['debit']) }}
            </td>
            <td class="px-2   py-2 text-right border-r text-sm text-gray-500">
                {{ ($en['credit']) }}
            </td>
            <td
                    class="  w-10 px-2 py-2   text-right border-r text-sm text-gray-500">
                {{  ($balance) }}

            </td>

        </tr>
    @endforeach
    <tr  >
        <th colspan="3" class="px-2  py-2   text-right   text-sm   text-gray-500">
            Closing Balance
        </th>
        <th></th>
        <th></th>
        <th class="px-2    py-2   text-sm  text-right  text-gray-500">{{  ($balance) }}</th>

    </tr>
    <tr  >
        <th colspan="3" class="px-2   py-2  text-right   text-sm   text-gray-500">
            Total Debit and Credit
        </th>
        <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500">{{ collect($ledger)->sum('debit') }}</th>
        <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500">{{ collect($ledger)->sum('credit') }}</th>
        <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500"></th>

    </tr>

    <tr  >
        <th colspan="3" class="px-2   py-2  text-right   text-sm   text-gray-500">
            Total Number of Transactions
        </th>
        <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500">{{ collect($ledger)->count() }}</th>
        <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500"></th>
        <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500"></th>

    </tr>

    </tbody>

</table>
</div>