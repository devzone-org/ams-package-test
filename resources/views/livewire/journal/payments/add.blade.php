@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="">
                            <a href="{{ url('accounts/accountant/payments') }}"
                               class="">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-dark"
                                     style="height: 40px; width: 40px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/>
                                </svg>

                            </a>
                            <span class="ml-2"><b>Go Back</b></span>
                        </div>
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
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title"><b>Payments & Receiving Form</b></h4>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="col-12 px-0">
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">
                                                ×
                                            </button>
                                            @foreach ($errors->all() as $error)
                                                <li>{!! $error !!}</li>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($success))
                                    <div class="col-12 px-0">
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">
                                                ×
                                            </button>
                                            {{ $success }}
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group">
                                            <label for="nature" class="font-weight-normal">Nature</label>
                                            <select id="nature" wire:model="nature"
                                                    class="form-control @error('nature')  is-invalid @enderror">
                                                <option value=""></option>
                                                <option value="pay">Amount Pay to Anyone</option>
                                                <option value="receive">Receive Amount from Anyone</option>
                                                {{--                                                @can('2.transfer-entry')--}}
                                                {{--                                                    <option value="transfer_entry">Transfer Entry</option>--}}
                                                {{--                                                @endcan--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4">
                                        <div class="form-group @if(!$nature) d-none @endif">
                                            <label for="date" class="font-weight-normal">Transaction Date</label>
                                            <input type="text" wire:model.lazy="posting_date" id="posting_date"
                                                   class="form-control @error('posting_date')  is-invalid @enderror">
                                        </div>
                                    </div>
                                    @if(!empty($nature))

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="first_account" class="font-weight-normal">
                                                    @if($nature=='pay')
                                                        Payment on account of
                                                    @elseif($nature=='receive')
                                                        Received on account of
                                                    @endif
                                                </label>

                                                <input type="text"
                                                       wire:click="searchableOpenModal('first_account_id','first_account_name','accounts')"
                                                       wire:model="first_account_name"
                                                       class="form-control @error('first_account_name')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="amount" class="font-weight-normal">
                                                    @if($nature=='pay')
                                                        Amount Paid
                                                    @elseif($nature=='receive')
                                                        Amount Received
                                                    @endif
                                                </label>
                                                <input type="number" wire:model.defer="amount"
                                                       class="form-control @error('amount')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-12 pt-3">
                                            <div class="form-group">
                                                <label class="font-weight-normal">
                                                    Description
                                                </label>
                                                <textarea wire:model.defer="description" cols="30" rows="5"
                                                          class="form-control @error('description')  is-invalid @enderror"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="first_account" class="font-weight-normal">
                                                    @if($nature=='pay')
                                                        Paid From
                                                    @elseif($nature=='receive')
                                                        Received In
                                                    @endif
                                                </label>
                                                @if(auth()->user()->can('2.payments.any'))
                                                    <input type="text"
                                                           wire:click="searchableOpenModal('second_account_id','second_account_name','accounts')"
                                                           wire:model="second_account_name"
                                                           class="form-control @error('second_account_name')  is-invalid @enderror">
                                                @else
                                                    <input type="text"
                                                           wire:model="second_account_name"
                                                           class="form-control @error('second_account_name')  is-invalid @enderror">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="mode" class="font-weight-normal">Mode of Payment</label>
                                                <select id="mode" wire:model="mode"
                                                        class="form-control @error('mode')  is-invalid @enderror">
                                                    <option value=""></option>
                                                    <option value="cash">Cash</option>
                                                    <option value="cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                @if($mode=='cheque')
                                                    <label for="first_account" class="font-weight-normal">
                                                        Instrument #
                                                    </label>
                                                    <input type="text" wire:model.defer="instrument_no"
                                                           class="form-control @error('instrument_no')  is-invalid @enderror">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="attachment"
                                                       class="font-weight-normal">Attachment</label>
                                                <input
                                                        class="form-control p-0 py-1 px-1 attachment"
                                                        type="file" wire:model="attachment">
                                            </div>
                                        </div>
                                        <div class="col-12 pt-3 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary" wire:click="save"
                                                        wire:loading.attr="disabled">
                                                    <span wire:loading wire:target="save">Saving ...</span>
                                                    <span wire:loading.remove wire:target="save">Save</span>
                                                </button>
                                            </div>
                                        </div>


                                        {{--                                        @if($nature == 'transfer_entry')--}}

                                        {{--                                            @if(auth()->user()->can('2.transfer-entry'))--}}
                                        {{--                                                <div class="col-xs-6 col-sm-4">--}}
                                        {{--                                                    <div class="form-group">--}}
                                        {{--                                                        <label for="transfer_from" class="font-weight-normal">Transfer--}}
                                        {{--                                                            From</label>--}}
                                        {{--                                                        <select id="transfer_from" wire:model="from_account_id"--}}
                                        {{--                                                                class="form-control @error('from_account_id')  is-invalid @enderror">--}}
                                        {{--                                                            <option value=""></option>--}}
                                        {{--                                                            @foreach($payment_accounts as $key => $account)--}}
                                        {{--                                                                <optgroup--}}
                                        {{--                                                                        label="{{\App\Models\ChartOfAccount::find($key)->name}}">--}}
                                        {{--                                                                    @foreach($account as $acc)--}}
                                        {{--                                                                        @if(!empty($to_account_id) && $to_account_id == $acc['id'])--}}
                                        {{--                                                                            @php--}}
                                        {{--                                                                                continue;--}}
                                        {{--                                                                            @endphp--}}
                                        {{--                                                                        @else--}}
                                        {{--                                                                            <option value="{{$acc['id']}}">{{$acc['name']}}</option>--}}
                                        {{--                                                                        @endif--}}
                                        {{--                                                                    @endforeach--}}
                                        {{--                                                                </optgroup>--}}
                                        {{--                                                            @endforeach--}}
                                        {{--                                                        </select>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <div class="col-xs-6 col-sm-4">--}}
                                        {{--                                                    <div class="form-group">--}}
                                        {{--                                                        <label for="transfer_to" class="font-weight-normal">Transfer--}}
                                        {{--                                                            To</label>--}}
                                        {{--                                                        <select id="transfer_to" wire:model="to_account_id"--}}
                                        {{--                                                                class="form-control @error('to_account_id')  is-invalid @enderror">--}}
                                        {{--                                                            <option value=""></option>--}}
                                        {{--                                                            @foreach($payment_accounts as $key => $account)--}}
                                        {{--                                                                <optgroup--}}
                                        {{--                                                                        label="{{\App\Models\ChartOfAccount::find($key)->name}}">--}}
                                        {{--                                                                    @foreach($account as $acc)--}}
                                        {{--                                                                        @if(!empty($from_account_id) && $from_account_id == $acc['id'])--}}
                                        {{--                                                                            @php--}}
                                        {{--                                                                                continue;--}}
                                        {{--                                                                            @endphp--}}
                                        {{--                                                                        @else--}}
                                        {{--                                                                            <option value="{{$acc['id']}}">{{$acc['name']}}</option>--}}
                                        {{--                                                                        @endif--}}
                                        {{--                                                                    @endforeach--}}
                                        {{--                                                                </optgroup>--}}
                                        {{--                                                            @endforeach--}}
                                        {{--                                                        </select>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <div class="col-xs-6 col-sm-4">--}}
                                        {{--                                                    <div class="form-group">--}}
                                        {{--                                                        <label for="amount" class="font-weight-normal">--}}
                                        {{--                                                            Transfer Amount--}}
                                        {{--                                                        </label>--}}
                                        {{--                                                        <input type="number" wire:model="amount"--}}
                                        {{--                                                               class="form-control @error('amount')  is-invalid @enderror">--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <div class="col-12 pt-3">--}}
                                        {{--                                                    <div class="form-group">--}}
                                        {{--                                                        <label class="font-weight-normal">--}}
                                        {{--                                                            Description--}}
                                        {{--                                                        </label>--}}
                                        {{--                                                        <textarea wire:model="description" cols="30" rows="5"--}}
                                        {{--                                                                  class="form-control @error('description')  is-invalid @enderror"></textarea>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}

                                        {{--                                                <div class="col-12 pt-3 d-flex justify-content-end">--}}
                                        {{--                                                    <div class="form-group">--}}
                                        {{--                                                        <button type="button" class="btn btn-primary"--}}
                                        {{--                                                                data-toggle="modal"--}}
                                        {{--                                                                data-target="#saveConfirm"--}}
                                        {{--                                                                data-style="expand-right"--}}
                                        {{--                                                                @if(empty($amount) || empty($from_account_id) || empty($to_account_id) || empty($description)) disabled @endif>--}}
                                        {{--                                                            Save--}}
                                        {{--                                                        </button>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}

                                        {{--                                            @endif--}}

                                        {{--                                        @else--}}

                                        {{--                                            --}}
                                        {{--                                        @endif--}}
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include("ams::include.searchable")
        </div>

        <div class="modal fade" id="saveConfirm" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form wire:submit.prevent="transferEntry">
                    <div class="modal-content">
                        <div class="card-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">Attention!!!</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                Are you sure you want to transfer <strong>{{ env('CURRENCY','PKR') }} {{$amount}}</strong> from
                                <strong>{{!empty($from_account_id) ? \Devzone\Ams\Models\ChartOfAccount::find($from_account_id)->name : ''}}</strong>
                                to
                                <strong>{{!empty($to_account_id) ? \Devzone\Ams\Models\ChartOfAccount::find($to_account_id)->name : ''}}</strong>
                                ?
                            </p>
                        </div>
                        <div class="modal-footer text-right">
                            <button type="submit" class="btn btn-primary" data-toggle="modal"
                                    data-target="#saveConfirm">
                                Yes
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                No
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
            let from_date = new Pikaday({
                field: document.getElementById('posting_date'),
                format: "DD MMM YYYY"
            });


        </script>
    @endpush
@else
    <div>
        <div class="pb-5 border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <a href="{{ url('accounts/accountant/payments') }}"
                   class="p-3 bg-gray-200 border-2 rounded-md  border-gray-400 cursor-pointer hover:bg-gray-300 ">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                </a>
                <span class="ml-4">Go Back</span>
            </h3>
        </div>

        <div class="shadow sm:rounded-md sm:overflow-hidden bg-white">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payments & Receiving Form </h3>

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
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-2">
                        <label for="nature" class="block text-sm font-medium text-gray-700">Nature</label>
                        <select id="nature" wire:model="nature"
                                class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            <option value="pay">Amount Pay to Anyone</option>
                            <option value="receive">Receive Amount from Anyone</option>
                        </select>
                    </div>
                    <div class="col-span-4"></div>
                    <div class="col-span-6 sm:col-span-2 {{ empty($nature) ? 'hidden' : '' }}">
                        <label for="date" class="block text-sm font-medium text-gray-700">Transaction Date</label>
                        <input type="text" wire:model.lazy="posting_date" readonly id="posting_date"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    @if(!empty($nature))

                        <div class="col-span-6 sm:col-span-2">
                            <label for="first_account" class="block text-sm font-medium text-gray-700">
                                @if($nature=='pay')
                                    Payment on account of
                                @elseif($nature=='receive')
                                    Received on account of
                                @endif
                            </label>

                            <input type="text" readonly
                                   wire:click="searchableOpenModal('first_account_id','first_account_name','accounts')"
                                   wire:model="first_account_name"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>


                        <div class="col-span-6 sm:col-span-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                @if($nature=='pay')
                                    Amount Paid
                                @elseif($nature=='receive')
                                    Amount Received
                                @endif
                            </label>
                            <input type="number" wire:model.defer="amount"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>


                        <div class="col-span-6 ">
                            <label class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea wire:model.defer="description" cols="30" rows="5"
                                      class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>

                        </div>


                        <div class="col-span-6 sm:col-span-2">
                            <label for="first_account" class="block text-sm font-medium text-gray-700">
                                @if($nature=='pay')
                                    Paid From
                                @elseif($nature=='receive')
                                    Received In
                                @endif
                            </label>
                            @if(auth()->user()->can('2.payments.any'))
                                <input type="text" readonly
                                       wire:click="searchableOpenModal('second_account_id','second_account_name','accounts')"
                                       wire:model="second_account_name"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @else
                                <input type="text" readonly

                                       wire:model="second_account_name"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label for="mode" class="block text-sm font-medium text-gray-700">Mode of Payment</label>
                            <select id="mode" wire:model="mode"
                                    class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            @if($mode=='cheque')
                                <label for="first_account" class="block text-sm font-medium text-gray-700">
                                    Instrument #
                                </label>
                                <input type="text" wire:model.defer="instrument_no"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @endif
                        </div>


                        <div class="col-span-6  ">
                            <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment</label>
                            <input
                                    class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    type="file" wire:model="attachment">
                            @if(!empty($preview_attachment))
                                <div class="mt-2">
                                    <a href="{{ $preview_attachment }}" target="_blank" class="text-indigo-600 hover:text-blue-900 underline">
                                        View Current Attachment
                                    </a>
                                </div>
                            @endif
                        </div>

                    @endif
                </div>


            </div>
            @if(!empty($nature))
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="button" wire:click="save" wire:loading.attr="disabled"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span wire:loading wire:target="save">{{$is_edit ? 'Updating' : 'Saving...'}}</span>
                        <span wire:loading.remove wire:target="save">{{$is_edit ? 'Update' : 'Save'}}</span>
                    </button>
                </div>
            @endif


        </div>

        @include("ams::include.searchable")
    </div>
    <script>

        document.addEventListener('livewire:load', () => {
            Livewire.on('focusInput', postId => {
                setTimeout(() => {
                    document.getElementById('searchable_query').focus();
                }, 300);
            });


        });

    </script>

    @section('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
            let from_date = new Pikaday({
                field: document.getElementById('posting_date'),
                format: "DD MMM YYYY"
            });


        </script>
    @endsection
@endif



