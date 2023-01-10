<div>
    <div class="shadow rounded-md">

        <div class="bg-white  mb-5 rounded-md">
            <div class="py-6 px-4 sm:p-6 flex justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Unclaimed Petty Expenses</h3>
                <a href="/accounts/petty-expenses">
                <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Add Petty Expenses
                </button>
                </a>
            </div>
            <table class="min-w-full table-fixed  ">
                <thead class="">
                <tr class="">
                    <th scope="col"
                        class="w-7 px-2 rounded-tl-md bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                        #
                    </th>
                    <th scope="col"
                        class="w-28 px-2 py-2   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Voucher #
                    </th>
                    <th scope="col"
                        class="w-1/5 px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                        Account Name
                    </th>
                    <th scope="col"
                        class="px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
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
                    <th scope="col"
                        class="w-10 rounded-tr-md cursor-pointer bg-gray-100    border-t px-2 py-2     text-right  text-sm font-bold text-gray-500 uppercase tracking-wider">
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white  ">

                {{--                @foreach($tl->where('debit','>',0) as $t)--}}
                {{--                    <tr class="{{ $loop->first ? 'border-t': '' }} {{ $loop->even ? 'bg-gray-50':'' }}   border-b">--}}
                {{--                        <td class="px-2 py-2  border-r text-sm text-gray-500">--}}
                {{--                            {{ $loop->iteration }}--}}
                {{--                        </td>--}}
                {{--                        <td class="px-2 py-2 border-r text-sm text-gray-500">--}}
                {{--                            @if($loop->first)--}}
                {{--                                {{ $t->voucher_no }}--}}
                {{--                            @endif--}}
                {{--                        </td>--}}
                {{--                        <td class="px-2 py-2 border-r text-sm text-gray-500">--}}
                {{--                            {{ $t->name }}--}}
                {{--                        </td>--}}

                {{--                        <td class="px-2 py-2 border-r text-sm text-gray-500">--}}
                {{--                            {{ $t->description }}--}}
                {{--                        </td>--}}
                {{--                        <td class="px-2 py-2 text-right  border-r text-sm text-gray-500">--}}
                {{--                            @if($t->debit>0)--}}
                {{--                                {{ number_format($t->debit,2) }}--}}
                {{--                            @endif--}}
                {{--                        </td>--}}
                {{--                        <td class="px-2 text-right py-2 border-r text-sm text-gray-500">--}}
                {{--                            @if($t->credit>0)--}}
                {{--                                {{ number_format($t->credit,2) }}--}}
                {{--                            @endif--}}
                {{--                        </td>--}}
                {{--                        <td class="px-2 text-right py-2  text-sm text-gray-500">--}}

                {{--                        @php--}}
                {{--                            $att = $t->attachments->where('type','0');--}}
                {{--                        @endphp--}}
                {{--                        <!-- This example requires Tailwind CSS v2.0+ -->--}}
                {{--                            @if($att->isNotEmpty())--}}
                {{--                                <div class="relative inline-block text-left" x-data="{open:false}">--}}
                {{--                                    <div>--}}
                {{--                                        <svg @click="open=true;" class="w-5 h-5 cursor-pointer"--}}
                {{--                                             fill="currentColor"--}}
                {{--                                             viewBox="0 0 20 20"--}}
                {{--                                             xmlns="http://www.w3.org/2000/svg">--}}
                {{--                                            <path fill-rule="evenodd"--}}
                {{--                                                  d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"--}}
                {{--                                                  clip-rule="evenodd"></path>--}}
                {{--                                        </svg>--}}
                {{--                                    </div>--}}

                {{--                                    <div @click.away="open=false;" x-show="open"--}}
                {{--                                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 focus:outline-none"--}}
                {{--                                         role="menu" aria-orientation="vertical" aria-labelledby="menu-button"--}}
                {{--                                         tabindex="-1">--}}
                {{--                                        <div class="py-1" role="none">--}}
                {{--                                            @foreach($att as $a)--}}
                {{--                                                @if(empty($a->account_id) || $t->account_id == $a->account_id)--}}
                {{--                                                    <a @click="open = false;"--}}
                {{--                                                       href="{{ env('AWS_URL').$a->attachment }}"--}}
                {{--                                                       target="_blank"--}}
                {{--                                                       class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"--}}
                {{--                                                       role="menuitem" tabindex="-1"--}}
                {{--                                                       id="menu-item-0">{{ $loop->iteration }} Attachment </a>--}}
                {{--                                                @endif--}}
                {{--                                            @endforeach--}}

                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            @endif--}}
                {{--                        </td>--}}

                {{--                    </tr>--}}
                {{--                @endforeach--}}


                </tbody>
            </table>
            {{--            <div class="p-3 flex  justify-between ">--}}


            {{--                <button type="button" wire:click="approveTempEntry('{{ $tl->first()->voucher_no }}')"--}}
            {{--                        wire:loading.attr="disabled"--}}
            {{--                        class="inline-flex  items-center px-4 py-2 border border-green-700 text-sm font-medium rounded-md text-green-700  hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">--}}
            {{--                    Approve Only--}}
            {{--                </button>--}}

            {{--                <button type="button" wire:click="approveTempEntry('{{ $tl->first()->voucher_no }}','true')"--}}
            {{--                        wire:loading.attr="disabled"--}}
            {{--                        class="inline-flex   items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">--}}
            {{--                    Print and Approve--}}
            {{--                </button>--}}
            {{--            </div>--}}
        </div>

    </div>
</div>