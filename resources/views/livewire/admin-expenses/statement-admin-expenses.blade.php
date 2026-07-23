@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    @forelse($groups as $code => $expenses)
                        @php $head = $expenses->first(); @endphp
                        <div class="card card-primary card-outline">
                            <div class="card-header py-3">
                                <p class="card-title"><b>Claim In Progress Expenses
                                        <span class="text-primary">#{{ strtoupper($code) }}</span></b></p>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover table-sm mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle" style="width: 40px;">#</th>
                                        <th class="align-middle">Expense Incurred</th>
                                        <th class="align-middle">Vendor /<br>A/C Head</th>
                                        <th class="align-middle text-right">Amount</th>
                                        <th class="align-middle">Status</th>
                                        <th class="align-middle">Added By /<br>Added At</th>
                                        <th class="align-middle" style="cursor: help;"
                                            title="Claim In Progress By / Claim In Progress At">Claim In Progress By /<br>Claim In Progress At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($expenses as $ae)
                                        <tr>
                                            <td class="align-middle text-muted">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                                            <td class="align-middle">
                                                {{ ucwords($ae['vendor_name'] ?? '-') }}<br>
                                                <span class="text-muted">{{ ucwords($ae['account_head'] ?? '-') }}</span>
                                            </td>
                                            <td class="align-middle text-right">{{ number_format($ae['amount'],2) }}</td>
                                            <td class="align-middle">
                                                <span class="badge badge-info" style="cursor: help;"
                                                      title="Claim In Progress">Claim In Progress</span>
                                            </td>
                                            <td class="align-middle">
                                                {{ ucwords($ae['added_by_name'] ?? '-') }}<br>
                                                <span class="text-muted">{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}</span>
                                            </td>
                                            <td class="align-middle">
                                                {{ ucwords($ae['claim_in_progress_by_name'] ?? '-') }}<br>
                                                <span class="text-muted">{{ !empty($ae['claim_in_progress_at']) ? date('d M, Y h:i A', strtotime($ae['claim_in_progress_at'])) : '-' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="thead-light">
                                    <tr>
                                        <td class="text-right" colspan="3"><b>Total</b></td>
                                        <td class="text-right"><b>{{ number_format($expenses->sum('amount'),2) }}</b></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-danger text-center"><b>No Records Found.</b></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@else
    <div>
        @forelse($groups as $code => $expenses)
            @php $head = $expenses->first(); @endphp
            <div class="mb-6 shadow rounded-md overflow-hidden bg-white">
                <div class="px-4 py-4 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Claim In Progress Expenses
                        <span class="text-indigo-600">#{{ strtoupper($code) }}</span></h3>
                </div>
                <table class="min-w-full table-fixed">
                    <thead>
                    <tr>
                        <th class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500">#</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Expense Incurred</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Vendor /<br>A/C Head</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-right text-sm font-bold text-gray-500">Amount</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Status</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Added By /<br>Added At</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500 cursor-help"
                            title="Claim In Progress By / Claim In Progress At">Claim In Progress By /<br>Claim In Progress At</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @foreach($expenses as $ae)
                        <tr class="{{ $loop->first ? 'border-t' : '' }} border-b">
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['vendor_name'] ?? '-') }}<br>
                                <span class="text-gray-400">{{ ucwords($ae['account_head'] ?? '-') }}</span>
                            </td>
                            <td class="px-2 py-2 border-r text-right text-sm text-gray-500">{{ number_format($ae['amount'],2) }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 cursor-help"
                                      title="Claim In Progress">Claim In Progress</span>
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['added_by_name'] ?? '-') }}<br>
                                <span class="text-gray-400">{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}</span>
                            </td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">
                                {{ ucwords($ae['claim_in_progress_by_name'] ?? '-') }}<br>
                                <span class="text-gray-400">{{ !empty($ae['claim_in_progress_at']) ? date('d M, Y h:i A', strtotime($ae['claim_in_progress_at'])) : '-' }}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="border-b">
                        <td class="px-2 py-2 border-r text-right text-sm font-bold text-gray-700" colspan="3">Total</td>
                        <td class="px-2 py-2 border-r text-right text-sm font-bold text-gray-700">{{ number_format($expenses->sum('amount'),2) }}</td>
                        <td class="px-2 py-2 border-r" colspan="3"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        @empty
            <div class="p-6 text-center text-sm text-red-500 bg-white shadow rounded-md"><b>No Records Found.</b></div>
        @endforelse
    </div>
@endif
