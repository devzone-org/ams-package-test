@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-primary card-outline">
                                <div class="card-body py-0 pt-2">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="users" class="">User</label>
                                                <select wire:model.defer="user_account_id"
                                                        class="form-control">
                                                    <option value=""></option>
                                                    @foreach($users as $u)
                                                        <option value="{{ $u['account_id'] }}">{{ $u['account_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="from_date" class="">From Date</label>
                                                <input type="text" wire:model.lazy="from_date" id="from_date" autocomplete="off"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">

                                                <label for="to_date" class="">To Date</label>
                                                <input type="text" wire:model.lazy="to_date" id="to_date" autocomplete="off"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 pt-3">
                                            <div class="form-group">
                                                <button type="button" wire:click="search" wire:loading.attr="disabled"
                                                        class="btn btn-primary">
                                                    <span wire:loading wire:target="search">Searching ...</span>
                                                    <span wire:loading.remove wire:target="search">Search</span>
                                                </button>
                                                <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"
                                                        class="btn btn-danger">
                                                    Reset
                                                </button>
                                                @if(!empty($report))
                                                    <a href="{{'day-closing/export'}}?id={{$user_account_id}}&from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}"
                                                       target="_blank"
                                                       class="btn btn-success">
                                                        Export.csv
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header pt-0 m-0">
                                    <h4 class="d-flex justify-content-center p-0 m-0">Day Closing Report</h4>
                                    <p class="text-center p-0 m-0">{{ env('APP_NAME') }}</p>
                                    <p class="text-center">Statement
                                        Period {{ date('d M, Y',strtotime($from_date)) }}
                                        to {{ date('d M, Y',strtotime($to_date)) }} </p>
                                </div>

                                <div class="card-body overflow-auto p-0 m-0">
                                    <table class="table table-bordered border-0">
                                        <thead class="text-nowrap">
                                        <tr>
                                            <th>Closing Date</th>
                                            <th>Voucher</th>
                                            <th>User ID</th>
                                            <th>Closed By</th>
                                            <th>Close At</th>
                                            <th>System Cash</th>
                                            <th>Physical Cash</th>
                                            <th>Amount Retained</th>
                                            <th>Adjustment</th>
                                            <th>Amount Transferred</th>
                                            <th>Transfer To</th>
                                            <th>Attachment</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-nowrap">
                                        @forelse($report as $r)
                                            <tr>
                                                <td class="text-center">{{ date('d M Y',strtotime($r['created_at'])) }}</td>
                                                <td class="text-center">
                                                    <a class="text-dark" href="javascript:void(0);"
                                                       onclick="window.open('{{ url('accounts/journal/voucher/print').'/'.$r['voucher_no'] }}','voucher-print-{{$r['voucher_no']}}','height=500,width=800');">{{ $r['voucher_no'] }}</a>
                                                </td>
                                                <td class="text-center">
                                                    {{ $r['user_id'] }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $r['close_by'] }}
                                                </td>
                                                <td class="text-center">
                                                    {{ date('h:i A',strtotime($r['created_at'])) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ number_format($r['closing_balance'],2) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ number_format($r['physical_cash']) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($r['cash_retained']) }}
                                                </td>

                                                <td class="text-center">
                                                    @if($r['closing_balance'] - $r['physical_cash']<=0)
                                                        {{ number_format(abs($r['closing_balance'] - $r['physical_cash']),2) }}
                                                    @else
                                                    ({{number_format(abs($r['closing_balance'] - $r['physical_cash']),2) }})
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    {{ number_format($r['physical_cash'] - $r['cash_retained']) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $r['transfer_name'] }}
                                                </td>
                                                <td class="text-center">
                                                    @if(!empty($r['attachment']))
                                                        <a href="{{ env('AWS_URL').$r['attachment'] }}"
                                                           class="text-danger" target="_blank">
                                                            View Attachment
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <td colspan="12" class="text-danger rounded-md overflow-hidden">
                                                <div class="alert alert-danger mb-0">
                                                    No Records Found.
                                                </div>

                                            </td>
                                        @endforelse
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
                                <a href="{{'day-closing/export'}}?id={{$user_account_id}}&from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}"
                                   target="_blank"
                                   class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">
                                    Export.csv
                                </a>
                            @endif
                        </div>

                    </div>

                </div>
                <div>
                    <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Day Closing Report</h3>
                    <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                    <p class="text-md  font-sm text-gray-500 text-center">Statement
                        Period {{ date('d M, Y',strtotime($from_date)) }}
                        to {{ date('d M, Y',strtotime($to_date)) }} </p>
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
                        <th scope="col"
                            class=" px-2 py-2   border-r text-center text-sm font-bold text-gray-500  tracking-wider">
                            Attachment
                        </th>


                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                    @forelse($report as $r)
                        <tr class="">
                            <td class="px-2  text-center py-2   border-r text-sm   text-gray-500">
                                {{ date('d M Y',strtotime($r['created_at'])) }}
                            </td>
                            <td class="px-2 text-center py-2   border-r text-sm   text-gray-500">
                                <a class="font-medium text-indigo-600 hover:text-indigo-500" href="javascript:void(0);"
                                   onclick="window.open('{{ url('accounts/journal/voucher/print').'/'.$r['voucher_no'] }}','voucher-print-{{$r['voucher_no']}}','height=500,width=800');">{{ $r['voucher_no'] }}</a>
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
                            <td class="px-2   py-2 text-center border-r text-sm text-gray-500">
                                @if(!empty($r['attachment']))
                                    <a href="{{ env('AWS_URL').$r['attachment'] }}"
                                       class="text-yellow-500 font-medium" target="_blank">
                                        View Attachment
                                    </a>
                                @endif
                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-sm text-red-500 rounded-md overflow-hidden">
                                <div class="flex items-center justify-center py-5">
                                    <div class="flex justify-between">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span class="ml-2">No Records Yet!</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
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


