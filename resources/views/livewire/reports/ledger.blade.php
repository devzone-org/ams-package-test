<div>
    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">{{ $account_name_s ?? 'General Ledger' }}</h3>
                <p class="text-md  font-sm text-gray-500 text-center" >{{ env('APP_NAME') }}</p>
                @if(!empty($from_d) && !empty($to_d))
                <p class="text-md  font-sm text-gray-500 text-center" >Statement Period {{ date('d M, Y',strtotime($from_d)) }} to {{ date('d M, Y',strtotime($to_d)) }} </p>
@endif
            </div>

            <div class="grid grid-cols-8 gap-6">
                <div class="col-span-6 sm:col-span-2">
                    <label for="account" class="block text-sm font-medium text-gray-700">Account Name</label>
                    <input type="text" readonly wire:click="searchableOpenModal('account_id','account_name','accounts')" wire:model="account_name" id="account"
                           autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" wire:model="from_date" id="from_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" wire:model="to_date" id="to_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <div class="mt-6 flex-shrink-0 flex ">
                        <button type="button" wire:click="search" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                        <button type="button" wire:click="resetSearch" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reset
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div>

            <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100">
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
                    <th scope="col"
                        class="w-7 cursor-pointer px-2 py-2   border-r text-right text-sm font-bold text-gray-500 uppercase tracking-wider">

                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr class>
                    <th colspan="3" class="px-2 py-2  text-right   text-sm   text-gray-500">
                        Opening Balance
                    </th>
                    <th></th>
                    <th></th>
                    <th class="px-2   py-2    text-sm  text-right  text-gray-500">{{ number_format($opening_balance,2) }}</th>
                    <th class="px-2    py-2    text-xs   text-gray-500"></th>
                </tr>
                @php
                    $balance = $opening_balance;
                @endphp
                @foreach($ledger as $key => $en)
                    @php
                        if ($this->account_details['nature'] == 'd') {
                            if($this->account_details['is_contra']=='f'){
                                $balance = $balance+ $en['debit'] - $en['credit'];
                            } else {
                                $balance = $balance- $en['debit'] + $en['credit'];
                            }

                        } else {
                            if($this->account_details['is_contra']=='f') {
                                $balance = $balance- $en['debit'] + $en['credit'];
                            } else {
                                $balance = $balance+ $en['debit'] - $en['credit'];
                            }
                        }
                    @endphp
                    <tr class="{{ $loop->odd ? 'bg-gray-50' :'' }}">
                        <td class="px-2   py-2   border-r text-sm   text-gray-500">
                            <a class="font-medium text-indigo-600 hover:text-indigo-500" href="javascript:void(0);" onclick="window.open('{{ url('accounts/journal/voucher/print').'/'.$en['voucher_no'] }}','voucher-print-{{$en['voucher_no']}}','height=500,width=800');">{{ $en['voucher_no'] }}</a>
                        </td>
                        <td class="px-2   py-2    border-r text-sm text-gray-500">
                            {{ date('d M, Y',strtotime($en['posting_date'])) }}
                        </td>
                        <td class="px-2    py-2    border-r  text-sm text-gray-500">
                            {{ $en['description'] }}
                        </td>
                        <td class="px-2  py-2  text-right  border-r text-sm text-gray-500">
                            {{ number_format($en['debit'],2) }}
                        </td>
                        <td class="px-2   py-2 text-right border-r text-sm text-gray-500">
                            {{ number_format($en['credit'],2) }}
                        </td>
                        <td
                            class="  w-10 px-2 py-2   text-right border-r text-sm text-gray-500">
                            {{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($balance) }}

                        </td>
                        <td
                            class=" w-10 px-2   py-2 text-right border-r text-sm text-gray-500 ">
                            @php

                                $att = \Devzone\Ams\Models\LedgerAttachment::where('voucher_no',$en['voucher_no'])->where('type','1')->get();
                            @endphp

                            @if($att->isNotEmpty())
                                <div class="relative inline-block text-left" x-data="{open:false}">
                                    <div class="pt-1 pl-0">
                                        <svg @click="open=true;" class="w-4 h-4 cursor-pointer" fill="currentColor"
                                             viewBox="0 0 20 20"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>

                                    <div @click.away="open=false;" x-show="open"
                                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 focus:outline-none"
                                         role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                         tabindex="-1">
                                        <div class="" role="none">
                                            @foreach($att as $a)
                                                @if(empty($a->account_id) || $en['account_id'] == $a->account_id)
                                                    <a @click="open = false;" href="{{ env('AWS_URL').$a->attachment }}"
                                                       target="_blank"
                                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                                       role="menuitem" tabindex="-1" id="menu-item-0">{{ $loop->iteration }} Attachment </a>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr class>
                    <th colspan="3" class="px-2  py-2   text-right   text-sm   text-gray-500">
                        Closing Balance
                    </th>
                    <th></th>
                    <th></th>
                    <th class="px-2    py-2   text-sm  text-right  text-gray-500">{{ \Devzone\Ams\Helper\GeneralJournal::numberFormat($balance) }}</th>
                    <th class="px-2   py-2   border-r text-sm   text-gray-500"></th>
                </tr>
                <tr class>
                    <th colspan="3" class="px-2   py-2  text-right   text-sm   text-gray-500">
                        Total Debit & Credit
                    </th>
                    <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500">{{ number_format(collect($ledger)->sum('debit'),2) }}</th>
                    <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500">{{ number_format(collect($ledger)->sum('credit'),2) }}</th>
                    <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500"></th>
                    <th class="px-2   py-2   border-r text-sm   text-gray-500"></th>
                </tr>

                <tr class>
                    <th colspan="3" class="px-2   py-2  text-right   text-sm   text-gray-500">
                        Total Number of Transactions
                    </th>
                    <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500">{{ number_format(collect($ledger)->count(),2) }}</th>
                    <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500"></th>
                    <th class="px-2   py-2   border-r text-sm  text-right  text-gray-500"></th>
                    <th class="px-2   py-2   border-r text-sm   text-gray-500"></th>
                </tr>

                </tbody>
            </table>
        </div>
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

    window.addEventListener('title', event => {
        document.title = "GL: "+event.detail.name;
    })
</script>
