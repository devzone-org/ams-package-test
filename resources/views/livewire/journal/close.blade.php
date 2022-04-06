<div>
    <div class="shadow sm:rounded-md sm:overflow-hidden bg-white overflow-auto">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Day Closing</h3>
            </div>

            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-2">
                    <label for="user_account" class="block text-sm font-medium text-gray-700">ID to be closed</label>
                    <select id="user_account" wire:model="user_account_id"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""></option>
                        @foreach($users as $u)
                            <option value="{{ $u['account_id'] }}">{{ $u['account_name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="text" value="{{ date('d M, Y') }}" readonly
                           class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                </div>

                <div class="col-span-6 sm:col-span-2">
                    <label for="date" class="block text-sm font-medium text-gray-700">Time</label>
                    <input wire:poll.60000ms type="text" value="{{ date('h:i A') }}" readonly
                           class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                </div>
            </div>

            @if(!empty($current_user))
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="sm:flex sm:space-x-5">
                        <div class="flex-shrink-0">
                            @if(!empty($current_user['attachment']))
                                <img class="mx-auto h-20 w-20 rounded-full"
                                     src="{{ env('AWS_URL').$current_user['attachment'] }}"
                                     alt="">
                            @else
                                <span
                                        class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-500">
                                  <span class="text-xl font-medium leading-none text-white">
                                        @php
                                            $words = explode(" ", $current_user['name']);
                                            $acronym = "";

                                            foreach ($words as $w) {
                                              $acronym .= $w[0];
                                            }
                                        @endphp
                                      {{  $acronym }}
                                  </span>
                                </span>
                            @endif
                        </div>
                        <div class="mt-4 text-center sm:mt-0 sm:pt-1 sm:text-left">
                            <p class="text-sm font-medium text-gray-600">Teller: ID to be closed</p>
                            <p class="text-xl font-bold text-gray-900 sm:text-2xl">{{ $current_user['name'] }}</p>
                            <p class="text-sm font-medium text-gray-600">{{ $current_user['email'] }}</p>
                        </div>
                    </div>
                    <div class="sm:flex sm:space-x-5">
                        <div class="flex-shrink-0">
                            @if(!empty(Auth::user()->attachment))
                                <img class="mx-auto h-20 w-20 rounded-full"
                                     src="{{ env('AWS_URL').Auth::user()->attachment }}"
                                     alt="">
                            @else
                                <span
                                        class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-500">
                                  <span class="text-xl font-medium leading-none text-white">
                                        @php
                                            $words = explode(" ", Auth::user()->name);
                                            $acronym = "";

                                            foreach ($words as $w) {
                                            if(!empty($w[0])){
                                              $acronym .= $w[0];
                                            }

                                            }
                                        @endphp
                                      {{  $acronym }}
                                  </span>
                                </span>
                            @endif
                        </div>
                        <div class="mt-4 text-center sm:mt-0 sm:pt-1 sm:text-left">
                            <p class="text-sm font-medium text-gray-600">Closing By</p>
                            <p class="text-xl font-bold text-gray-900 sm:text-2xl">{{ Auth::user()->name }}</p>
                            <p class="text-sm font-medium text-gray-600">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(!empty($current_user))

            <table class="min-w-full border-b border-gray-200 divide-y divide-gray-200">
                <thead class="">
                <tr>
                    <th scope="col"
                        class="px-6 bg-gray-50 py-3 uppercase text-left text-sm font-medium text-center text-gray-800   tracking-wider">
                        Opening Balance
                    </th>
                    @foreach($closing_balance_heads as $h)
                        @if(empty($h))
                            <th scope="col"
                                class="px-6 bg-gray-50 uppercase py-3 text-left text-sm font-medium text-center text-gray-800   tracking-wider">
                                Other
                            </th>
                        @else
                            <th scope="col"
                                class="px-6 bg-gray-50 uppercase py-3 text-left text-sm font-medium  text-center text-gray-800   tracking-wider">
                                {{ ucwords(str_replace('-',' ',$h)) }}
                            </th>
                        @endif

                    @endforeach

                    <th scope="col"
                        class="px-6 py-3 bg-gray-50 uppercase text-left text-sm font-medium text-gray-800 text-center  tracking-wider">
                        Closing Balance (CB)
                    </th>

                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 text-center text-sm  text-gray-500">
                        @if(!empty($opening_balance))
                            <div class="text-sm">
                                PKR {{ number_format($opening_balance) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                as at {{ date('d M, Y',strtotime($opening_balance_date)) }}
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    @foreach($closing_balance_heads as $h)
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500">
                            @if(collect($closing_balance)->where('reference',$h)->first()['balance']>0)
                                {{ number_format(collect($closing_balance)->where('reference',$h)->first()['balance']) }}
                            @else
                            ({{ number_format(abs(collect($closing_balance)->where('reference',$h)->first()['balance'])) }})
                            @endif
                        </td>
                    @endforeach
                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="text-sm">
                            PKR {{number_format(collect($closing_balance)->sum('balance') + $opening_balance)}}
                        </div>
                        <div class="text-sm text-gray-500">
                            as at {{ date('d M, Y') }}
                        </div>
                    </td>

                </tr>


                </tbody>
            </table>

            <div class="bg-white py-6 px-4  sm:p-6 mt-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Denomination Counting (DC)
                </h3>
            </div>




            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3 overflow-auto">
                    <table class="min-w-full border divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="uppercase px-6 py-3 text-left text-sm font-medium text-gray-800   tracking-wider">
                                Currency
                            </th>
                            <th scope="col"
                                class="uppercase px-6 py-3 w-75 text-left text-sm font-medium text-gray-800    ">
                                Count
                            </th>
                            <th scope="col"
                                class="uppercase px-6 py-3 text-left text-sm font-medium text-gray-800   tracking-wider">
                                Total
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($denomination_counting as $key => $dc)
                            <tr>
                                <td class="px-6 py-2 whitespace-nowrap text-sm  text-gray-500">
                                    {{  number_format($dc['currency']) }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    <input type="number" wire:model.lazy="denomination_counting.{{$key}}.count"
                                           class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{  number_format($dc['total']) }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="2"
                                class="px-6 py-3 text-left text-xl font-medium text-gray-800   tracking-wider">Total
                            </th>
                            <th class="px-6 py-3 text-left text-xl font-medium text-gray-800   tracking-wider">
                                {{ number_format(collect($denomination_counting)->sum('total')) }}
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <table class="bg-white min-w-full border table-fixed divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">

                        <tr class="">
                            <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                CB System Cash
                            </td>
                            <td class="  px-6 py-2 font-medium   text-lg text-gray-900">
                                PKR {{number_format(collect($closing_balance)->sum('balance') + $opening_balance,2)}}
                            </td>
                        </tr>

                        <tr class="">
                            <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                DC Physical Cash
                            </td>
                            <td class="  px-6 py-2 font-medium   text-lg text-gray-900">
                                PKR {{number_format(collect($denomination_counting)->sum('total'),2)}}
                            </td>
                        </tr>
                        @if(!empty($difference))
                            <tr class="">
                                <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                    Difference
                                    @if($difference > 0)
                                        <span class="text-red-600">(Surplus) </span>
                                    @else
                                        <span class="text-red-600">(Shortage)</span>
                                    @endif
                                </td>
                                <td class="  px-6 py-2 font-medium    text-lg text-gray-900">
                                    PKR {{number_format(abs(collect($closing_balance)->sum('balance') + $opening_balance - collect($denomination_counting)->sum('total')),2)}}
                                </td>
                            </tr>


                            <tr class="">
                                <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                    Adjustment

                                </td>
                                <td class="  px-6 py-2 font-medium    text-lg text-gray-900">
                                    PKR
                                    @if($difference > 0)
                                        {{ number_format($difference,2) }}
                                    @else
                                        ({{ number_format(abs($difference),2) }})
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr class="">
                                <td class="  text-green-600   px-6 py-2   text-lg font-medium text-gray-900">
                                    Difference

                                </td>
                                <td class=" text-green-600 px-6 py-2 font-medium    text-lg text-gray-900">
                                    0
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr class="">
                            <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                Cash Retained
                            </td>
                            <td class="  px-6 py-2 font-medium   text-lg text-gray-900">
                                <input type="number" wire:model.lazy="retained_cash"
                                       class=" w-36 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </td>
                        </tr>

                        <tr class="">
                            <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                Transfer Amount
                            </td>
                            <td class="  px-6 py-2 font-medium   text-lg text-gray-900">
                                {{ number_format(collect($denomination_counting)->sum('total') - $retained_cash ,2) }}
                            </td>
                        </tr>


                        <tr class="">
                            <td class="     px-6 py-2   text-lg font-medium text-gray-900">
                                Transfer To
                            </td>
                            <td class="  px-6 py-2 font-medium   text-lg text-gray-900">
                                <select wire:model="transfer_id"
                                        class=" w-36 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option></option>
                                    @foreach($transfers as $t)
                                        @if($t['id']==$user_account_id)
                                            @continue
                                        @endif
                                        <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr class="">
                            <td colspan="2" class="px-6 py-2 text-center bg-gray-50  text-lg font-sm text-gray-900">
                                Transfer to "{{ collect($transfers)->firstWhere('id',$transfer_id)['name'] ?? '' }}"
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    @if(!empty($transfer_id))
                        <button type="button" wire:click="$set('confirm_popup','true')" wire:loading.attr="disabled"
                                class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Proceed Closing
                        </button>
                    @endif


                    @if ($errors->any())
                        <div class="rounded-md mt-5 bg-red-50 p-4">
                            <div class="flex">

                                <div class=" ">

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
                </div>
            </div>
        @endif

    </div>


    <div x-data="{ open: @entangle('confirm_popup') }" x-cloak x-show="open"
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
            <div @click.away="open = false;" x-show="open" x-description="Modal panel, show/hide based on modal state."
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="h-1/3 inline-block align-bottom bg-white rounded-lg  text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full  "
                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">


                <div class="p-4">
                    Are you sure you want to transfer
                    PKR {{ number_format(collect($denomination_counting)->sum('total') - $retained_cash ,2) }}
                    to {{ collect($transfers)->firstWhere('id',$transfer_id)['name'] ?? '' }}
                    from {{ $current_user['name'] ?? '' }}
                    <br>
                    Press <strong>Y</strong> to confirm
                </div>
            </div>
        </div>
    </div>

</div>


<script>

    document.addEventListener("keydown", event => {
        if (event.keyCode == 89) {
            event.preventDefault();
            event.stopPropagation();
            window.livewire.emit('proceedClosing');
        }

    });
</script>
