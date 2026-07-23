@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    @forelse($groups as $code => $expenses)
                        @php $head = $expenses->first(); @endphp
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-title pt-1 mb-0"><b>{{ strtoupper($code) }}</b></p>
                                    <div class="text-right small text-muted">
                                        <div>Claim In Progress By:
                                            <b>{{ ucwords($head['claim_in_progress_by_name'] ?? '-') }}</b></div>
                                        <div>Claim In Progress At:
                                            <b>{{ !empty($head['claim_in_progress_at']) ? date('d M, Y h:i A', strtotime($head['claim_in_progress_at'])) : '-' }}</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover table-sm mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle" style="width: 40px;">#</th>
                                        <th class="align-middle">Expense Incurred</th>
                                        <th class="align-middle">Vendor</th>
                                        <th class="align-middle">A/C Head</th>
                                        <th class="align-middle text-right">Amount</th>
                                        <th class="align-middle">Added By</th>
                                        <th class="align-middle">Added At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($expenses as $ae)
                                        <tr>
                                            <td class="align-middle text-muted">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                                            <td class="align-middle">{{ ucwords($ae['vendor_name'] ?? '') }}</td>
                                            <td class="align-middle">{{ ucwords($ae['account_head'] ?? '') }}</td>
                                            <td class="align-middle text-right">{{ number_format($ae['amount'],2) }}</td>
                                            <td class="align-middle">{{ ucwords($ae['added_by_name'] ?? '') }}</td>
                                            <td class="align-middle text-muted">{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="thead-light">
                                    <tr>
                                        <td class="text-right" colspan="4"><b>Total</b></td>
                                        <td class="text-right"><b>{{ number_format($expenses->sum('amount'),2) }}</b></td>
                                        <td colspan="2"></td>
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
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-start">
                    <h3 class="text-base font-bold text-gray-900">{{ strtoupper($code) }}</h3>
                    <div class="text-right text-xs text-gray-500 space-y-1">
                        <div>Claim In Progress By:
                            <span class="font-semibold">{{ ucwords($head['claim_in_progress_by_name'] ?? '-') }}</span>
                        </div>
                        <div>Claim In Progress At:
                            <span class="font-semibold">{{ !empty($head['claim_in_progress_at']) ? date('d M, Y h:i A', strtotime($head['claim_in_progress_at'])) : '-' }}</span>
                        </div>
                    </div>
                </div>
                <table class="min-w-full table-fixed">
                    <thead>
                    <tr>
                        <th class="w-7 px-2 bg-gray-100 border-t border-r py-2 text-left text-sm font-bold text-gray-500">#</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Expense Incurred</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Vendor</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">A/C Head</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-right text-sm font-bold text-gray-500">Amount</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Added By</th>
                        <th class="px-2 py-2 bg-gray-100 border-t border-r text-left text-sm font-bold text-gray-500">Added At</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @foreach($expenses as $ae)
                        <tr class="{{ $loop->first ? 'border-t' : '' }} border-b">
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ date('d M, Y',strtotime($ae['expense_date'])) }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ ucwords($ae['vendor_name'] ?? '') }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ ucwords($ae['account_head'] ?? '') }}</td>
                            <td class="px-2 py-2 border-r text-right text-sm text-gray-500">{{ number_format($ae['amount'],2) }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ ucwords($ae['added_by_name'] ?? '') }}</td>
                            <td class="px-2 py-2 border-r text-sm text-gray-500">{{ date('d M, Y h:i A',strtotime($ae['created_at'])) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="border-b">
                        <td class="px-2 py-2 border-r text-right text-sm font-bold text-gray-700" colspan="4">Total</td>
                        <td class="px-2 py-2 border-r text-right text-sm font-bold text-gray-700">{{ number_format($expenses->sum('amount'),2) }}</td>
                        <td class="px-2 py-2 border-r" colspan="2"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        @empty
            <div class="p-6 text-center text-sm text-red-500 bg-white shadow rounded-md"><b>No Records Found.</b></div>
        @endforelse
    </div>
@endif
