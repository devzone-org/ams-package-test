@php
    $selected  = collect($this->selectedExpenses);
    $fromDate  = $selected->min('expense_date');
    $toDate    = $selected->max('expense_date');
    $total     = $selected->sum('amount');
    $claimant  = ucwords(auth()->user()->name ?? '');
@endphp
@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="SelectedExpenses" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="selectedExpensesLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="selectedExpensesLabel">Reimbursement Statement</h5>
                        <button type="button" class="close" onclick="$('#SelectedExpenses').modal('hide')"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 460px; overflow-y: auto;">
                        <div class="text-center mb-3">
                            <h4 class="mb-0"><b>Expense Reimbursement Statement</b></h4>
                            <small class="text-muted">Unclaimed admin expenses selected for reimbursement</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div><span class="text-muted">Claimant:</span> <b>{{ $claimant }}</b></div>
                                <div><span class="text-muted">Statement Date:</span>
                                    <b>{{ date('d M, Y') }}</b></div>
                            </div>
                            <div class="col-6 text-right">
                                <div><span class="text-muted">From Date:</span>
                                    <b>{{ $fromDate ? date('d M, Y', strtotime($fromDate)) : '-' }}</b></div>
                                <div><span class="text-muted">To Date:</span>
                                    <b>{{ $toDate ? date('d M, Y', strtotime($toDate)) : '-' }}</b></div>
                                <div><span class="text-muted">No. Of Items:</span>
                                    <b>{{ $selected->count() }}</b></div>
                            </div>
                        </div>

                        <table class="table table-bordered mb-0">
                            <thead>
                            <th class="add-services-table text-muted">#</th>
                            <th class="add-services-table text-muted">Expense On Dated</th>
                            <th class="add-services-table text-left text-muted">Vendor</th>
                            <th class="add-services-table text-left text-muted">A/C Head</th>
                            <th class="add-services-table text-right text-muted">Amount</th>
                            <th class="add-services-table text-left text-muted" style="width: 20px;"></th>
                            </thead>
                            <tbody>
                            @forelse($selected as $ae)
                                <tr wire:key="selected-{{ $ae['id'] }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                                    <td>{{ ucwords($ae['vendor_name'] ?? '') }}</td>
                                    <td>{{ ucwords($ae['account_head'] ?? '') }}</td>
                                    <td class="text-right">{{ number_format($ae['amount'],2) }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link p-0 text-danger"
                                                title="Remove"
                                                wire:click.prevent="removeSelected({{ $ae['id'] }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                            @if($selected->isNotEmpty())
                                <tfoot>
                                <tr>
                                    <td class="text-right" colspan="4"><b>Total Amount Claiming</b></td>
                                    <td class="text-right"><b>{{ number_format($total,2) }}</b></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            @endif
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
                        Reimbursement Statement
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

                <div class="px-6 pt-5">
                    <div class="text-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Expense Reimbursement Statement</h2>
                        <p class="text-sm text-gray-500">Unclaimed admin expenses selected for reimbursement</p>
                    </div>
                    <div class="flex justify-between text-sm text-gray-700 mb-4">
                        <div class="space-y-1">
                            <div><span class="text-gray-500">Claimant:</span>
                                <span class="font-semibold">{{ $claimant }}</span></div>
                            <div><span class="text-gray-500">Statement Date:</span>
                                <span class="font-semibold">{{ date('d M, Y') }}</span></div>
                        </div>
                        <div class="space-y-1 text-right">
                            <div><span class="text-gray-500">From Date:</span>
                                <span class="font-semibold">{{ $fromDate ? date('d M, Y', strtotime($fromDate)) : '-' }}</span></div>
                            <div><span class="text-gray-500">To Date:</span>
                                <span class="font-semibold">{{ $toDate ? date('d M, Y', strtotime($toDate)) : '-' }}</span></div>
                            <div><span class="text-gray-500">No. Of Items:</span>
                                <span class="font-semibold">{{ $selected->count() }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="max-h-96 overflow-y-auto px-6 pb-5">
                    <table class="min-w-full table-fixed border-l border-r">
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
                            <th scope="col" style="width: 20px;"
                                class="px-2 py-2 border-t bg-gray-100 border-r text-left text-sm font-bold text-gray-500  tracking-wider">
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        @forelse($selected as $ae)
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
                                <td class="px-2 py-2 border-r text-center text-sm text-gray-500">
                                    <button type="button" class="text-red-500 hover:text-red-700" title="Remove"
                                            wire:click.prevent="removeSelected({{ $ae['id'] }})">
                                        <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
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
                        @if($selected->isNotEmpty())
                            <tfoot>
                            <tr class="border-b">
                                <td class="px-2 py-2 border-r text-right text-sm font-bold text-gray-700" colspan="4">
                                    Total Amount Claiming
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm font-bold text-gray-700">
                                    {{ number_format($total,2) }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500"></td>
                            </tr>
                            </tfoot>
                        @endif
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
