@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <p class="card-title pt-1"><b>Search Filters</b></p>
                                @can('3.add.admin-expenses')
                                    <a href="{{ url('accounts/admin-expenses/add') }}" class="btn btn-primary btn-sm">
                                        Add Expense
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="search">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Expense on Dated</label>
                                            <input type="date" wire:model.lazy="filter.expense_date" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Vendor</label>
                                            <input type="text" wire:model.lazy="filter.vendor" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">A/c Head</label>
                                            <select wire:model.defer="filter.expense_account_id" class="form-control">
                                                <option value=""></option>
                                                @foreach($fetch_account_heads as $a)
                                                    <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Amount</label>
                                            <input type="number" step="0.01" wire:model.lazy="filter.amount"
                                                   autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Added By</label>
                                            <select wire:model.defer="filter.added_by" class="form-control">
                                                <option value=""></option>
                                                @foreach($fetch_added_by as $u)
                                                    <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Added At</label>
                                            <input type="date" wire:model.lazy="filter.added_at" autocomplete="off"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="form-group">
                                            <label class="font-weight-normal">Status</label>
                                            <select wire:model.defer="filter.status" class="form-control">
                                                <option value=""></option>
                                                <option value="unclaimed">Unclaimed</option>
                                                <option value="claimed">Claimed</option>
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
                            <p class="card-title"><b>Admin Expenses</b></p>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered border-0">
                                <thead class="">
                                <th class="add-services-table text-muted">#</th>
                                <th class="add-services-table text-muted">Expense on Dated</th>
                                <th class="add-services-table text-left text-muted">Vendor</th>
                                <th class="add-services-table text-left text-muted">A/c Head</th>
                                <th class="add-services-table text-left text-muted">Description</th>
                                <th class="add-services-table text-right text-muted">Amount</th>
                                <th class="add-services-table text-left text-muted">Added By</th>
                                <th class="add-services-table text-left text-muted">Added At</th>
                                <th class="add-services-table text-left text-muted">Status</th>
                                <th class="text-center add-services-table text-muted" style="width: 20px;"></th>
                                </thead>
                                <tbody>
                                @forelse($admin_expenses_list as $ae)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ date('d M, Y',strtotime($ae['expense_date'])) }}
                                        </td>
                                        <td>
                                            {{ ucwords($ae['vendor_name']) }}
                                            @if(!empty($ae['invoice_no']))
                                                <br>{{ $ae['invoice_no'] }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ ucwords($ae['account_head']) }}
                                        </td>
                                        <td>
                                            {{ ucfirst($ae['description']) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($ae['amount'],2) }}
                                        </td>
                                        <td>
                                            {{ ucwords($ae['added_by_name']) }}
                                        </td>
                                        <td>
                                            {{ date('d M, Y',strtotime($ae['created_at'])) }}
                                        </td>
                                        <td>
                                            @if($ae['status'] == 'claimed')
                                                <span class="badge badge-success">Claimed</span>
                                            @else
                                                <span class="badge badge-warning">Unclaimed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ae['status'] == 'unclaimed')
                                                <a href="/accounts/admin-expenses/add/{{$ae['id']}}" class="">
                                                    Edit
                                                </a>
                                            @else
                                                <a href="/accounts/admin-expenses/add/{{$ae['id']}}" class="">
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
                                            <td class="px-2 py-2 text-right" colspan="5">
                                                <b>Total</b>
                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                <b>{{ number_format(collect($admin_expenses_list)->sum('amount'),2) }}</b>
                                            </td>
                                            <td class="px-2 py-2" colspan="4"></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-danger rounded-md overflow-hidden">
                                            No Record Found
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div>
        <div class="mb-5 shadow sm:rounded-md sm:overflow-hidden bg-white">
            <div class="p-4 px-6 flex justify-between border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Search Filters</h3>
                @can('3.add.admin-expenses')
                    <a href="{{ url('accounts/admin-expenses/add') }}"
                       class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Expense
                    </a>
                @endcan
            </div>
            <form wire:submit.prevent="search">
                <div class="py-6 px-4 space-y-6 sm:p-6">
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Expense on Dated </label>
                            <input type="date" wire:model.lazy="filter.expense_date" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Vendor </label>
                            <input type="text" wire:model.lazy="filter.vendor" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">A/c Head </label>
                            <select wire:model.defer="filter.expense_account_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                @foreach($fetch_account_heads as $a)
                                    <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Amount </label>
                            <input type="number" step="0.01" wire:model.lazy="filter.amount" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Added By </label>
                            <select wire:model.defer="filter.added_by"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                @foreach($fetch_added_by as $u)
                                    <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Added At </label>
                            <input type="date" wire:model.lazy="filter.added_at" autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Status </label>
                            <select wire:model.defer="filter.status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                <option value="unclaimed">Unclaimed</option>
                                <option value="claimed">Claimed</option>
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
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Admin Expenses</h3>
                </div>
                <table class="min-w-full table-fixed  ">
                    <thead class="">
                    <tr class="">
                        <th scope="col"
                            class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                            #
                        </th>
                        <th scope="col" style="width: 110px;"
                            class="px-2 py-2   bg-gray-100 border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Expense on Dated
                        </th>
                        <th scope="col"
                            class="px-2 py-2  bg-gray-100  border-t border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Vendor
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            A/c Head
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Description
                        </th>
                        <th scope="col" style="width: 110px;"
                            class="px-2 py-2   border-t bg-gray-100 border-r text-right  text-sm font-bold text-gray-500  tracking-wider">
                            Amount
                        </th>
                        <th scope="col"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Added By
                        </th>
                        <th scope="col" style="width: 110px;"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Added At
                        </th>
                        <th scope="col" style="width: 120px;"
                            class=" px-2 py-2   border-t bg-gray-100 border-r text-left  text-sm font-bold text-gray-500  tracking-wider">
                            Status
                        </th>
                        <th scope="col" style="width: 140px;"
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
                                {{ date('d M, Y',strtotime($ae['expense_date'])) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['vendor_name']) }}
                                @if(!empty($ae['invoice_no']))
                                    <br>{{ $ae['invoice_no'] }}
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['account_head']) }}
                            </td>
                            <td class=" px-2 py-2 border-r text-sm text-gray-500 whitespace-initial"
                                style="width: 400px !important;">
                                {{ ucfirst($ae['description']) }}
                            </td>
                            <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                {{ number_format($ae['amount'],2) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['added_by_name']) }}
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ date('d M, Y',strtotime($ae['created_at'])) }}
                            </td>
                            <td class="px-2 py-2 border-r text-left text-sm text-gray-500">
                                @if($ae['status'] == 'claimed')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Claimed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Unclaimed
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                @if($ae['status'] == 'unclaimed')
                                    <a href="/accounts/admin-expenses/add/{{$ae['id']}}"
                                       class="text-indigo-500 font-medium">
                                        Edit
                                    </a>
                                @else
                                    <a href="/accounts/admin-expenses/add/{{$ae['id']}}"
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
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500" colspan="5">
                                    <b>Total</b>
                                </td>
                                <td class="px-2 py-2 border-r text-right text-sm text-gray-500">
                                    <b>{{ number_format(collect($admin_expenses_list)->sum('amount'),2) }}</b>
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500" colspan="4"></td>
                            </tr>
                        @endif
                    @empty
                        <tr class="border-t border-b">
                            <td colspan="10" class="text-sm text-red-500 rounded-md overflow-hidden">
                                No Record Found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
