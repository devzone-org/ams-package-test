@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    @if(!empty($success))
                        <div class="alert alert-success">{{ $success }}</div>
                    @endif
                    @error('error')
                    <div class="alert alert-danger">{!! $message !!}</div>
                    @enderror

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <p class="card-title pt-1"><b>Unclaimed Admin Expenses</b></p>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="claim">
                            <table class="table table-bordered border-0">
                                <thead class="">
                                @if(!empty($admin_expenses_list))
                                    <th class="add-services-table">
                                        <input type="checkbox" wire:model="checked_all" class=""/>
                                    </th>
                                @endif
                                <th class="add-services-table text-muted">#</th>
                                <th class="add-services-table text-muted">Expense Date</th>
                                <th class="add-services-table text-left text-muted">Vendor / Invoice #</th>
                                <th class="add-services-table text-left text-muted">Expense Account</th>
                                <th class="add-services-table text-left text-muted">Description</th>
                                <th class="add-services-table text-right text-muted">Amount</th>
                                <th class="add-services-table text-left text-muted">Added By / At</th>
                                </thead>
                                <tbody>
                                @forelse($admin_expenses_list as $ae)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   wire:model="checked_admin_expenses.{{$ae['id']}}" class=""/>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                                        <td>
                                            {{ ucwords($ae['vendor_name'] ?? '') }}<br>{{ $ae['invoice_no'] }}
                                        </td>
                                        <td>{{ ucwords($ae['account_head'] ?? '') }}</td>
                                        <td>{{ ucfirst($ae['description'] ?? '') }}</td>
                                        <td class="text-right">{{ number_format($ae['amount'],2) }}</td>
                                        <td>
                                            {{ ucwords($ae['added_by_name'] ?? '') }}<br>{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}
                                        </td>
                                    </tr>
                                    @if($loop->last)
                                        <tr>
                                            <td class="px-2 py-2 text-right" colspan="6">
                                                <b>Total</b>
                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                <b>{{ number_format(collect($admin_expenses_list)->sum('amount'),2) }}</b>
                                            </td>
                                            <td class="px-2 py-2"></td>
                                        </tr>
                                        <tr>
                                            <td class="px-2 py-2 text-right" colspan="6">
                                                <b>Selected Bills</b>
                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                <b>{{ number_format(count(array_filter($checked_admin_expenses))) }}</b>
                                            </td>
                                            <td class="px-2 py-2"></td>
                                        </tr>
                                        <tr>
                                            <td class="px-2 py-2 text-right" colspan="6">
                                                <b>Selected Amount</b>
                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                @php
                                                    $amount = collect($admin_expenses_list)->whereIn('id',array_keys(array_filter($checked_admin_expenses)))->sum('amount');
                                                @endphp
                                                <b>{{ number_format($amount,2) }}</b>
                                            </td>
                                            <td class="px-2 py-2"></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-danger rounded-md overflow-hidden">
                                            <div class="alert alert-danger mb-0 py-4 text-center">
                                                <b>No Records Found.</b>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-end align-items-center pt-2">
                                @if(count(array_filter($checked_admin_expenses)) > 0)
                                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary mx-1">
                                        <span wire:loading.remove wire:target="claim">Claim</span>
                                        <span wire:loading wire:target="claim">Claiming...</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary mx-1"
                                            onclick="$('#SelectedExpenses').modal('show')">
                                        View Selected ({{ count(array_filter($checked_admin_expenses)) }})
                                    </button>
                                @endif
                                <a href="{{ url('accounts/admin-expenses/list') }}" class="btn btn-secondary mx-1">
                                    Go Back
                                </a>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include("ams::livewire.admin-expenses.selected-expenses-modal")
    </div>
@else
    <div>
        <div class="shadow rounded-md">
            <div class="bg-white  mb-5 rounded-md overflow-hidden">
                @if(!empty($success))
                    <div class="px-6 pt-6">
                        <div class="p-4  rounded-md bg-green-50">
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
                                    <p class="text-sm font-medium text-green-800">{{ $success }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @error('error')
                <div class="px-6 pt-6">
                    <div class="p-4 rounded-md bg-red-50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    There was an error with your submission.
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>{!! $message !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @enderror

                <div class="py-6 px-4 sm:p-6 flex justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Unclaimed Admin
                        Expenses</h3>
                </div>
                <form wire:submit.prevent="claim">
                <table class="min-w-full table-fixed  ">
                    <thead class="">
                    <tr class="">
                        @if(!empty($admin_expenses_list))
                            <th scope="col"
                                class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                                <input type="checkbox" wire:model="checked_all"
                                       class="cursor-pointer relative w-5 h-5 border rounded border-gray-400 bg-white text-indigo-500 focus:outline-none focus:ring-2  focus:ring-indigo-500"/>
                            </th>
                        @endif
                        <th scope="col"
                            class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            #
                        </th>
                        <th scope="col" style="width: 110px;"
                            class="px-2 py-2   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Expense Date
                        </th>
                        <th scope="col"
                            class="px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Vendor / Invoice #
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Expense Account
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Description
                        </th>
                        <th scope="col" style="width: 110px;"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                            Amount
                        </th>
                        <th scope="col" style="width: 150px;"
                            class="rounded-tr-md bg-gray-100    border-t px-2 py-2     text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Added By / At
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white  ">
                    @forelse($admin_expenses_list as $ae)
                        <tr class="{{ $loop->first ? 'border-t': '' }}   border-b">
                            <td class="px-2 py-2  border-r text-sm text-gray-500">
                                <input type="checkbox" wire:model="checked_admin_expenses.{{$ae['id']}}"
                                       class="cursor-pointer relative w-5 h-5 border rounded border-gray-400 bg-white text-indigo-500 focus:outline-none focus:ring-2  focus:ring-indigo-500"/>
                            </td>
                            <td class="px-2 py-2  border-r text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ date('d M, Y',strtotime($ae['expense_date'])) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['vendor_name'] ?? '') }}<br>{{ $ae['invoice_no'] }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['account_head'] ?? '') }}
                            </td>
                            <td class=" px-2 py-2 border-r text-sm text-gray-500 whitespace-initial"
                                style="width: 400px !important;">
                                {{ ucfirst($ae['description'] ?? '') }}
                            </td>
                            <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                {{ number_format($ae['amount'],2) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['added_by_name'] ?? '') }}<br>{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}
                            </td>
                        </tr>
                        @if($loop->last)
                            <tr class="border-b">
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="6">
                                    <b>Total</b>
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    <b>{{ number_format(collect($admin_expenses_list)->sum('amount'),2) }}</b>
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500"></td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="6">
                                    <b>Selected Bills</b>
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    <b>{{ number_format(count(array_filter($checked_admin_expenses))) }}</b>
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500"></td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="6">
                                    <b>Selected Amount</b>
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    @php
                                        $amount = collect($admin_expenses_list)->whereIn('id',array_keys(array_filter($checked_admin_expenses)))->sum('amount');
                                    @endphp
                                    <b>{{ number_format($amount,2) }}</b>
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500"></td>
                            </tr>
                        @endif
                    @empty
                        <tr class="border-t border-b">
                            <td colspan="8" class="text-sm text-red-500 rounded-md overflow-hidden">
                                <div class="flex items-center justify-center py-5">
                                    <b>No Records Found.</b>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="w-full flex justify-end items-center py-6 px-4 sm:p-6">
                    @if(count(array_filter($checked_admin_expenses)) > 0)
                        <button type="submit" wire:loading.attr="disabled"
                                class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading.remove wire:target="claim">Claim</span>
                            <span wire:loading wire:target="claim">Claiming...</span>
                        </button>
                        <button type="button" x-data="{}" @click="$dispatch('open-selected-modal')"
                                class="ml-2 inline-flex justify-center px-4 py-2 text-sm font-medium text-indigo-600 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View Selected ({{ count(array_filter($checked_admin_expenses)) }})
                        </button>
                    @endif
                    <a href="{{ url('accounts/admin-expenses/list') }}"
                       class="ml-1 inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Go Back
                    </a>
                </div>
                </form>
            </div>
        </div>
        @include("ams::livewire.admin-expenses.selected-expenses-modal")
    </div>
@endif
