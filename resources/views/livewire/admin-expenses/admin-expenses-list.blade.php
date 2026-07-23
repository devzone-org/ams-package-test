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
                            <div class="d-flex justify-content-between">
                                <p class="card-title pt-1"><b>Search Filters</b></p>
                            </div>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="search">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Expense Date From</label>
                                            <input type="date" wire:model.defer="filter.expense_date_from"
                                                   autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Expense Date To</label>
                                            <input type="date" wire:model.defer="filter.expense_date_to"
                                                   autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Vendor</label>
                                            <input type="text" wire:model.defer="filter.vendor" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">A/C Head</label>
                                            <input type="text" wire:model.defer="filter.expense_account"
                                                   autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Amount</label>
                                            <input type="number" step="0.01" wire:model.defer="filter.amount"
                                                   autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Status</label>
                                            <select wire:model.defer="filter.status" class="form-control">
                                                <option value="">All</option>
                                                <option value="unclaimed">Unclaimed</option>
                                                <option value="claim-in-progress">Claim In Progress</option>
                                                <option value="claimed">Claim Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal d-block">&nbsp;</label>
                                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                                                Search
                                            </button>
                                            <button class="btn btn-danger" type="button" wire:click="clear"
                                                    wire:loading.attr="disabled">
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <p class="card-title"><b>Admin Expenses List</b></p>

                            <div>
                                @can('3.add.admin-expenses')
                                    <a href="{{ url('accounts/admin-expenses/add') }}"
                                       class="btn btn-primary btn-sm">
                                        Add Expense
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered border-0">
                                <thead class="">
                                <th class="add-services-table text-muted">#</th>
                                <th class="add-services-table text-muted">Code</th>
                                <th class="add-services-table text-muted">Expense Incurred</th>
                                <th class="add-services-table text-left text-muted">Vendor /<br>A/C Head</th>
                                <th class="add-services-table text-right text-muted">Amount</th>
                                <th class="add-services-table text-left text-muted">Status</th>
                                <th class="add-services-table text-left text-muted">Added By /<br>Added At</th>
                                <th class="add-services-table text-left text-muted" style="cursor: help;"
                                    title="Claim In Progress By / Claim In Progress At">Claim In Progress By /<br>Claim In Progress At</th>
                                <th class="add-services-table text-left text-muted">Claimed By /<br>Claimed At</th>
                                <th class="text-center add-services-table text-muted"></th>
                                </thead>
                                <tbody>
                                @forelse($admin_expenses_list as $ae)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            @if(!empty($ae['code']))
                                                <b class="text-primary">{{ strtoupper($ae['code']) }}</b>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ date('d M, Y',strtotime($ae['expense_date'])) }}
                                        </td>
                                        <td>
                                            {{ ucwords($ae['vendor_name'] ?? '-') }}<br>
                                            <span class="text-muted">{{ ucwords($ae['account_head'] ?? '-') }}</span>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($ae['amount'],2) }}
                                        </td>
                                        <td>
                                            @if($ae['status'] == 'claimed')
                                                <span class="badge badge-success">Claim Completed</span>
                                            @elseif($ae['status'] == 'claim-in-progress')
                                                <span class="badge badge-info">Claim In Progress</span>
                                            @else
                                                <span class="badge badge-warning">Unclaimed</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ ucwords($ae['added_by_name'] ?? '-') }}<br>
                                            <span class="text-muted">{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}</span>
                                        </td>
                                        <td>
                                            @if(!empty($ae['claim_in_progress_by_name']) || !empty($ae['claim_in_progress_at']))
                                                {{ ucwords($ae['claim_in_progress_by_name'] ?? '-') }}<br>
                                                <span class="text-muted">{{ !empty($ae['claim_in_progress_at']) ? date('d M, Y h:i A', strtotime($ae['claim_in_progress_at'])) : '-' }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($ae['claimed_by_name']) || !empty($ae['claimed_at']))
                                                {{ ucwords($ae['claimed_by_name'] ?? '-') }}<br>
                                                <span class="text-muted">{{ !empty($ae['claimed_at']) ? date('d M, Y h:i A', strtotime($ae['claimed_at'])) : '-' }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($ae['status'] == 'unclaimed')
                                                <a href="/accounts/admin-expenses/update/unclaimed/{{$ae['id']}}" class="">
                                                    Edit
                                                </a>
                                                @can('3.delete.admin-expenses.unclaimed')
                                                    |
                                                    <button type="button" class="btn btn-link p-0 text-danger"
                                                            wire:click.prevent="openDeleteModal({{$ae['id']}})">
                                                        Delete
                                                    </button>
                                                @endcan
                                            @else
                                                <a href="{{ url('accounts/admin-expenses/view/'.$ae['id']) }}" class="">
                                                    View
                                                </a>
                                            @endif
                                            @if(!empty($ae['attachment']))
                                                <br>
                                                <a href="{{ env('AWS_URL').$ae['attachment'] }}"
                                                   class="" target="_blank">
                                                    View Attachment
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($loop->last)
                                        <tr>
                                            <td class="px-2 py-2 text-right" colspan="4">
                                                <b>Total</b>
                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                <b>{{ number_format($admin_expenses_list->sum('amount'),2) }}</b>
                                            </td>
                                            <td class="px-2 py-2" colspan="5"></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-danger rounded-md overflow-hidden">
                                            <div class="alert alert-danger mb-0 py-4 text-center">
                                                <b>No Records Found.</b>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div>
                                {{ $admin_expenses_list->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-data="{ open: @entangle('delete_modal') }"
             class="modal" id="DeleteConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deleteModalLabel"
             :style="open ? 'display:block; background: rgba(0,0,0,.5);' : 'display:none;'">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="deleteModalLabel">Attention !</h5>
                        <button type="button" class="close" wire:click.prevent="closeDeleteModal()"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to {{ $delete_modal_msg }}? This can't be undone.</p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" wire:click.prevent="closeDeleteModal()" class="btn btn-sm btn-light">
                            Cancel
                        </button>
                        <button type="button" wire:click="deleteRecord" wire:loading.attr="disabled"
                                class="btn btn-sm btn-danger">
                            <span wire:loading.remove wire:target="deleteRecord">Delete</span>
                            <span wire:loading wire:target="deleteRecord">
                                <span class="spinner-border spinner-border-sm" role="status"
                                      aria-hidden="true"></span>
                                Deleting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div>
        <div class="mb-5 shadow sm:rounded-md sm:overflow-hidden bg-white">
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
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" wire:click="$set('success', '')"
                                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                        <span class="sr-only">Dismiss</span>
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
                </div>
            @endif

            <div class="p-4 px-6 flex justify-between border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Search Filters</h3>
            </div>
            <form wire:submit.prevent="search">
                <div class="py-6 px-4 space-y-6 sm:p-6">
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Expense Date From </label>
                            <input type="date" wire:model.defer="filter.expense_date_from" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Expense Date To </label>
                            <input type="date" wire:model.defer="filter.expense_date_to" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Vendor </label>
                            <input type="text" wire:model.defer="filter.vendor" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">A/C Head </label>
                            <input type="text" wire:model.defer="filter.expense_account" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Amount </label>
                            <input type="number" step="0.01" wire:model.defer="filter.amount" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Status </label>
                            <select wire:model.defer="filter.status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All</option>
                                <option value="unclaimed">Unclaimed</option>
                                <option value="claim-in-progress">Claim In Progress</option>
                                <option value="claimed">Claim Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full flex justify-end">
                        <div>
                            <button type="submit" wire:loading.attr="disabled"
                                    class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>

                            <button type="button" wire:click="clear" wire:loading.attr="disabled"
                                    class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Reset
                            </button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
        <div class="shadow rounded-md">
            <div class="bg-white  mb-5 rounded-md overflow-hidden">
                <div class="py-6 px-4 sm:p-6 flex justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Admin Expenses List</h3>

                    <div class="flex">
                        @can('3.add.admin-expenses')
                            <a href="{{ url('accounts/admin-expenses/add') }}"
                               class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Add Expense
                            </a>
                        @endcan
                    </div>
                </div>
                <table class="min-w-full table-fixed  ">
                    <thead class="">
                    <tr class="">
                        <th scope="col"
                            class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            #
                        </th>
                        <th scope="col"
                            class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500  tracking-wider">
                            Code
                        </th>
                        <th scope="col"
                            class="px-2 py-2 whitespace-nowrap   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Expense Incurred
                        </th>
                        <th scope="col"
                            class="px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Vendor /<br>A/C Head
                        </th>
                        <th scope="col"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                            Amount
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Added By /<br>Added At
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider cursor-help"
                            title="Claim In Progress By / Claim In Progress At">
                            Claim In Progress By /<br>Claim In Progress At
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Claimed By /<br>Claimed At
                        </th>
                        <th scope="col"
                            class="rounded-tr-md bg-gray-100    border-t px-2 py-2     text-left  text-sm font-bold text-gray-500 uppercase tracking-wider">
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white  ">

                    @forelse($admin_expenses_list as $ae)
                        <tr class="{{ $loop->first ? 'border-t': '' }}   border-b">
                            <td class="px-2 py-2  border-r text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                @if(!empty($ae['code']))
                                    <span class="font-bold text-indigo-600">{{ strtoupper($ae['code']) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ date('d M, Y',strtotime($ae['expense_date'])) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['vendor_name'] ?? '-') }}<br>
                                <span class="text-gray-400">{{ ucwords($ae['account_head'] ?? '-') }}</span>
                            </td>
                            <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                {{ number_format($ae['amount'],2) }}
                            </td>
                            <td class="px-2 py-2 border-r text-left text-sm text-gray-500">
                                @if($ae['status'] == 'claimed')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Claim Completed
                                    </span>
                                @elseif($ae['status'] == 'claim-in-progress')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Claim In Progress
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Unclaimed
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['added_by_name'] ?? '-') }}<br>
                                <span class="text-gray-400">{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}</span>
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                @if(!empty($ae['claim_in_progress_by_name']) || !empty($ae['claim_in_progress_at']))
                                    {{ ucwords($ae['claim_in_progress_by_name'] ?? '-') }}<br>
                                    <span class="text-gray-400">{{ !empty($ae['claim_in_progress_at']) ? date('d M, Y h:i A', strtotime($ae['claim_in_progress_at'])) : '-' }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                @if(!empty($ae['claimed_by_name']) || !empty($ae['claimed_at']))
                                    {{ ucwords($ae['claimed_by_name'] ?? '-') }}<br>
                                    <span class="text-gray-400">{{ !empty($ae['claimed_at']) ? date('d M, Y h:i A', strtotime($ae['claimed_at'])) : '-' }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                @if($ae['status'] == 'unclaimed')
                                    <a href="{{ url('accounts/admin-expenses/update/unclaimed/'.$ae['id']) }}"
                                       class="text-indigo-500 font-medium">
                                        Edit
                                    </a>
                                    @can('3.delete.admin-expenses.unclaimed')
                                        |
                                        <button type="button" class="text-red-500 font-medium" style="outline: none !important;"
                                                @click="$dispatch('open-delete-modal')"
                                                wire:click.prevent="openDeleteModal({{$ae['id']}})">
                                            Delete
                                        </button>
                                    @endcan
                                @else
                                    <a href="{{ url('accounts/admin-expenses/view/'.$ae['id']) }}"
                                       class="text-gray-500 font-medium">
                                        View
                                    </a>
                                @endif
                                @if(!empty($ae['attachment']))
                                    <br>
                                    <a href="{{ env('AWS_URL').$ae['attachment'] }}"
                                       class="text-yellow-500 font-medium" target="_blank">
                                        View Attachment
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @if($loop->last)
                            <tr class="border-b">
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="4">
                                    <b>Total</b>
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    <b>{{ number_format($admin_expenses_list->sum('amount'),2) }}</b>
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500" colspan="5"></td>
                            </tr>
                        @endif
                    @empty
                        <tr class="border-t border-b">
                            <td colspan="10" class="text-sm text-red-500 rounded-md overflow-hidden">
                                <div class="flex items-center justify-center py-5">
                                    <b>No Records Found.</b>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                @if($admin_expenses_list->isNotEmpty() && $admin_expenses_list->lastPage() > 1)
                    <div class="bg-white p-3 border-t rounded-b-md  ">
                        {{ $admin_expenses_list->links() }}
                    </div>
                @endif
            </div>
        </div>
        @include("ams::include.delete-modal")
    </div>
@endif
