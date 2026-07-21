@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="SelectedExpenses" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="selectedExpensesLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="selectedExpensesLabel">Selected Expenses</h5>
                        <button type="button" class="close" onclick="$('#SelectedExpenses').modal('hide')"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-bordered border-0 mb-0">
                            <thead class="">
                            <th class="add-services-table text-muted">#</th>
                            <th class="add-services-table text-muted">Expense On Dated</th>
                            <th class="add-services-table text-left text-muted">Vendor</th>
                            <th class="add-services-table text-left text-muted">A/C Head</th>
                            <th class="add-services-table text-right text-muted">Amount</th>
                            <th class="add-services-table text-left text-muted" style="width: 20px;"></th>
                            </thead>
                            <tbody>
                            @forelse($this->selectedExpenses as $ae)
                                <tr wire:key="selected-{{ $ae['id'] }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                                    <td>{{ ucwords($ae['vendor_name'] ?? '') }}</td>
                                    <td>{{ ucwords($ae['account_head'] ?? '') }}</td>
                                    <td class="text-right">{{ number_format($ae['amount'],2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-link p-0 text-danger"
                                                wire:click.prevent="removeSelected({{ $ae['id'] }})">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                @if($loop->last)
                                    <tr>
                                        <td class="px-2 py-2 text-right" colspan="4">
                                            <b>Amount Claiming</b>
                                        </td>
                                        <td class="px-2 py-2 text-right">
                                            <b>{{ number_format(collect($this->selectedExpenses)->sum('amount'),2) }}</b>
                                        </td>
                                        <td class="px-2 py-2"></td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-danger rounded-md overflow-hidden">
                                        <div class="alert alert-danger mb-0 py-4 text-center">
                                            <b>No Records Found.</b>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-light"
                                onclick="$('#SelectedExpenses').modal('hide')">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            window.addEventListener('close-selected-modal', function () {
                $('#SelectedExpenses').modal('hide');
            })
        </script>
    @endpush
@else
    <div x-cloak x-data="{ open: false }" x-show="open"
         @open-selected-modal.window="open = true"
         @close-selected-modal.window="open = false"
         class="fixed z-40 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
                 role="dialog" aria-modal="true" aria-labelledby="selected-expenses-headline">

                <div class="flex items-center px-4 py-3 border-b border-gray-200">
                    <h3 class="flex-1 text-base font-medium text-gray-900" id="selected-expenses-headline">
                        Selected Expenses
                    </h3>
                    <button type="button" @click="open = false"
                            class="-mr-1 p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    <table class="min-w-full table-fixed">
                        <thead class="">
                        <tr class="">
                            <th scope="col"
                                class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                                #
                            </th>
                            <th scope="col" style="width: 110px;"
                                class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500  tracking-wider">
                                Expense On Dated
                            </th>
                            <th scope="col"
                                class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500  tracking-wider">
                                Vendor
                            </th>
                            <th scope="col"
                                class="px-2 py-2 border-t bg-gray-100 border-r text-left text-sm font-bold text-gray-500  tracking-wider">
                                A/C Head
                            </th>
                            <th scope="col" style="width: 110px;"
                                class="px-2 py-2 border-t bg-gray-100 border-r text-right text-sm font-bold text-gray-500  tracking-wider">
                                Amount
                            </th>
                            <th scope="col" style="width: 90px;"
                                class="px-2 py-2 border-t bg-gray-100 border-r text-left text-sm font-bold text-gray-500  tracking-wider">
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        @forelse($this->selectedExpenses as $ae)
                            <tr wire:key="selected-{{ $ae['id'] }}"
                                class="{{ $loop->first ? 'border-t': '' }} border-b">
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ date('d M, Y',strtotime($ae['expense_date'])) }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ ucwords($ae['vendor_name'] ?? '') }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ ucwords($ae['account_head'] ?? '') }}
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    {{ number_format($ae['amount'],2) }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    <button type="button" class="text-red-500 font-medium"
                                            wire:click.prevent="removeSelected({{ $ae['id'] }})">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            @if($loop->last)
                                <tr class="border-b">
                                    <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="4">
                                        <b>Amount Claiming</b>
                                    </td>
                                    <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                        <b>{{ number_format(collect($this->selectedExpenses)->sum('amount'),2) }}</b>
                                    </td>
                                    <td class="px-2 py-2 border-r text-sm text-gray-500"></td>
                                </tr>
                            @endif
                        @empty
                            <tr class="border-t border-b">
                                <td colspan="6" class="text-sm text-red-500 rounded-md overflow-hidden">
                                    <div class="flex items-center justify-center py-5">
                                        <b>No Records Found.</b>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="button" @click="open = false"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
