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
                    <button type="button" wire:click="search"
                            class="bg-white mt-6 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Search
                    </button>

                    <button type="button" wire:click="resetSearch"
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
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nature
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Accounts
                </th>
                <th scope="col"
                    class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Description
                </th>

                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Amount
                </th>

                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Created By
                </th>

                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
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
                                            <button type="button" wire:click="reverseEntry('{{ $e->id }}')"

                                                    class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                                    role="menuitem" tabindex="-1">
                                            <span wire:loading.remove wire:target="reverseEntry">
                                                Reverse Entry
                                                </span>
                                                <span wire:loading wire:target="reverseEntry">
                                                    Please Wait...
                                                </span>
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


</div>



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
