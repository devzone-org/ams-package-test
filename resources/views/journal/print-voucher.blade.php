<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Voucher</title>
    <link href="{{ asset('ams/css/app.css') }}" rel="stylesheet">
</head>
<body>

<div class="bg-white py-6 px-4 space-y-6 sm:p-6">
    <div>
        <h3 class="text-lg leading-6 text-center font-medium text-gray-900">{{ env('APP_NAME') }}</h3>
        <p class="mt-1 text-sm text-center text-gray-500">Voucher Print Date Time: {{ date('d M Y h:i:s A') }}</p>
    </div>
    <div class="flex justify-between">
        <div>
            <p>Posting Date: {{ date('d M, Y',strtotime($ledger->first()->posting_date)) }}</p>
            <p>Voucher # {{ $ledger->first()->voucher_no }}</p>
        </div>
        <div>
            <p class="text-right">Posted By: {{  $ledger->first()->posted }}</p>
            <p class="text-right">Approved By: {{  $ledger->first()->approved }}</p>
        </div>
    </div>

    <table class="min-w-full table-fixed  ">
        <thead class="">
        <tr class="">

            <th scope="col"
                class=" px-2 py-2  bg-gray-100 border-l border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                Account Name
            </th>
            <th scope="col"
                class="px-2 py-2 w-6/12  border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                Description
            </th>
            <th scope="col"
                class="w-28 px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                Debit
            </th>
            <th scope="col"
                class="w-28 px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                Credit
            </th>

        </tr>
        </thead>
        <tbody class="bg-white  ">

        @foreach($ledger as $t)
            <tr class="{{ $loop->first ? 'border-t': '' }} {{ $loop->even ? 'bg-gray-50':'' }} {{ $loop->last ? 'border-b':'' }}">
                <td class="px-2 py-2 border-r border-l text-sm text-gray-500">
                    {{ $t->code }} - {{ $t->name }}
                </td>

                <td class="px-2 py-2 border-r text-sm text-gray-500">
                    {{ $t->description }}
                </td>
                <td class="px-2 py-2 text-right  border-r text-sm text-gray-500">
                    @if($t->debit>0)
                        {{ number_format($t->debit,2) }}
                    @endif
                </td>
                <td class="px-2 text-right py-2 border-r text-sm text-gray-500">
                    @if($t->credit>0)
                        {{ number_format($t->credit,2) }}
                    @endif
                </td>
            </tr>
        @endforeach

        <tr class="border-b">

            <th colspan="2" class="px-2 border-l py-2 text-right border-r text-sm text-gray-500">
                Total
            </th>
            <td class="px-2 py-2 text-right  border-r text-sm text-gray-500">

                {{ number_format($ledger->sum('debit'),2) }}

            </td>
            <td class="px-2 text-right py-2 border-r text-sm text-gray-500">
                {{ number_format($ledger->sum('credit'),2) }}
            </td>
        </tr>

        </tbody>
    </table>


</div>
@if($print)
    <script type="text/javascript">
        window.onload = function () {
            window.print();
        }
    </script>
@endif

</body>
</html>
