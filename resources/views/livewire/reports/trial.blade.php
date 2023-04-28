@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h4 class="card-title"><b>Trial Balance</b></h4>
                                </div>
                                <div class="card-body m-0 p-0 p-2 px-3">
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
                                        <div class="col-xs-6 col-sm-4 mt-4" style="padding-top: 8px">
                                            <div class="form-group">
                                                @if(!empty($ledger))
                                                    <a href="{{'trial-balance/export'}}?from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}"
                                                       target="_blank"
                                                       class="btn btn-primary">
                                                        Export.xls
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body p-0">
                                                <table class="table table-bordered border-0">
                                                    <thead class="">
                                                    <tr>
                                                        <th class="add-services-table col-2 text-muted">Type</th>
                                                        <th class="add-services-table col-8 text-muted">Account Name</th>
                                                        <th class="add-services-table col-1 text-right text-muted">Dr</th>
                                                        <th class="add-services-table col-1 text-right text-muted">Cr</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($ledger as $key => $en)
                                                        <tr>
                                                            <td class="add-services-table">{{ $en['type'] }}</td>
                                                            <td class="add-services-table">
                                                                <a target="_blank" class="text-dark"
                                                                   href="{{url('accounts/accountant/ledger') }}?account_id={{$en['id']}}">
                                                                    {{ $en['code'] }} - {{ $en['account_name'] }}</a>
                                                            </td>
                                                            <td class=" add-services-table text-right">
                                                                {{ $en['debit']>=0 ? number_format($en['debit'],2) : '('.number_format(abs($en['debit']),2).')' }}
                                                            </td>
                                                            <td class="add-services-table text-right">
                                                                {{ $en['credit']>=0 ? number_format($en['credit'],2) : '('.number_format(abs($en['credit']),2).')' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @php
                                                        $debit = collect($ledger)->sum('debit');
                                                        $credit = collect($ledger)->sum('credit');

                                                    @endphp
                                                    <tr>
                                                        <th class="px-2 py-2 text-right bg-white add-services-table" colspan="2">
                                                            <span class="text-muted">Total</span>
                                                        </th>
                                                        <th class="px-2  py-2  text-right bg-white add-services-table">
                                                            <span class="text-muted">{{ $debit>0 ? number_format($debit,2) : '('.number_format(abs($debit),2).')' }}</span>
                                                        </th>
                                                        <th class="px-2   py-2 text-right bg-white add-services-table">
                                                            <span class="text-muted">{{ $credit>0 ? number_format($credit,2) : '('.number_format(abs($credit),2).')' }}</span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <th class="px-2   py-2 text-right bg-white add-services-table" colspan="2">
                                                            <span class="text-muted">Difference</span>
                                                        </th>

                                                        <th colspan="2" class="px-2   py-2 text-right bg-white add-services-table">
                                                            <span class="text-muted">{{ number_format(abs($debit-$credit),2) }}</span>
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
    <div>
        <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Trial Balance</h3>

                </div>

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-2">
                        <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="text" readonly wire:model.lazy="from_date" id="from_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="text" readonly wire:model.lazy="to_date" id="to_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-2 mt-6">
                        @if(!empty($ledger))
                            <a href="{{'trial-balance/export'}}?from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}"
                               target="_blank"
                               class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">
                                Export.xls
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div>

                <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gray-100">
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
                        <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                            <td class="px-2   py-2   border-r text-sm   text-gray-500">
                                {{ $en['type'] }}
                            </td>

                            <td class="px-2   py-2    border-r text-sm text-gray-500">
                                <a target="_blank" class="hover:text-gray-900"
                                   href="{{url('accounts/accountant/ledger') }}?account_id={{$en['id']}}">
                                    {{ $en['code'] }} - {{ $en['account_name'] }}</a>
                            </td>

                            <td class="px-2  py-2  text-right  border-r text-sm text-gray-500">

                                {{ $en['debit']>=0 ? number_format($en['debit'],2) : '('.number_format(abs($en['debit']),2).')' }}
                            </td>
                            <td class="px-2   py-2 text-right border-r text-sm text-gray-500">

                                {{ $en['credit']>=0 ? number_format($en['credit'],2) : '('.number_format(abs($en['credit']),2).')' }}
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
                            {{ $debit>0 ? number_format($debit,2) : '('.number_format(abs($debit),2).')' }}
                        </th>
                        <th class="px-2   py-2 text-right border-r text-sm text-gray-500">
                            {{ $credit>0 ? number_format($credit,2) : '('.number_format(abs($credit),2).')' }}
                        </th>
                    </tr>

                    <tr>
                        <th class="px-2   py-2 text-right   border-r text-sm text-gray-500" colspan="2">
                            Difference
                        </th>

                        <th colspan="2" class="px-2   py-2 text-right border-r text-sm text-gray-500">
                            {{ number_format(abs($debit-$credit),2) }}
                        </th>
                    </tr>

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
