<div class="shadow sm:rounded-md sm:overflow-hidden">
    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add Journal Entry</h3>

        </div>

        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="posting_date" class="block text-sm font-medium text-gray-700">Posting Date</label>
                <input type="date" wire:model="posting_date" id="posting_date" autocomplete="off"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="voucher_no" class="block text-sm font-medium text-gray-700">Temp Voucher #</label>
                <input type="text" wire:model="voucher_no" readonly id="voucher_no" autocomplete="off"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

        </div>


    </div>
    <div>

        <table class="min-w-full table-fixed divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col"
                    class="w-7 px-2   border-r py-3 text-left text-xs font-medium text-gray-500  tracking-wider">
                    #
                </th>
                <th scope="col"
                    class="w-1/5 px-2   border-r py-3 text-left text-xs font-medium text-gray-500  tracking-wider">
                    Account
                </th>
                <th scope="col"
                    class="px-2 py-3   border-r text-left text-xs font-medium text-gray-500  tracking-wider">
                    Description
                </th>
                <th scope="col"
                    class="w-32 px-2 py-3   border-r text-right text-xs font-medium text-gray-500  tracking-wider">
                    Debit
                </th>
                <th scope="col"
                    class="w-32 px-2 py-3   border-r text-right text-xs font-medium text-gray-500  tracking-wider">
                    Credit
                </th>
                <th scope="col" wire:click="addEntry()"
                    class="w-10 cursor-pointer px-2 py-3   border-r text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                    <td class="px-2    border-r text-sm font-medium text-gray-900">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-2     border-r text-sm text-gray-500">
                        <input wire:click="searchAccounts('{{ $key }}')" type="text" readonly
                               wire:model.lazy="entries.{{$key}}.account_name"
                               class="p-0 focus:ring-0 block w-full  text-sm border-0  " autocomplete="off">
                    </td>
                    <td class="px-2      border-r  text-sm text-gray-500">
                    <textarea wire:ignore.self cols="30" rows="2" wire:model.lazy="entries.{{$key}}.description"
                              class="p-0  focus:ring-0 block w-full  text-sm border-0  "></textarea>
                    </td>
                    <td class="px-2    border-r text-sm text-gray-500">
                        <input type="number" step="0.01" wire:model.lazy="entries.{{$key}}.debit"
                               class=" p-0 focus:ring-0 block w-full text-right text-sm border-0  " autocomplete="off">
                    </td>
                    <td class="px-2   border-r text-sm text-gray-500">
                        <input type="number" step="0.01" wire:model.lazy="entries.{{$key}}.credit"
                               class="p-0 focus:ring-0 block w-full text-right text-sm border-0  "
                               autocomplete="off">
                    </td>
                    <td wire:click="removeEntry('{{ $key }}')"
                        class="  w-10 cursor-pointer px-2 py-3   border-r text-right text-xs font-medium text-red-700  tracking-wider  ">
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
                <th class="px-2    border-r text-right text-sm text-gray-900">
                    {{ number_format(collect($entries)->sum('debit'),2) }}
                </th>
                <th class="px-2   border-r text-right text-sm text-gray-900">
                    {{ number_format(collect($entries)->sum('credit'),2) }}
                </th>
                <td class="  w-10 cursor-pointer px-2 py-3 py-3   border-r text-right text-xs font-medium text-red-700  tracking-wider  ">
                    &nbsp;
                </td>
            </tr>
            </tbody>
        </table>


    </div>
    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <button type="submit"
                class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save
        </button>
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
            <div @click.away="open = false;" x-show="open" x-description="Modal panel, show/hide based on modal state."
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full  "
                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="sm:flex sm:items-start">
                    <input type="text" wire:model.debounce.500ms="search_accounts"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                @if(!empty($accounts))
                    <table class="mt-3 min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-2 py-2 text-left text-xs font-medium text-gray-500   tracking-wider">
                                Code
                            </th>
                            <th scope="col"
                                class="px-2 py-2 text-left text-xs font-medium text-gray-500   tracking-wider">
                                Name
                            </th>


                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($accounts as $a)
                        <tr class="hover:bg-gray-50 cursor-pointer" wire:click="chooseAccount('{{ $a['id'] }}','{{ $a['name'] }}')">

                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $a['code'] }}
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $a['name'] }}
                            </td>

                        </tr>

                        @endforeach
                        </tbody>
                    </table>


                @endif
            </div>
        </div>
    </div>

</div>
