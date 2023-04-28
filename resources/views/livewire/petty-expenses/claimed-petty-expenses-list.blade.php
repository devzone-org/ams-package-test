@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <p class="card-title pt-1"><b>Search Filters</b></p>
                                <a href="{{  url('/accounts/petty-expenses') }}"
                                   class="btn btn-primary">
                                    Add Petty Expenses
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(!$reject_modal)
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
                            @endif

                            <form wire:submit.prevent="search">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Invoice Date </label>
                                            <input type="date" wire:model.lazy="filter.invoice_date" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Name </label>
                                            <input type="text" wire:model.lazy="filter.name" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Contact # </label>
                                            <input type="text" wire:model.lazy="filter.contact_no" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Account Head </label>
                                            <select wire:model.defer="filter.account_head_id"
                                                    class="form-control">
                                                <option value=""></option>
                                                @foreach($fetch_account_heads as $a)
                                                    <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-4 pt-4 mt-2">
                                        <div class="form-group">
                                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                                                Search
                                            </button>
                                            <button class="btn btn-danger" type="button" wire:click="clear"
                                                    wire:loading.attr="disabled">Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <form wire:submit.prevent="claim">

                            <div class="card-header">
                                <h4 class="card-title"><b>Claimed Petty Expenses</b></h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered border-0">
                                    <thead class="">
                                    @if(!empty($petty_expenses_list))
                                        <th class="add-services-table">
                                            <input type="checkbox"
                                                   wire:model="checked_all"
                                                   class=""/>
                                        </th>
                                    @endif
                                    <th class="add-services-table text-muted">#</th>
                                    <th class="add-services-table text-muted">Invoice Date</th>
                                    <th class="add-services-table text-left text-muted">Vendor</th>
                                    <th class="add-services-table text-left text-muted">Account Head</th>
                                    <th class="add-services-table text-left text-muted">Expense Head</th>
                                    <th class="add-services-table text-left text-muted">Description</th>
                                    <th class="add-services-table text-right text-muted">Amount</th>
                                    <th class="add-services-table text-right text-muted">Claimed By</th>
                                    <th class="text-center add-services-table text-muted" style="width: 20px;"></th>
                                    </thead>
                                    <tbody class="">
                                    @forelse($petty_expenses_list as $pe)

                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                       wire:model="checked_petty_expenses.{{$pe['id']}}"
                                                       class=""/>
                                            </td>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ date('d M, Y',strtotime($pe['invoice_date'])) }}
                                            </td>
                                            <td>
                                                {{ ucwords($pe['vendor_name']) }}
                                                <br>{{ ucwords($pe['vendor_contact_no']) }}
                                            </td>
                                            <td>
                                                {{ ucwords($pe['account_head']) }}
                                            </td>
                                            <td>
                                                {{ ucwords($pe['expense_head']) }}
                                            </td>
                                            <td>
                                                {{ ucfirst($pe['description']) }}
                                            </td>
                                            <td>
                                                {{ number_format($pe['amount'],2) }}
                                            </td>
                                            <td>
                                                {{ucwords($pe['claimed_by'])}}<br>
                                                @ {{date('d M, Y',strtotime($pe['claimed_at']))}}
                                            </td>
                                            <td>
                                                @if(empty($pe['attachment']))
                                                    -
                                                @else
                                                    <a href="{{ env('AWS_URL').$pe['attachment'] }}"
                                                       class="text-primary" target="_blank">
                                                        View Attachment
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($loop->last)
                                            <tr class="">
                                                <td class="px-2 py-2 text-right" colspan="7">
                                                </td>
                                                <td class="px-2 py-2 text-left" colspan="1">
                                                    <b>Selected Bills</b>
                                                </td>
                                                <td class="px-2 py-2 text-right" colspan="1">
                                                    <b>{{number_format(count(array_filter(array_keys($checked_petty_expenses))))}}</b>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="px-2 py-2 text-right" colspan="7">
                                                </td>
                                                <td class="px-2 py-2 text-left" colspan="1">
                                                    <b>Selected Amount</b>
                                                </td>
                                                <td class="px-2 py-2 text-right" colspan="1">
                                                    @php
                                                        $amount = collect($petty_expenses_list)->whereIn('id',array_keys(array_filter($checked_petty_expenses)))->sum('amount');
                                                    @endphp
                                                    <b>{{number_format($amount ?? 0,2)}}</b>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-danger rounded-md overflow-hidden">
                                                <div class="alert alert-danger mb-0">
                                                    No Records Found.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    </tbody>
                                </table>
                                {{--                                <div x-data="{ open: @entangle('approve_modal') }" x-show="open"--}}
                                {{--                                     class="fixed z-10 inset-0 overflow-y-auto"--}}
                                {{--                                     aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">--}}
                                {{--                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">--}}

                                {{--                                        <div x-show="open" x-transition:enter="ease-out duration-300"--}}
                                {{--                                             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"--}}
                                {{--                                             x-transition:leave="ease-in duration-200"--}}
                                {{--                                             x-transition:leave-start="opacity-100"--}}
                                {{--                                             x-transition:leave-end="opacity-0"--}}
                                {{--                                             x-description="Background overlay, show/hide based on modal state."--}}
                                {{--                                             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"--}}
                                {{--                                             aria-hidden="true"></div>--}}


                                {{--                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"--}}
                                {{--                                              aria-hidden="true">​</span>--}}

                                {{--                                        <div x-show="open" x-transition:enter="ease-out duration-300"--}}
                                {{--                                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
                                {{--                                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"--}}
                                {{--                                             x-transition:leave="ease-in duration-200"--}}
                                {{--                                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"--}}
                                {{--                                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
                                {{--                                             x-description="Modal panel, show/hide based on modal state."--}}
                                {{--                                             class="inline-block align-bottom bg-white rounded-lg px-4 sm:p-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">--}}
                                {{--                                            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">--}}
                                {{--                                                <button type="button" wire:click="closeApproveModal"--}}
                                {{--                                                        wire:loading.attr="disabled"--}}
                                {{--                                                        class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"--}}
                                {{--                                                        @click="open = false">--}}
                                {{--                                                    <span class="sr-only">Close</span>--}}
                                {{--                                                    <svg class="h-6 w-6" x-description="Heroicon name: outline/x"--}}
                                {{--                                                         xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                                {{--                                                         viewBox="0 0 24 24"--}}
                                {{--                                                         stroke="currentColor" aria-hidden="true">--}}
                                {{--                                                        <path stroke-linecap="round" stroke-linejoin="round"--}}
                                {{--                                                              stroke-width="2"--}}
                                {{--                                                              d="M6 18L18 6M6 6l12 12"></path>--}}
                                {{--                                                    </svg>--}}
                                {{--                                                </button>--}}
                                {{--                                            </div>--}}
                                {{--                                            @if($approve_modal)--}}
                                {{--                                                <div class="sm:flex sm:items-start">--}}
                                {{--                                                    <div--}}
                                {{--                                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">--}}
                                {{--                                                        <svg class="h-6 w-6 text-red-600"--}}
                                {{--                                                             x-description="Heroicon name: outline/exclamation"--}}
                                {{--                                                             xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                                {{--                                                             viewBox="0 0 24 24"--}}
                                {{--                                                             stroke="currentColor" aria-hidden="true">--}}
                                {{--                                                            <path stroke-linecap="round" stroke-linejoin="round"--}}
                                {{--                                                                  stroke-width="2"--}}
                                {{--                                                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>--}}
                                {{--                                                        </svg>--}}
                                {{--                                                    </div>--}}
                                {{--                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">--}}
                                {{--                                                        <h3 class="text-lg leading-6 font-medium text-gray-900"--}}
                                {{--                                                            id="modal-title">--}}
                                {{--                                                            Attention !--}}
                                {{--                                                        </h3>--}}
                                {{--                                                        <div class="mt-2">--}}
                                {{--                                                            <p class="text-sm text-gray-500">--}}
                                {{--                                                                {{ $approve_modal_msg }}--}}
                                {{--                                                            </p>--}}

                                {{--                                                        </div>--}}
                                {{--                                                    </div>--}}
                                {{--                                                </div>--}}
                                {{--                                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">--}}
                                {{--                                                    <button type="submit" wire:loading.attr="disabled"--}}
                                {{--                                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"--}}
                                {{--                                                            @click="open = false">--}}
                                {{--                                                        Approve--}}
                                {{--                                                    </button>--}}
                                {{--                                                    <button type="button" wire:click="closeApproveModal"--}}
                                {{--                                                            wire:loading.attr="disabled"--}}
                                {{--                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"--}}
                                {{--                                                            @click="open = false">--}}
                                {{--                                                        Cancel--}}
                                {{--                                                    </button>--}}
                                {{--                                                </div>--}}
                                {{--                                            @endif--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                --}}{{--reject modal --}}
                                {{--                                <div x-data="{ open: @entangle('reject_modal') }" x-show="open"--}}
                                {{--                                     class="fixed z-10 inset-0 overflow-y-auto"--}}
                                {{--                                     aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">--}}
                                {{--                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">--}}
                                {{--                                        ​--}}
                                {{--                                        <div x-show="open" x-transition:enter="ease-out duration-300"--}}
                                {{--                                             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"--}}
                                {{--                                             x-transition:leave="ease-in duration-200"--}}
                                {{--                                             x-transition:leave-start="opacity-100"--}}
                                {{--                                             x-transition:leave-end="opacity-0"--}}
                                {{--                                             x-description="Background overlay, show/hide based on modal state."--}}
                                {{--                                             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"--}}
                                {{--                                             aria-hidden="true"></div>--}}
                                {{--                                        ​--}}
                                {{--                                        ​--}}
                                {{--                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"--}}
                                {{--                                              aria-hidden="true">​</span>--}}
                                {{--                                        ​--}}
                                {{--                                        <div x-show="open" x-transition:enter="ease-out duration-300"--}}
                                {{--                                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
                                {{--                                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"--}}
                                {{--                                             x-transition:leave="ease-in duration-200"--}}
                                {{--                                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"--}}
                                {{--                                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"--}}
                                {{--                                             x-description="Modal panel, show/hide based on modal state."--}}
                                {{--                                             class="inline-block align-bottom bg-white rounded-lg px-4 sm:p-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">--}}
                                {{--                                            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">--}}
                                {{--                                                <button type="button" wire:click="closeRejectModal"--}}
                                {{--                                                        wire:loading.attr="disabled"--}}
                                {{--                                                        class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"--}}
                                {{--                                                        @click="open = false">--}}
                                {{--                                                    <span class="sr-only">Close</span>--}}
                                {{--                                                    <svg class="h-6 w-6" x-description="Heroicon name: outline/x"--}}
                                {{--                                                         xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                                {{--                                                         viewBox="0 0 24 24"--}}
                                {{--                                                         stroke="currentColor" aria-hidden="true">--}}
                                {{--                                                        <path stroke-linecap="round" stroke-linejoin="round"--}}
                                {{--                                                              stroke-width="2"--}}
                                {{--                                                              d="M6 18L18 6M6 6l12 12"></path>--}}
                                {{--                                                    </svg>--}}
                                {{--                                                </button>--}}
                                {{--                                            </div>--}}
                                {{--                                            @if(!empty($reject_modal))--}}
                                {{--                                                <div>--}}
                                {{--                                                    @if ($errors->any())--}}
                                {{--                                                        <div class="p-4 mb-4 rounded-md bg-red-50">--}}
                                {{--                                                            <div class="flex">--}}
                                {{--                                                                <div class="flex-shrink-0">--}}
                                {{--                                                                    <svg class="w-5 h-5 text-red-400"--}}
                                {{--                                                                         xmlns="http://www.w3.org/2000/svg"--}}
                                {{--                                                                         viewBox="0 0 20 20"--}}
                                {{--                                                                         fill="currentColor" aria-hidden="true">--}}
                                {{--                                                                        <path fill-rule="evenodd"--}}
                                {{--                                                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"--}}
                                {{--                                                                              clip-rule="evenodd"/>--}}
                                {{--                                                                    </svg>--}}
                                {{--                                                                </div>--}}
                                {{--                                                                <div class="ml-3">--}}
                                {{--                                                                    <h3 class="text-sm font-medium text-red-800">--}}
                                {{--                                                                        There {{ $errors->count() > 1? 'were' : 'was' }} {{ $errors->count() }} {{--}}
                                {{--                                $errors->count() > 1? 'errors' : 'error' }}--}}
                                {{--                                                                        with your submission--}}
                                {{--                                                                    </h3>--}}
                                {{--                                                                    <div class="mt-2 text-sm text-red-700">--}}
                                {{--                                                                        <ul class="pl-5 space-y-1 list-disc">--}}
                                {{--                                                                            @foreach ($errors->all() as $error)--}}
                                {{--                                                                                <li>{{ $error }}</li>--}}
                                {{--                                                                            @endforeach--}}
                                {{--                                                                        </ul>--}}
                                {{--                                                                    </div>--}}
                                {{--                                                                </div>--}}
                                {{--                                                            </div>--}}
                                {{--                                                        </div>--}}
                                {{--                                                    @endif--}}
                                {{--                                                </div>--}}
                                {{--                                                <div class="">--}}
                                {{--                                                    <h2 class="text-lg font-semibold"> Reject Details</h2>--}}
                                {{--                                                </div>--}}

                                {{--                                                <div class="mt-5 flex flex-col">--}}
                                {{--                                                    <div class="-my-2 -mx-6 overflow-x-auto sm:-mx-6 lg:-mx-8">--}}
                                {{--                                                        <div class="inline-block min-w-full py-2 align-middle px-2">--}}
                                {{--                                                            <div class="overflow-hidden border-t">--}}
                                {{--                                                                <table class="min-w-full divide-y divide-gray-300">--}}
                                {{--                                                                    <thead class="">--}}
                                {{--                                                                    <tr class="h-12">--}}
                                {{--                                                                        <th scope="col"--}}
                                {{--                                                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">--}}
                                {{--                                                                            Selected Bills--}}
                                {{--                                                                        </th>--}}
                                {{--                                                                        <td scope="col"--}}
                                {{--                                                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-500 sm:pl-6">--}}
                                {{--                                                                            {{number_format(count(array_filter(array_keys($checked_petty_expenses))))}}--}}
                                {{--                                                                        </td>--}}
                                {{--                                                                    </tr>--}}
                                {{--                                                                    <tr class="h-12">--}}
                                {{--                                                                        <th scope="col"--}}
                                {{--                                                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">--}}
                                {{--                                                                            Selected Amount--}}
                                {{--                                                                        </th>--}}
                                {{--                                                                        <td scope="col"--}}
                                {{--                                                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-500 sm:pl-6">--}}
                                {{--                                                                            {{number_format($amount ?? 0,2)}}--}}
                                {{--                                                                        </td>--}}
                                {{--                                                                    </tr>--}}
                                {{--                                                                    </thead>--}}
                                {{--                                                                </table>--}}
                                {{--                                                            </div>--}}
                                {{--                                                        </div>--}}
                                {{--                                                    </div>--}}
                                {{--                                                </div>--}}

                                {{--                                                <div class="mt-4 w-full">--}}
                                {{--                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Reject--}}
                                {{--                                                        Reason--}}
                                {{--                                                        <span class="text-red-500 text-sm">*</span>--}}
                                {{--                                                    </label>--}}
                                {{--                                                    <textarea wire:model.lazy='reject_reason'--}}
                                {{--                                                              class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">--}}
                                {{--                                </textarea>--}}
                                {{--                                                </div>--}}

                                {{--                                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">--}}
                                {{--                                                    <button type="button" wire:click.prevent="reject"--}}
                                {{--                                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">--}}
                                {{--                                                        Reject--}}
                                {{--                                                    </button>--}}
                                {{--                                                    <button type="button" wire:click="closeRejectModal"--}}
                                {{--                                                            wire:loading.attr="disabled"--}}
                                {{--                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"--}}
                                {{--                                                            @click="open = false">--}}
                                {{--                                                        Cancel--}}
                                {{--                                                    </button>--}}
                                {{--                                                </div>--}}
                                {{--                                            @endif--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}

                                @if(count(array_filter($checked_petty_expenses)) > 0)
                                    <div class="w-100 d-flex justify-content-end">
                                        <div class="p-4">
                                            <button type="button" wire:loading.attr="disabled"
                                                    wire:click.prevent="openApproveModal"
                                                    class="">
                                                Approve
                                            </button>

                                            <button type="button" wire:click="openRejectModal"
                                                    wire:loading.attr="disabled"
                                                    class="">
                                                Reject
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div>
        <div class="mb-5 shadow sm:rounded-md sm:overflow-hidden bg-white">
            @if(!$reject_modal)
                @if ($errors->any())
                    <div class="px-6 pt-6">
                        <div class="p-4 rounded-md bg-red-50">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There {{ $errors->count() > 1? 'were' : 'was' }} {{ $errors->count() }} {{
                                $errors->count() > 1? 'errors' : 'error' }}
                                        with your submission
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="pl-5 space-y-1 list-disc">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty($success) || session()->has('success'))
                    <div class="px-6 pt-6">
                        <div class="p-4  rounded-md bg-green-50">
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
                                        @if(!empty($success))
                                            {{ $success }}
                                        @elseif(session()->has('success'))
                                            {{ session('success') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button type="button" wire:click="$set('success', '')"
                                                class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                            <span class="sr-only">Dismiss</span>
                                            <!-- Heroicon name: x -->
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20"
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
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="px-6 pt-6">
                        <div class="p-4 rounded-md bg-red-50">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There was an error with your submission.
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>{{session('error')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="p-4 px-6 flex justify-between border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Search Filters</h3>
                <a href="/accounts/petty-expenses"
                   class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Add Petty Expenses
                </a>
            </div>
            <form wire:submit.prevent="search">
                <div class="py-6 px-4 space-y-6 sm:p-6">
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Invoice Date </label>
                            <input type="date" wire:model.lazy="filter.invoice_date" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Name </label>
                            <input type="text" wire:model.lazy="filter.name" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Contact # </label>
                            <input type="text" wire:model.lazy="filter.contact_no" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Account Head </label>
                            <select wire:model.defer="filter.account_head_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                @foreach($fetch_account_heads as $a)
                                    <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                    <div class="w-full flex justify-end">
                        <div>
                            <button type="submit" wire:loading.attr="disabled"
                                    class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>

                            <button type="button" wire:click="clear" wire:loading.attr="disabled"
                                    class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Reset
                            </button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
        <div class="shadow rounded-md">
            <form wire:submit.prevent="approve">
                <div class="bg-white  mb-5 rounded-md overflow-hidden">
                    <div class="py-6 px-4 sm:p-6 flex justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Claimed Petty
                            Expenses</h3>
                    </div>
                    <table class="min-w-full table-fixed  ">
                        <thead class="">
                        <tr class="">
                            @if(!empty($petty_expenses_list))
                                <th scope="col"
                                    class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                                    <input type="checkbox"
                                           wire:model="checked_all"
                                           class="cursor-pointer relative w-5 h-5 border rounded border-gray-400 bg-white text-indigo-500 focus:outline-none focus:ring-2  focus:ring-indigo-500"/>
                                </th>
                            @endif
                            <th scope="col"
                                class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                                #
                            </th>
                            <th scope="col"
                                class="px-2 py-2   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider"
                                style="width: 110px;">
                                Invoice Date
                            </th>
                            <th scope="col"
                                class="px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                                Vendor
                            </th>
                            <th scope="col"
                                class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                                Account Head
                            </th>
                            <th scope="col"
                                class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                                Expense Head
                            </th>
                            <th scope="col"
                                class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                                Description
                            </th>

                            <th scope="col"
                                class="px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                                Amount
                            </th>
                            <th scope="col"
                                class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider"
                                style="width: 170px;">
                                Claimed By
                            </th>
                            {{--                        <th scope="col" style="width: 90px;"--}}
                            {{--                            class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">--}}
                            {{--                            Status--}}
                            {{--                        </th>--}}
                            <th scope="col"
                                class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">

                            </th>

                        </tr>
                        </thead>
                        <tbody class="bg-white  ">

                        @forelse($petty_expenses_list as $pe)
                            <tr class="{{ $loop->first ? 'border-t': '' }}   border-b">
                                <td class="px-2 py-2  border-r text-sm text-gray-500">
                                    <input type="checkbox"
                                           wire:model="checked_petty_expenses.{{$pe['id']}}"
                                           class="cursor-pointer relative w-5 h-5 border rounded border-gray-400 bg-white text-indigo-500 focus:outline-none focus:ring-2  focus:ring-indigo-500"/>
                                </td>
                                <td class="px-2 py-2  border-r text-sm text-gray-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ date('d M, Y',strtotime($pe['invoice_date'])) }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ ucwords($pe['vendor_name']) }}<br>{{ ucwords($pe['vendor_contact_no']) }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ ucwords($pe['account_head']) }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ ucwords($pe['expense_head']) }}
                                </td>
                                <td class=" px-2 py-2 border-r text-sm text-gray-500 whitespace-initial"
                                    style="width: 400px !important;">
                                    {{ ucfirst($pe['description']) }}
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    {{ number_format($pe['amount'],2) }}
                                </td>
                                <td class="px-2 py-2 border-r text-left text-sm text-gray-500">
                                    {{ucwords($pe['claimed_by'])}}<br>
                                    @ {{date('d M, Y',strtotime($pe['claimed_at']))}}
                                </td>
                                {{--                            <td class="px-2 py-2 border-r text-left text-sm text-gray-500">--}}
                                {{--                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Claimed</span>--}}
                                {{--                            </td>--}}
                                <td class="px-2 py-2 border-r text-sm text-gray-500" style="width: 150px;">
                                    @if(empty($pe['attachment']))
                                        -
                                    @else
                                        <a href="{{ env('AWS_URL').$pe['attachment'] }}"
                                           class="text-yellow-500 font-medium" target="_blank">
                                            View Attachment
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @if($loop->last)
                                <tr class="{{ $loop->first ? 'border-t': '' }}   border-b">
                                    <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="7">
                                    </td>
                                    <td class="px-2 py-2 border-r text-left text-sm text-gray-500" colspan="1">
                                        <b>Selected Bills</b>
                                    </td>
                                    <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="1">
                                        <b>{{number_format(count(array_filter(array_keys($checked_petty_expenses))))}}</b>
                                    </td>
                                </tr>
                                <tr class="{{ $loop->first ? 'border-t': '' }}  border-b">
                                    <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="7">
                                    </td>
                                    <td class="px-2 py-2 border-r text-left text-sm text-gray-500" colspan="1">
                                        <b>Selected Amount</b>
                                    </td>
                                    <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="1">
                                        @php
                                            $amount = collect($petty_expenses_list)->whereIn('id',array_keys(array_filter($checked_petty_expenses)))->sum('amount');
                                        @endphp
                                        <b>{{number_format($amount ?? 0,2)}}</b>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-sm text-red-500 rounded-md overflow-hidden">
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

                    <div x-data="{ open: @entangle('approve_modal') }" x-show="open"
                         class="fixed z-10 inset-0 overflow-y-auto"
                         aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                            <div x-show="open" x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 x-description="Background overlay, show/hide based on modal state."
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
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
                                    <button type="button" wire:click="closeApproveModal" wire:loading.attr="disabled"
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
                                @if($approve_modal)
                                    <div class="sm:flex sm:items-start">
                                        <div
                                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600"
                                                 x-description="Heroicon name: outline/exclamation"
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
                                                    {{ $approve_modal_msg }}
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button type="submit" wire:loading.attr="disabled"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                                @click="open = false">
                                            Approve
                                        </button>
                                        <button type="button" wire:click="closeApproveModal"
                                                wire:loading.attr="disabled"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                                @click="open = false">
                                            Cancel
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{--reject modal --}}
                    <div x-data="{ open: @entangle('reject_modal') }" x-show="open"
                         class="fixed z-10 inset-0 overflow-y-auto"
                         aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            ​
                            <div x-show="open" x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 x-description="Background overlay, show/hide based on modal state."
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                 aria-hidden="true"></div>
                            ​
                            ​
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                            ​
                            <div x-show="open" x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-description="Modal panel, show/hide based on modal state."
                                 class="inline-block align-bottom bg-white rounded-lg px-4 sm:p-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                                    <button type="button" wire:click="closeRejectModal" wire:loading.attr="disabled"
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
                                @if(!empty($reject_modal))
                                    <div>
                                        @if ($errors->any())
                                            <div class="p-4 mb-4 rounded-md bg-red-50">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="w-5 h-5 text-red-400"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 20 20"
                                                             fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h3 class="text-sm font-medium text-red-800">
                                                            There {{ $errors->count() > 1? 'were' : 'was' }} {{ $errors->count() }} {{
                                $errors->count() > 1? 'errors' : 'error' }}
                                                            with your submission
                                                        </h3>
                                                        <div class="mt-2 text-sm text-red-700">
                                                            <ul class="pl-5 space-y-1 list-disc">
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="">
                                        <h2 class="text-lg font-semibold"> Reject Details</h2>
                                    </div>

                                    <div class="mt-5 flex flex-col">
                                        <div class="-my-2 -mx-6 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                            <div class="inline-block min-w-full py-2 align-middle px-2">
                                                <div class="overflow-hidden border-t">
                                                    <table class="min-w-full divide-y divide-gray-300">
                                                        <thead class="">
                                                        <tr class="h-12">
                                                            <th scope="col"
                                                                class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                Selected Bills
                                                            </th>
                                                            <td scope="col"
                                                                class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-500 sm:pl-6">
                                                                {{number_format(count(array_filter(array_keys($checked_petty_expenses))))}}
                                                            </td>
                                                        </tr>
                                                        <tr class="h-12">
                                                            <th scope="col"
                                                                class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                                Selected Amount
                                                            </th>
                                                            <td scope="col"
                                                                class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-500 sm:pl-6">
                                                                {{number_format($amount ?? 0,2)}}
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 w-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Reject Reason
                                            <span class="text-red-500 text-sm">*</span>
                                        </label>
                                        <textarea wire:model.lazy='reject_reason'
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </textarea>
                                    </div>

                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button type="button" wire:click.prevent="reject"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Reject
                                        </button>
                                        <button type="button" wire:click="closeRejectModal" wire:loading.attr="disabled"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                                @click="open = false">
                                            Cancel
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    @if(count(array_filter($checked_petty_expenses)) > 0)
                        <div class="w-full flex justify-end">
                            <div class="p-4">
                                <button type="button" wire:loading.attr="disabled" wire:click.prevent="openApproveModal"
                                        class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Approve
                                </button>

                                <button type="button" wire:click="openRejectModal" wire:loading.attr="disabled"
                                        class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Reject
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endif
