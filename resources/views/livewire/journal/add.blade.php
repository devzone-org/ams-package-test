@if(env('AMS_BOOTSTRAP') == 'true')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h1>General Journal</h1>
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
                                <h4 class="card-title"><b>Add Journal Entry</b></h4>
                                <div class="card-tools">
                                    {{--                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
                                    {{--                                        <i class="fas fa-minus"></i>--}}
                                    {{--                                    </button>--}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if ($errors->any())
                                        <div class="col-12">
                                            @foreach ($errors->all() as $error)

                                                <div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">
                                                        ×
                                                    </button>
                                                    <li>{!! $error !!}</li>
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
                                    <div class="col-sm-10 col-xs-12 no-padding">
                                        <div class="row">

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="font-weight-normal">Posting Date</label>
                                                    <input type="text" wire:model.lazy="posting_date"
                                                           id="posting_date" autocomplete="off"
                                                           class="form-control  @error('posting_date')  is-invalid @enderror">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="font-weight-normal">Temp Voucher #</label>
                                                    <input
                                                            type="text" wire:model="voucher_no" readonly id="voucher_no"
                                                            autocomplete="off"
                                                            class="form-control   @error('voucher_no')  is-invalid @enderror">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-bordered border-0">
                                        <thead class="text-nowrap">
                                        <th class="text-center add-services-table text-muted" style="width: 35px;">#
                                        </th>
                                        <th class="add-services-table text-center col-3 text-muted">Accounts</th>
                                        <th class="add-services-table text-center col-6 text-muted">Description</th>
                                        <th class="add-services-table text-center text-muted">Debit</th>
                                        <th class="add-services-table text-center text-muted">Credit</th>
                                        <th class="text-center add-services-table text-muted">
                                            <button class="outline-none border-0 p-0"
                                                    wire:click="addEntry()">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" class=""
                                                     style="width: 20px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                            </button>
                                        </th>
                                        </thead>
                                        <tbody class="text-nowrap">
                                        @foreach($entries as $key => $en)
                                            <tr>
                                                <td class="add-services-table text-center align-middle"
                                                    style="width: 25px;">{{ $loop->iteration }}</td>
                                                <td class="add-services-table col-3">
                                                    <input wire:click="searchAccounts('{{ $key }}')" type="text"
                                                           wire:model.lazy="entries.{{$key}}.account_name"
                                                           class="border-0 w-100 pt-2" id="searchbox"
                                                           autocomplete="off" style="outline: none;"
                                                           wire:key="'searchbox-' . {{$key}}">
                                                </td>
                                                <td class="add-services-table col-6">
                                                 <textarea wire:ignore.self
                                                           wire:model.lazy="entries.{{$key}}.description"
                                                           class="border-0 w-100 pt-2"
                                                           style="outline: none;height: 35px; overflow:hidden"></textarea>
                                                </td>
                                                <td class="add-services-table">
                                                    <input type="number" step="0.01"
                                                           wire:model.lazy="entries.{{$key}}.debit"
                                                           class="text-center p-0 border-0 w-100 pt-2"
                                                           onclick="this.select()"
                                                           autocomplete="off" style="outline: none; width: 25px">
                                                </td>
                                                <td class="add-services-table">
                                                    <input type="number" step="0.01"
                                                           wire:model.lazy="entries.{{$key}}.credit"
                                                           onclick="this.select()"
                                                           class="p-0 text-center border-0 w-100 pt-2"
                                                           autocomplete="off" style="outline: none; width: 25px">
                                                </td>
                                                <td class="text-center add-services-table ">
                                                    <button class="outline-none border-0 p-0 mt-2 bg-white"
                                                            type="button"
                                                            wire:click="removeEntry('{{ $key }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 24 24" fill="currentColor"
                                                             class="text-danger" style="width: 20px;">
                                                            <path fill-rule="evenodd"
                                                                  d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>

                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="3" class="text-right align-middle col-9 bg-white">Total</th>
                                            <td class="text-center pl-3">{{ number_format(collect($entries)->sum('debit'),2) }}</td>
                                            <td class="text-center">{{ number_format(collect($entries)->sum('credit'),2) }}</td>
                                            <td style="width: 35px"></td>
                                        </tr>
                                        <tr class="">
                                            <th colspan="3" class="text-right align-middle bg-white">Difference</th>
                                            <td colspan="2" class="text-center"> {{ number_format(abs(collect($entries)->sum('debit') - collect($entries)->sum('credit')),2) }}</td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title"><b>Attachments</b></h5>
                                <div class="card-tools">
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered border-0">
                                    <thead class="text-nowrap">
                                    <th class="text-center add-services-table text-muted" style="width: 25px;">#</th>
                                    <th class="add-services-table col-3 text-muted">Reference Account</th>
                                    <th class="add-services-table col-8 text-muted">Choose File</th>
                                    <th class="text-center add-services-table" style="width: 25px;">
                                        <button class="outline-none border-0 p-0" wire:click="addAttachmentEntry()">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class=""
                                                 style="width: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 4.5v15m7.5-7.5h-15"/>
                                            </svg>
                                        </button>
                                    </th>
                                    </thead>
                                    <tbody class="text-nowrap">
                                    @foreach($attachment_entries as $key => $en)
                                        <tr>
                                            <td class="add-services-table text-center pt-2 align-middle"
                                                style="width: 15px">{{ $loop->iteration }}</td>
                                            <td class="add-services-table col-3">
                                                <select id="{{$key}}-account_id" name=""
                                                        wire:model="attachment_entries.{{$key}}.account_id"
                                                        id="ref_account" class="border-0 w-100 pt-3"
                                                        style="outline: none">
                                                    <option value=""></option>
                                                    @foreach($entries as $e)
                                                        <option value="{{ $e['account_id'] }}">{{ $e['account_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="add-services-table col-8">
                                                @if(!isset($en['id']))
                                                    <input id="{{$key}}-file" type="file"
                                                           wire:model="attachment_entries.{{$key}}.file"
                                                           class="mt-2 mb-2 mx-2">
                                                @else
                                                    <a class="font-medium text-indigo-600 hover:text-indigo-500 pt-2"
                                                       href="{{ env('AWS_URL').$en['attachment'] }}" target="_blank">View
                                                        Attachment</a>
                                                @endif
                                            </td>
                                            <td class="text-center add-services-table" style="width: 25px;">
                                                <button class="outline-none border-0 p-0 mt-2 bg-white" type="button"
                                                        wire:click="removeAttachmentEntry('{{ $key }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 24 24" fill="currentColor"
                                                         class="text-danger" style="width: 20px;">
                                                        <path fill-rule="evenodd"
                                                              d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex  justify-content-between px-3">
            <div>
                <button type="button" wire:click="deleteAll" wire:loading.attr="disabled"
                        class="btn btn-danger">
                    Delete All
                </button>
            </div>
            @if(!$file_upload)
                <div>
                    @if((!empty(collect($entries)->sum('debit')) || !empty(collect($entries)->sum('credit'))  ) )
                        <button type="button" wire:click="draft" wire:loading.attr="disabled"
                                class="btn btn-secondary">
                            <span wire:loading.remove wire:target="draft">Save as Draft</span>
                            <span wire:loading wire:target="draft">Saving...</span>
                        </button>
                    @endif
                    @if(collect($entries)->sum('debit') == collect($entries)->sum('credit') && (!empty(collect($entries)->sum('debit')) || !empty(collect($entries)->sum('credit'))  ) )
                        <button type="button" wire:click="posted" wire:loading.attr="disabled"
                                class="btn btn-success">
                            <span wire:loading.remove wire:target="posted">Post for Approval</span>
                            <span wire:loading wire:target="posted">Posting...</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <div class="modal fade" id="searchmodal" wire:ignore.self tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content advance-modal-width">
                    {{--                    <div class="card-header">--}}
                    {{--                        <button type="button" class="close" data-dismiss="modal"--}}
                    {{--                                aria-label="Close"><span--}}
                    {{--                                    aria-hidden="true">&times;</span></button>--}}
                    {{--                        <h4 class="modal-title" id="exampleModalLabel">Received Advance Details</h4>--}}
                    {{--                    </div>--}}
                    <div class="modal-body p-0">

                        <div class="px-2 pt-2">
                            <div class="">
                                <input type="text"
                                       wire:model.debounce.500ms="search_accounts"
                                       wire:keydown.arrow-up="decrementHighlight"
                                       wire:keydown.arrow-down="incrementHighlight"
                                       wire:keydown.enter="selectionAccount"
                                       id="searchableField"
                                       class="rounded searchable_query" style="width: 580px;"
                                       autocomplete="off" autofocus>
                            </div>
                            <p class="pt-2" id="search-description">You can search accounts by
                                Name,
                                Code and Type.</p>
                        </div>
                        @if(!empty($accounts))
                            <table class="table border-0 table-hover p-0 m-0">
                                <thead class="text-nowrap">
                                <th style="padding: 7px;border-top: none;">Name</th>
                                <th class="col-1" style="padding: 7px;border-top: none;">Type</th>
                                </thead>
                                <tbody class="text-nowrap">
                                @foreach($accounts as $key=> $a)
                                    <tr style="cursor: pointer;"
                                        wire:click="chooseAccount('{{ $a['id'] }}','{{$a['name'] }}')"
                                        onmouseover="this.style.backgroundColor='#3d40e0';this.style.color='#ffffff';"
                                        onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#000000';">
                                        <td style="padding: 7px;border-top: none;">{{ $a['code'] }}
                                            - {{ $a['name'] }}</td>
                                        <td class="col-1" style="padding: 7px;border-top: none;">{{ $a['type'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            @if(strlen($search_accounts) < 2)
                                <p class="px-3">Please enter {{ 2 - strlen($search_accounts) }}
                                    or more
                                    {{ (2 - strlen($search_accounts)) > 1 ? 'characters' : 'character' }}</p>
                            @else
                                <p class="px-3">{{ empty($accounts) ? 'No Record Found': '' }}</p>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
            document.addEventListener('open-modal', function () {
                $('#searchmodal').modal('show');
                setTimeout(function () {
                    $("#searchableField").focus();
                }, 500);
            })
            document.addEventListener('close-modal', function () {
                $('#searchmodal').modal('hide');
            })
            let from_date = new Pikaday({
                field: document.getElementById('posting_date'),
                format: "DD MMM YYYY"
            });


            from_date.setDate(new Date('{{ $posting_date }}'));
        </script>
    @endpush
@else
    <div>

        <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add Journal Entry</h3>

                </div>

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="posting_date" class="block text-sm font-medium text-gray-700">Posting Date</label>
                        <input type="text" readonly wire:model.lazy="posting_date" id="posting_date" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="voucher_no" class="block text-sm font-medium text-gray-700">Temp Voucher #</label>
                        <input type="text" wire:model="voucher_no" readonly id="voucher_no" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    @if(env('AMS_CUSTOMER', false) === true)
                        @if($customer_account_present)
                            <div class="col-span-6 sm:col-span-3">
                                <label for="voucher_no" class="block text-sm font-medium text-gray-700">Invoice Paid</label>
                                <select wire:model="invoice_paid"
                                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value=""></option>
                                    <option value="t">Yes</option>
                                    <option value="f">No</option>
                                </select>
                            </div>
                            @if($invoice_paid == 't')
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="voucher_no" class="block text-sm font-medium text-gray-700">Month</label>
                                    <input type="month" wire:model="selected_month"  id="month" autocomplete="off"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            @endif
                        @endif
                    @endif

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
                                            <li>{!! $error !!}</li>
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
            <div>

                <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="w-7 px-2   border-r py-2 text-center text-xs font-medium text-gray-500  tracking-wider">
                            #
                        </th>
                        <th scope="col"
                            class="w-1/5 px-2   border-r py-2 text-center text-xs font-medium text-gray-500  tracking-wider">
                            Account
                        </th>
                        <th scope="col"
                            class="px-2 py-2   border-r text-center text-xs font-medium text-gray-500  tracking-wider">
                            Description
                        </th>
                        <th scope="col"
                            class="w-32 px-2 py-2   border-r text-center text-xs font-medium text-gray-500  tracking-wider">
                            Debit
                        </th>
                        <th scope="col"
                            class="w-32 px-2 py-2   border-r text-center text-xs font-medium text-gray-500  tracking-wider">
                            Credit
                        </th>
                        <th scope="col" wire:click="addEntry()"
                            class="w-10 cursor-pointer px-2 py-2   border-r text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <svg class="w-6 h-6  " fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($entries as $key => $en)
                        <tr>
                            <td class="px-2  text-center  border-r text-sm font-medium text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-2  text-left   border-r text-sm text-gray-500">
                                <input wire:click="searchAccounts('{{ $key }}')" type="text" readonly
                                       wire:model.lazy="entries.{{$key}}.account_name"
                                       class="p-0 focus:ring-0 block w-full  text-sm border-0 text-left "
                                       autocomplete="off">
                            </td>
                            <td class="px-2   text-left   border-r  text-sm text-gray-500">
                    <textarea wire:ignore.self cols="30" rows="1" wire:model.lazy="entries.{{$key}}.description"
                              class="  p-0  focus:ring-0 block w-full  text-sm border-0  text-left "></textarea>
                            </td>
                            <td class="px-2  text-center  border-r text-sm text-gray-500">
                                <input type="number" step="0.01" wire:model.lazy="entries.{{$key}}.debit"
                                       class="text-center p-0 focus:ring-0 block w-full   text-sm border-0  "
                                       onclick="this.select()"
                                       autocomplete="off">
                            </td>
                            <td class="px-2  text-center border-r text-sm text-gray-500">
                                <input type="number" step="0.01" wire:model.lazy="entries.{{$key}}.credit"
                                       onclick="this.select()"
                                       class="p-0 text-center focus:ring-0 block w-full  text-sm border-0  "
                                       autocomplete="off">
                            </td>
                            <td wire:click="removeEntry('{{ $key }}')"
                                class="  w-10 cursor-pointer px-2 py-3   border-r text-center text-xs font-medium text-red-700  tracking-wider  ">
                                <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                          clip-rule="evenodd"></path>
                                </svg>

                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="px-2  text-sm font-medium text-gray-900">

                        </td>
                        <td class="px-2     text-sm text-gray-500">

                        </td>
                        <th class="px-2    text-right  border-r  text-sm text-gray-900">
                            Total
                        </th>
                        <th class="px-2    border-r text-center text-sm text-gray-900">
                            {{ number_format(collect($entries)->sum('debit'),2) }}
                        </th>
                        <th class="px-2   border-r text-center text-sm text-gray-900">
                            {{ number_format(collect($entries)->sum('credit'),2) }}
                        </th>
                        <td class="  w-10 cursor-pointer px-2 py-3 py-3   border-r text-center text-xs font-medium text-red-700  tracking-wider  ">
                            &nbsp;
                        </td>
                    </tr>
                    <tr class="">
                        <td class="px-2  text-sm font-medium text-gray-900">

                        </td>
                        <td class="px-2     text-sm text-gray-500">

                        </td>
                        <th class="px-2    text-right  border-r  text-sm text-gray-900">
                            Difference
                        </th>
                        <th colspan="2" class="px-2    border-r text-center text-sm text-gray-900">
                            {{ number_format(abs(collect($entries)->sum('debit') - collect($entries)->sum('credit')),2) }}
                        </th>

                        <td class="  w-10 cursor-pointer px-2 py-3 py-3   border-r text-right text-xs font-medium text-red-700  tracking-wider  ">
                            &nbsp;
                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>
        </div>


        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Attachments</h3>
                </div>


            </div>
            <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="w-7 px-2   border-r py-2 text-left text-xs font-medium text-gray-500  tracking-wider">
                        #
                    </th>
                    <th scope="col"
                        class="w-1/5 px-2   border-r py-2 text-left text-xs font-medium text-gray-500  tracking-wider">
                        Reference Account
                    </th>
                    <th scope="col"
                        class="px-2 py-2   border-r text-left text-xs font-medium text-gray-500  tracking-wider">
                        Choose File
                    </th>

                    <th scope="col" wire:click="addAttachmentEntry()"
                        class=" w-10 cursor-pointer px-2 py-2   border-r text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-6 h-6  " fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($attachment_entries as $key => $en)
                    <tr>
                        <td class="px-2    border-r text-sm font-medium text-gray-900">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-2     border-r text-sm text-gray-500">
                            <select id="{{$key}}-account_id" name="" wire:model="attachment_entries.{{$key}}.account_id"
                                    id="ref_account" class="p-0 focus:ring-0 block w-full  text-sm border-0  ">
                                <option value=""></option>
                                @foreach($entries as $e)
                                    <option value="{{ $e['account_id'] }}">{{ $e['account_name'] }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-2      border-r  text-sm text-gray-500">
                            @if(!isset($en['id']))
                                <input id="{{$key}}-file" type="file" wire:model="attachment_entries.{{$key}}.file"
                                       onchange="progress()" onload="load()"
                                       wire:loading.remove>
                                <span class="col-sm-6 text-danger" wire:loading
                                      wire:target="attachment_entries.{{$key}}.file">
                                Uploading...
                            </span>
                            @else
                                <a class="font-medium text-indigo-600 hover:text-indigo-500"
                                   href="{{ env('AWS_URL').$en['attachment'] }}" target="_blank">View Attachment</a>
                            @endif
                        </td>

                        <td wire:click="removeAttachmentEntry('{{ $key }}')"
                            class="  w-10 cursor-pointer px-2 py-3   border-r text-right text-xs font-medium text-red-700  tracking-wider  ">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                      clip-rule="evenodd"></path>
                            </svg>

                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>


        <div class="py-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <p class="text-sm leading-6 font-medium text-gray-900">
                <button type="button" wire:click="deleteAll" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500  sm:w-auto sm:text-sm">
                    Delete All
                </button>
            </p>
            @if(!$file_upload)
                <div class="mt-3 flex sm:mt-0 sm:ml-4" id="hide">

                    @if((!empty(collect($entries)->sum('debit')) || !empty(collect($entries)->sum('credit'))  ) )
                        <button type="button" wire:click="draft" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading.remove wire:target="draft">Save as Draft</span>
                            <span wire:loading wire:target="draft">Saving...</span>
                        </button>
                    @endif
                    @if(collect($entries)->sum('debit') == collect($entries)->sum('credit') && (!empty(collect($entries)->sum('debit')) || !empty(collect($entries)->sum('credit'))  ) )
                        <button type="button" wire:click="posted" wire:loading.attr="disabled"
                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading.remove wire:target="posted">Post for Approval</span>
                            <span wire:loading wire:target="posted">Posting...</span>
                        </button>
                    @endif

                </div>
            @endif
        </div>


        <div x-data="{ open: @entangle('search_accounts_modal') }" x-cloak x-show="open"
             class="fixed z-40 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-description="Background overlay, show/hide based on modal state."
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                <div @click.away="open = false;" x-show="open"
                     x-description="Modal panel, show/hide based on modal state."
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="h-1/3 inline-block align-bottom bg-white rounded-lg  text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full  "
                     role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                    <div class="  px-2 pt-2 pb-2">


                        <div class="">
                            <input type="text"
                                   wire:model.debounce.500ms="search_accounts"
                                   wire:keydown.arrow-up="decrementHighlight"
                                   wire:keydown.arrow-down="incrementHighlight"
                                   wire:keydown.enter="selectionAccount"
                                   id="search"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   autocomplete="off">
                        </div>
                        <p class="mt-2 text-sm text-gray-400" id="search-description">You can search accounts by Name,
                            Code and Type.</p>


                    </div>

                    @if(!empty($accounts))
                        <table class="mt-3 min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>

                                <th scope="col"
                                    class="px-2 py-2 text-left text-xs font-medium text-gray-500   tracking-wider">
                                    Name
                                </th>

                                <th scope="col"
                                    class="px-2 py-2 text-left text-xs font-medium text-gray-500   tracking-wider">
                                    Type
                                </th>


                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($accounts as $key=> $a)
                                <tr class="hover:bg-indigo-600 hover:text-white cursor-pointer  {{ $highlightIndex === $key ? 'bg-indigo-600 text-white' : ' text-gray-500' }}"
                                    wire:click="chooseAccount('{{ $a['id'] }}','{{$a['name'] }}')">

                                    <td class="px-2 py-2 whitespace-nowrap text-sm ">
                                        {{ $a['code'] }} - {{ $a['name'] }}
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-sm  ">
                                        {{ $a['type'] }}
                                    </td>

                                </tr>

                            @endforeach
                            </tbody>
                        </table>

                    @else
                        @if(strlen($search_accounts) < 2)
                            <p class="text-sm opacity-25 pt-0 p-3 ">Please enter {{ 2 - strlen($search_accounts) }}
                                or more
                                {{ (2 - strlen($search_accounts)) > 1 ? 'characters' : 'character' }}</p>
                        @else
                            <p class="text-sm opacity-25 pt-0 p-3">{{ empty($accounts) ? 'No Record Found': '' }}</p>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
            document.addEventListener('livewire:load', () => {
                Livewire.on('focusInput', postId => {
                    console.log('asd');
                    setTimeout(() => {
                        document.getElementById('search').focus();
                    }, 300);
                })
            });
            let from_date = new Pikaday({
                field: document.getElementById('posting_date'),
                format: "DD MMM YYYY"
            });


            from_date.setDate(new Date('{{ $posting_date }}'));

            function progress() {
                if (event.target.files[0]) {
                    document.querySelector("#hide").style.display = 'none';
                    @this.
                    set('file_upload', true)
                }
            }
        </script>
    @endsection
@endif
