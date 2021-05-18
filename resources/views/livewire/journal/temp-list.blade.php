<div class="">
    @if($temp_list->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <!-- Heroicon name: solid/exclamation -->
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No Record Found.
                        {{--                        <a href="#" class="font-medium underline text-yellow-700 hover:text-yellow-600">--}}
                        {{--                            Upgrade your account to add more credits.--}}
                        {{--                        </a>--}}
                    </p>
                </div>
            </div>
        </div>
    @else
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

        @foreach($temp_list->groupBy('voucher_no') as $ky => $tl)
            <div class="shadow rounded-md">

                <div class="bg-white  mb-5 rounded-md">
                    @if($loop->first)
                    <div class="py-6 px-4 space-y-6 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Temporary Journal Entries</h3>
                    </div>
                    @endif




                <table class="min-w-full table-fixed  ">
                    <thead class="">
                    <tr class="">
                        <th scope="col"
                            class="w-7 px-2 {{ !$loop->first ? 'rounded-tl-md':'' }}  bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
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
                            class="w-10 {{ !$loop->first ? 'rounded-tr-md':'' }} cursor-pointer bg-gray-100    border-t px-2 py-2     text-right  text-sm font-bold text-gray-500 uppercase tracking-wider">
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white  ">

                    @foreach($tl as $t)
                        <tr class="{{ $loop->first ? 'border-t': '' }} {{ $loop->even ? 'bg-gray-50':'' }} {{ $loop->last ? 'border-b':'' }}">
                            <td class="px-2 py-2  border-r text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                @if($loop->first)
                                    {{ $t->voucher_no }}
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ $t->code }} - {{ $t->name }}
                            </td>

                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ $t->description }}
                            </td>
                            <td class="px-2 py-2 text-right  border-r text-sm text-gray-500">
                                @if($t->debit>0)
                                    {{ number_format($t->debit,2) }}
                                @endif
                            </td>
                            <td class="px-2 text-right py-2 border-r text-sm text-gray-500">
                                @if($t->credit>0)
                                    {{ number_format($t->credit,2) }}
                                @endif
                            </td>
                            <td class="px-2 text-right py-2  text-sm text-gray-500">

                            @php
                                $att = $t->attachments->where('type','0');
                            @endphp
                            <!-- This example requires Tailwind CSS v2.0+ -->
                                @if($att->isNotEmpty())
                                    <div class="relative inline-block text-left" x-data="{open:false}">
                                        <div>
                                            <svg @click="open=true;" class="w-5 h-5 cursor-pointer" fill="currentColor"
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
                                            <div class="py-1" role="none">
                                                @foreach($att as $a)
                                                    @if(empty($a->account_id) || $t->account_id == $a->account_id)
                                                        <a @click="open = false;"
                                                           href="{{ env('AWS_URL').$a->attachment }}"
                                                           target="_blank"
                                                           class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                                           role="menuitem" tabindex="-1"
                                                           id="menu-item-0">{{ $loop->iteration }} Attachment </a>
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    <tr class=" border-b ">
                        <th colspan="4" class="px-2 py-2 text-left text-sm text-gray-500">
                            <div class="flex justify-between  rounded-md">

                                <div class="">

                                    <p class="text-sm font-normal ">
                                        Posted by {{ $tl->first()->posting }} on
                                        <time
                                            datetime="{{ $tl->first()->posting_date }}">{{ date('d M, Y',strtotime($tl->first()->posting_date)) }}</time>
                                    </p>


                                </div>

                                <div>
                                    <a href="{{ url('accounts/journal/edit').'/'.$tl->first()->voucher_no }}"
                                       class="font-medium  py-1 px-2 rounded-md text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100">
                                        Edit
                                    </a>

                                    <a href="#" wire:click="deleteTempEntry('{{ $tl->first()->voucher_no }}')"
                                       class="font-medium  py-1 px-2 rounded-md text-red-600 hover:text-red-800 hover:bg-red-100">
                                        Delete
                                    </a>


                                </div>
                            </div>
                        </th>
                        <th class="px-2 bg-gray-100 text-right  border-r text-sm text-gray-500">
                            {{ number_format($tl->sum('debit'),2) }}
                        </th>
                        <th class="px-2 bg-gray-100 text-right  border-r text-sm text-gray-500">
                            {{ number_format($tl->sum('credit'),2) }}
                        </th>
                        <th></th>
                    </tr>


                    </tbody>
                </table>
                   <div class="p-3 flex  justify-between ">


                       <button type="button" wire:click="approveTempEntry('{{ $tl->first()->voucher_no }}')" class="inline-flex  items-center px-4 py-2 border border-green-700 text-sm font-medium rounded-md text-green-700  hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                           Approve Only
                       </button>

                       <button type="button" wire:click="approveTempEntry('{{ $tl->first()->voucher_no }}','true')" class="inline-flex   items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                           Print and Approve
                       </button>
                   </div>
                </div>

            </div>
        @endforeach
    @endif
</div>




<script>
    window.addEventListener('print-voucher', event => {
        var url = "/accounts/journal/voucher/print/"+event.detail.voucher_no+"/"+event.detail.print;
        newwindow=window.open(url,'voucher-print','height=500,width=800');
        if (window.focus) {newwindow.focus()}
        return false;
    })
</script>

