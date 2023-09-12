@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h1>Payments & Receiving</h1>
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

                                            <label for="nature" class="font-weight-normal">Nature</label>
                                            <select wire:model="nature"
                                                    class="form-control">
                                                <option value="">All</option>
                                                <option value="pay">Paid</option>
                                                <option value="receive">Received</option>
                                                {{--                                                <option value="transfer_entry">Transfer Entry</option>--}}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">
                                            <label for="status" class="font-weight-normal">Status</label>
                                            <select wire:model="status"
                                                    class="form-control">
                                                <option value="">All</option>
                                                <option value="t">Approve</option>
                                                <option value="f">Not Approved</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">

                                            <label for="from" class="font-weight-normal">From</label>
                                            <input type="text" wire:model.lazy="from" id="from" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">

                                            <label for="to" class="font-weight-normal">To</label>
                                            <input type="text" wire:model.lazy="to" id="to" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4 pt-4">
                                        <div class="form-group">
                                            {{--                                            <button type="button" class="btn btn-primary"--}}
                                            {{--                                                    wire:click="search"--}}
                                            {{--                                                    wire:loading.attr="disabled">--}}
                                            {{--                                                <span wire:loading wire:target="search">Searching ...</span>--}}
                                            {{--                                                <span wire:loading.remove wire:target="search">Search</span>--}}
                                            {{--                                            </button>--}}
                                            <button class="btn btn-danger" type="button" wire:click="resetSearch"
                                                    wire:loading.attr="disabled">Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="card-title pt-1"><b>Payments & Receiving</b></p>
                                    <a href="{{  url('accounts/accountant/payments/add') }}"
                                       class="btn btn-primary btn-sm">
                                        Create
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0 m-0">
                                @if ($errors->any())
                                    <div class="col-12">
                                        @foreach ($errors->all() as $error)

                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">
                                                    ×
                                                </button>
                                                <li>{{ $error }}</li>
                                            </div>
                                        @endforeach
                                    </div>

                                @endif
                                @if(!empty($success))
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">
                                                ×
                                            </button>
                                            {{ $success }}
                                        </div>
                                    </div>
                                @endif
                                <div class="table table bordered table-responsive">
                                    <table class="table border-0">
                                        <thead class="">
                                        <th class="add-services-table text-center pl-3 pr-1 text-muted border-right-0">#</th>
                                        <th class="add-services-table text-center text-muted border-left-0 border-right-0">
                                            NATURE
                                        </th>
                                        <th class="add-services-table text-nowrap text-center text-muted border-left-0 border-right-0">
                                            TRANSACTION DATE
                                        </th>
                                        <th class="add-services-table text-center text-muted border-left-0 border-right-0">
                                            ACCOUNTS
                                        </th>
                                        <th class="add-services-table text-center text-muted border-left-0 border-right-0">
                                            DESCRIPTION
                                        </th>
                                        <th class="add-services-table text-center text-muted border-left-0 border-right-0">
                                            AMOUNT
                                        </th>
                                        <th class="add-services-table text-center text-nowrap text-muted border-left-0 border-right-0">
                                            CREATED BY
                                        </th>
                                        <th class="add-services-table text-center text-nowrap text-muted border-left-0 border-right-0">
                                            APPROVED BY
                                        </th>
                                        <th class="text-center add-services-table text-muted border-left-0"
                                            style="width: 20px;"></th>
                                        </thead>
                                        <tbody>
                                        @foreach($entries as $key => $e)
                                            <tr>
                                                <td class="align-middle pl-3 pr-1 border-right-0">{{$entries->firstItem() + $key}}</td>
                                                <td class="align-middle text-center border-right-0 border-left-0">
                                                    @if($e->nature=='pay')
                                                        <span class="badge badge-pill badge-success">Payment</span>
                                                    @elseif($e->nature=='receive')
                                                        <span class="badge badge-pill badge-primary">Received</span>
                                                    @else
                                                        <span class="badge badge-pill badge-warning">Transfer Entry</span>
                                                    @endif
                                                    @if($e->reversal == 't')
                                                        <br>
                                                        <span class="badge badge-pill badge-danger">Reversed</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center border-right-0 border-left-0">
                                                    {{ date('d M, Y',strtotime($e->posting_date)) }}
                                                </td>
                                                <td class="align-middle text-nowrap border-right-0 border-left-0">
                                                    <a class=""
                                                       href="{{ url('accounts/accountant/ledger') }}?account_id={{$e -> first_account_id}}&date={{$e->posting_date}}"
                                                       target="_blank">
                                                        {{ $e->nature=='pay' ? 'Dr': ($e->nature=='transfer_entry' ? 'Dr' : 'Cr') }}
                                                        - {{ $e -> first_account_name }}
                                                    </a>
                                                    <br>
                                                    <a class=""
                                                       href="{{ url('accounts/accountant/ledger') }}?account_id={{$e -> second_account_id}}&date={{$e->posting_date}}"
                                                       target="_blank">
                                                        {{ $e->nature!='pay' ? ($e->nature=='transfer_entry' ? 'Cr' : 'Dr') :'Cr' }}
                                                        - {{ $e -> second_account_name }}
                                                    </a>
                                                </td>
                                                <td class="align-middle text-center border-right-0 border-left-0">
                                                    {{ $e->description }}
                                                </td>
                                                <td class="align-middle text-center border-right-0 border-left-0">
                                                    {{ number_format($e->amount,2)  }}
                                                </td>
                                                <td class="align-middle text-center text-nowrap border-right-0 border-left-0">
                                                    {{ $e->added_by }} <br>
                                                    {{ date('d M, Y h:i A',strtotime($e->created_at)) }}
                                                </td>
                                                <td class="align-middle text-center text-nowrap border-right-0 border-left-0">
                                                    @if(!empty($e->approved_at))
                                                        {{ $e->approved_by_name }} <br>
                                                        {{ date('d M, Y h:i A',strtotime($e->approved_at)) }}
                                                    @endif
                                                </td>
                                                <td class="align-middle border-left-0" style="width: 50px;">

                                                    <div class="nav-item dropdown">
                                                        <div class="user-panel d-flex nav-link m-0 p-0"
                                                             data-toggle="dropdown" style="cursor: pointer;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                 viewBox="0 0 24 24" stroke-width="1.5"
                                                                 stroke="currentColor" class="w-6 h-6"
                                                                 style="width: 25px; height: 25px">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
                                                            </svg>


                                                        </div>
                                                        <div class="dropdown-menu  dropdown-menu-right">
                                                            @if(empty($e->approved_at))
                                                                <a wire:click="approve('{{ $e->id }}')"
                                                                   class="dropdown-item" target="_blank">
                                                                    Approve
                                                                </a>

                                                                <a type="button"
                                                                   wire:click="delete('{{ $e->id }}')"
                                                                   class="text-dark mx-3"> Delete </a>
                                                            @else
                                                                <a href="javascript:void(0);" --}}
                                                                   onclick="window.open('{{ url('accounts/journal/voucher/print').'/'.$e->voucher_no }}','voucher-print-{{$e->voucher_no}}','height=500,width=800');"
                                                                   class="dropdown-item"
                                                                   role="menuitem" tabindex="-1">View Voucher</a>
                                                                @if($e->reversal=='f' && auth()->user()->can('2.payments.reversal'))
                                                                    <a type="button"
                                                                       wire:click="openReverseModal('{{ $e->id }}')"
                                                                       class="text-dark mx-3"
                                                                       role="menuitem" tabindex="-1">
                                                                        Reverse Entry
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($entries->hasPages())
                                    <div class="card-footer d-flex justify-content-center">
                                        {{ $entries->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reverseEntry" wire:ignore.self tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form wire:submit.prevent="deleteService">

                        <div class="modal-header">
                            <h2 class="modal-title" id="exampleModalLabel">Attention</h2>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                        </div>

                        <div class="modal-body" id="std_form">
                            <div class="mt-2">
                                <p class="text-sm">
                                    Are you sure you want to reverse the entry? This
                                    can't be undone.
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="reverseEntry"
                                    class="btn btn-primary">
                            <span wire:loading.remove wire:target="reverseEntry">
                                Reverse Entry
                            </span>
                                <span wire:loading wire:target="reverseEntry">
                                Please Wait...
                            </span>
                            </button>
                            <button type="button" wire:loading.attr="disabled"
                                    class="btn btn-danger"
                                    data-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
            window.addEventListener('close-reverse-modal', event => {
                $('#reverseEntry').modal('hide');
            });
            window.addEventListener('open-reverse-modal', event => {
                $('#reverseEntry').modal('show');
            });
            let from_date = new Pikaday({
                field: document.getElementById('from'),
                format: "DD MMM YYYY"
            });

            let to_date = new Pikaday({
                field: document.getElementById('to'),
                format: "DD MMM YYYY"
            });

            from_date.setDate(new Date('{{ $from }}'));
            to_date.setDate(new Date('{{ $to }}'));

        </script>
    @endpush
@else
    <div>
        <div class="shadow mb-5 overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6 ">
                <div class="grid grid-cols-8 gap-6">
                    <div class="col-span-8 sm:col-span-1">
                        <label for="nature" class="block text-sm font-medium text-gray-700">Nature</label>
                        <select wire:model.defer="nature"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="pay">Paid</option>
                            <option value="receive">Received</option>
                        </select>
                    </div>

                    <div class="col-span-8 sm:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.defer="status"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="t">Approve</option>
                            <option value="f">Not Approved</option>
                        </select>

                    </div>

                    <div class="col-span-8 sm:col-span-2">
                        <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                        <input type="text" readonly wire:model.lazy="from" id="from" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-8 sm:col-span-2">
                        <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                        <input type="text" readonly wire:model.lazy="to" id="to" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-8 sm:col-span-2">
                        <button type="button" wire:click="search" wire:loading.attr="disabled"
                                class="bg-white mt-6 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading wire:target="search">Searching ...</span>
                            <span wire:loading.remove wire:target="search">Search</span>
                        </button>

                        <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="shadow sm:rounded-md   bg-white">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6 rounded-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payments & Receiving</h3>
                    <a href="{{  url('accounts/accountant/payments/add') }}"
                       class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        Create
                    </a>
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
                                            <li>{{ $error }}</li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty($success))
                    <div class="rounded-md bg-green-50 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Heroicon name: check-circle -->
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ $success }}
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" wire:click="$set('success', '')"
                                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                        <span class="sr-only">Dismiss</span>
                                        <!-- Heroicon name: x -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>


            <table class="min-w-full divide-y divide-gray-200  rounded-md ">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        #
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nature
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Accounts
                    </th>
                    <th scope="col"
                        class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>

                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                    </th>

                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created By
                    </th>

                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Approved By
                    </th>
                    <th scope="col" class="relative px-6 py-3">

                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200  rounded-md">
                @foreach($entries as $e)
                    <tr>
                        <td class="px-6 py-4  text-sm font-medium text-gray-900">
                            {{ $loop -> iteration }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">

                            @if($e->nature=='pay')
                                <span
                                        class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                              Payment
                            </span>
                            @else
                                <span
                                        class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                              Received
                            </span>
                            @endif
                            @if($e->reversal == 't')
                                <br>
                                <span
                                        class="inline-flex mt-2 items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                              Reversed
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ date('d M, Y',strtotime($e->posting_date)) }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">

                            <a class="text-indigo-600 hover:text-blue-900"
                               href="{{ url('accounts/accountant/ledger') }}?account_id={{$e -> first_account_id}}&date={{$e->posting_date}}"
                               target="_blank">{{ $e->nature=='pay' ? 'Dr':'Cr' }} - {{ $e -> first_account_name }}</a>
                            <br>
                            <a class="text-indigo-600 hover:text-blue-900"
                               href="{{ url('accounts/accountant/ledger') }}?account_id={{$e -> second_account_id}}&date={{$e->posting_date}}"
                               target="_blank">{{ $e->nature!='pay' ? 'Dr':'Cr' }} - {{ $e -> second_account_name }}</a>
                        </td>

                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ $e -> description }}
                        </td>

                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ number_format($e->amount,2)  }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ $e->added_by }} <br>
                            {{ date('d M, Y h:i A',strtotime($e->created_at)) }}
                        </td>

                        <td class="px-6 py-4  text-sm text-gray-500">
                            @if(!empty($e->approved_at))
                                {{ $e->approved_by_name }} <br>
                                {{ date('d M, Y h:i: A',strtotime($e->approved_at)) }}
                            @endif
                        </td>
                        <td class="px-6 py-4  text-right text-sm font-medium">


                            <div class="relative inline-block text-left" x-data="{open:false}">
                                <div>
                                    <button type="button" x-on:click="open=true;" @click.away="open=false;"
                                            class="  rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                                            id="menu-button" aria-expanded="true" aria-haspopup="true">
                                        <span class="sr-only">Open options</span>
                                        <!-- Heroicon name: solid/dots-vertical -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor" aria-hidden="true">
                                            <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                </div>


                                <div x-show="open"
                                     class="origin-top-right absolute right-0 mt-2 w-56 z-10 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                     tabindex="-1">
                                    <div class="py-1" role="none">

                                        @if(empty($e->approved_at))

                                            <a href="#" wire:click="approve('{{ $e->id }}')"
                                               class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                               role="menuitem" tabindex="-1">Approve</a>


                                            <a href="#" wire:click="delete('{{ $e->id }}')"
                                               class="text-red-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                               role="menuitem" tabindex="-1">Delete</a>

                                        @else
                                            <a href="javascript:void(0);"
                                               onclick="window.open('{{ url('accounts/journal/voucher/print').'/'.$e->voucher_no }}','voucher-print-{{$e->voucher_no}}','height=500,width=800');"
                                               class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                               role="menuitem" tabindex="-1">View Voucher</a>

                                            @if($e->reversal=='f' && auth()->user()->can('2.payments.reversal'))
                                                <button type="button" wire:click="openReverseModal('{{ $e->id }}')"

                                                        class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                                        role="menuitem" tabindex="-1">
                                                    Reverse Entry
                                                </button>
                                            @endif

                                        @endif


                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach


                </tbody>
            </table>
            @if($entries->hasPages())
                <div class="bg-white border-t px-3 py-2  rounded-md">
                    {{ $entries->links() }}
                </div>
            @endif
        </div>
        <div x-data="{ open: @entangle('reverse_modal') }" x-show="open" class="fixed z-10 inset-0 overflow-y-auto"
             aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="open" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-description="Background overlay, show/hide based on modal state."
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"
                     aria-hidden="true"></div>


                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                <div x-show="open" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-description="Modal panel, show/hide based on modal state."
                     class="inline-block align-bottom bg-white rounded-lg px-4 sm:p-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button"
                                class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                @click="open = false">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" x-description="Heroicon name: outline/x"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @if($reverse_modal == true)

                        <div class="sm:flex sm:items-start">
                            <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" x-description="Heroicon name: outline/exclamation"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Attention !
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to reverse the entry? This
                                        can't be undone.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="reverseEntry"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="open = false">
                            <span wire:loading.remove wire:target="reverseEntry">
                                Reverse Entry
                            </span>
                                <span wire:loading wire:target="reverseEntry">
                                Please Wait...
                            </span>
                            </button>
                            <button type="button" wire:loading.attr="disabled"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="open = false">
                                Cancel
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
@endif




@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script>
        let from_date = new Pikaday({
            field: document.getElementById('from'),
            format: "DD MMM YYYY"
        });

        let to_date = new Pikaday({
            field: document.getElementById('to'),
            format: "DD MMM YYYY"
        });

        from_date.setDate(new Date('{{ $from }}'));
        to_date.setDate(new Date('{{ $to }}'));

    </script>
@endsection
