<table class="min-w-full table-fixed divide-y divide-gray-200">
    <thead class="bg-gray-100">
    <tr>
        <th colspan="4">Trial Balance Report</th>
    </tr>
    <tr>
        <th colspan="4">{{env('APP_NAME')}}</th>
    </tr>
    <tr>
        <th colspan="4">
            Statement Period from {{date('d M Y', strtotime($from))}} to {{date('d M Y', strtotime($to))}}
        </th>
    </tr>
    <tr>
        <th scope="col"
            class="  px-2    border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
            Type
        </th>
        <th scope="col"
            class="  px-2   border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
            Account Name
        </th>

        <th scope="col"
            class="w-28 px-2 py-2   border-r text-right text-sm font-bold text-gray-500  tracking-wider">
            Dr
        </th>
        <th scope="col"
            class="w-28 px-2 py-2   border-r text-right text-sm font-bold text-gray-500  tracking-wider">
            Cr
        </th>


    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">

    @foreach($ledger as $key => $en)

        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
            <td class="px-2   py-2   border-r text-sm   text-gray-500">
                {{ $en['type'] }}
            </td>
            <td class="px-2   py-2    border-r text-sm text-gray-500">
                {{ $en['code'] }} - {{ $en['account_name'] }}
            </td>

            <td class="px-2  py-2  text-right  border-r text-sm text-gray-500">

                {{ $en['debit']>=0 ? $en['debit'] : abs($en['debit']) }}
            </td>
            <td class="px-2   py-2 text-right border-r text-sm text-gray-500">

                {{ $en['credit']>=0 ? $en['credit'] : abs($en['credit']) }}
            </td>
        </tr>
    @endforeach
    @php
        $debit = collect($ledger)->sum('debit');
        $credit = collect($ledger)->sum('credit');

    @endphp
    <tr>
        <th class="px-2   py-2 text-right   border-r text-sm text-gray-500" colspan="2">
            Total
        </th>
        <th class="px-2  py-2  text-right  border-r text-sm text-gray-500">
            {{ $debit>0 ? $debit : abs($debit)}}
        </th>
        <th class="px-2   py-2 text-right border-r text-sm text-gray-500">
            {{ $credit>0 ? $credit : abs($credit) }}
        </th>
    </tr>

    <tr>
        <th class="px-2   py-2 text-right   border-r text-sm text-gray-500" colspan="2">
            Difference
        </th>

        <th colspan="2" class="px-2   py-2 text-right border-r text-sm text-gray-500">
            {{abs($debit-$credit)}}
        </th>
    </tr>

    </tbody>
</table>