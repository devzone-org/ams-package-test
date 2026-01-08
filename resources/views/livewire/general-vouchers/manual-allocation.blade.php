@if(env('AMS_BOOTSTRAP') == 'true')
    @section('content')
    <div class="content-wrapper">
        <style>

        </style>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-sm-6">
                        <h1>Transaction @if ($deallocate)
                                Allocation
                            @else
                                Deallocation
                            @endif
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">General Vouchers</a></li>
                            <li class="breadcrumb-item active">Transaction
                                @if ($deallocate)
                                    Allocation
                                @else
                                    Deallocation
                                @endif
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-dark card-outline">
                            <div class="card-light ">
                                <div class="card-header bg-white">
                                    <h3 class="card-title text-bold">
                                        Transaction @if ($deallocate)
                                            Allocation
                                        @else
                                            Deallocation
                                        @endif
                                    </h3>
                                </div>
                                <form wire:submit.prevent='SearchAmount'>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4" wire:ignore>
                                                <label for="">Choose Customer<sup><i class="fas fa-asterisk fa-xs"
                                                            style="color: #c52128;"></i></sup></label>
                                                <select name="" id="customers" class="form-control select2-danger"
                                                    data-dropdown-css-class="select2-danger" wire:model="selected_customer">
                                                    <option value="">Select Customer</option>
                                                    @foreach ($customers as $c)
                                                        <option value="{{ $c['account_id'] }}">{{ $c['customer_code'] }} -
                                                            {{ $c['customer_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-4">
                                                <label for="">Voucher No. Credit Transactions</label>
                                                <input type="text" class="form-control" placeholder="Voucher No. Credit Transactions"
                                                    wire:model.defer="voucher_no_for_credit">
                                            </div>

                                            <div class="col-4">
                                                <label for="">Voucher No. Sales Invoice</label>
                                                <input type="text" placeholder="Voucher No. Sales Invoice" class="form-control" wire:model.defer="voucher_no">
                                            </div>



                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-4" wire:ignore>
                                                <label for="">Transaction Type</label>
                                                <select name="" id="type" class="form-control select2-danger"
                                                    data-dropdown-css-class="select2-danger" wire:model="selected_type">
                                                    <option value="">Select Type</option>
                                                    <option value="jv">Journal Voucher (JV)</option>
                                                    <option value="pv">Payment Voucher (PV)</option>
                                                    <option value="rv">Receipt Voucher (RV)</option>
                                                    <option value="dv">Contra Voucher (DV)</option>
                                                    <option value="si">Sales Invoice (SI)</option>
                                                </select>
                                            </div>

                                            <div class="col-4">
                                                <label for="">From Date</label>
                                                <input type="date" class="form-control" wire:model.defer="from_date">
                                            </div>
                                            <div class="col-4">
                                                <label for="">To Date</label>
                                                <input type="date" class="form-control" wire:model.defer="to_date">
                                            </div>

                                        </div>

                                        <div class="row pt-3">
                                            <div class="col-4">
                                                <label for="">Allocate/Deallocate</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <input type="checkbox" wire:model.defer="deallocate"
                                                                id="toggle_allocate">
                                                        </span>
                                                    </div>
                                                    <input type="text" tabindex=-1 id="toggle_allocate_text"
                                                        class="form-control {{ !$deallocate ? 'text-danger' : 'text-success' }}"
                                                        value="{{ !$deallocate ? 'Allocated' : 'Deallocated' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="float-right">
                                            {{-- <a href="/master/data/chart-of-accounts" class="btn btn-danger px-3">Back</a> --}}
                                            <button type="submit" id="submitButton" class="btn btn-dark px-3"
                                                wire:loading.attr='disabled'>Fetch</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($customer_details))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-dark card-outline">
                                <div class="card-light">
                                    <div class="card-header bg-white py-2">
                                        <h3 class="card-title text-bold pt-1 d-flex justify-content-start">
                                            Customer Details
                                        </h3>
                                    </div>

                                    <div class="card-body p-0">
                                        <table class="table table-bordered table-striped table-sm">
                                            <tr class="col-12">
                                                <th class="col-3">Frequency</th>
                                                <th class="col-3">
                                                    {{ !empty($customer_details[0]['frequency']) ? $customer_details[0]['frequency'] : '' }}
                                                </th>
                                                <th class="col-3">Grace Period</th>
                                                <th class="col-3">
                                                    {{ !empty($customer_details[0]['grace_period']) ? $customer_details[0]['grace_period'] : '' }}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="col-3">Credit Limit</th>
                                                <th class="col-3">
                                                    {{ !empty($customer_details[0]['amount']) ? number_format($customer_details[0]['amount'], 2) : '' }}
                                                </th>
                                                <th class="col-3">Closing Balance</th>
                                                <th class="col-3">
                                                    @if (!empty($closing_balance))
                                                        @if ($closing_balance >= 0)
                                                            {{ number_format($closing_balance, 2) }}
                                                        @else
                                                            ({{ number_format(abs($closing_balance), 2) }})
                                                        @endif
                                                    @else
                                                        &nbsp;
                                                    @endif
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!empty($settled_data))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-dark card-outline">
                                <div class="card-light">
                                    <div class="card-header bg-white py-2">
                                        <h3 class="card-title text-bold pt-1 d-flex justify-content-start">
                                            Allocated Transactions
                                        </h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th class="bg-dark" colspan="6">Credit
                                                        Transactions - Allocated Deposits</th>
                                                    <th></th>
                                                    <th class="bg-dark" colspan="5">Paid
                                                        - Sales Invoices</th>
                                                </tr>
                                                {{-- <th class="text-center">#</th> --}}
                                                <tr class="bg-dark">
                                                    <th class=" text-center align-middle"><input type="checkbox"
                                                            class="form-control" wire:model="deallocate_checkbox_all">
                                                    </th>
                                                    <th class="text-center">Posting Date</th>
                                                    <th>Voucher No.</th>
                                                    <th>Reference No.</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-right">Allocated</th>
                                                    <th class="border-top-0 bg-light-gray"></th>
                                                    {{-- <th class="text-center">#</th> --}}
                                                    <th class="text-center">Posting Date</th>
                                                    <th>Voucher No.</th>
                                                    <th>Reference No.</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-right">Allocated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $count = 0;
                                                @endphp
                                                @foreach ($settled_data as $ids => $data)
                                                    @php
                                                        $credit_count = count($data['credit']);
                                                        $count++;
                                                    @endphp
                                                    @for ($i = 0; $i < $credit_count; $i++)
                                                        <tr class="{{ $count % 2 == 0 ? 'bg-light-gray' : 'bg-white' }}">
                                                            @if ($i == 0)
                                                                <td class="text-center align-middle"
                                                                    rowspan="{{ $credit_count }}">
                                                                    <input type="checkbox" class="form-control"
                                                                        wire:model="deallocate_checkbox.{{ $data['debit'][0]['ledger_id'] }}">
                                                                </td>
                                                            @endif
                                                            <td class="text-center align-middle">
                                                                {{ date('d/m/Y', strtotime($data['credit'][$i]['posting_date'])) }}
                                                            </td>
                                                            <td class="align-middle">
                                                                {{ $data['credit'][$i]['voucher_no'] }}</td>
                                                            <td class="align-middle">
                                                                {{ $data['credit'][$i]['reference_no'] }}</td>
                                                            <td class="text-right align-middle">
                                                                {{ number_format($data['credit'][$i]['credit'], 2) }}</td>
                                                            <td class="text-right align-middle">
                                                                {{ number_format($data['credit'][$i]['allocated'], 2) }}
                                                            </td>
                                                            <td class="border-bottom-0 border-top-0"></td>
                                                            @if ($i == 0)
                                                                <td class="text-center align-middle"
                                                                    rowspan="{{ $credit_count }}">
                                                                    {{ date('d/m/Y', strtotime($data['debit'][0]['posting_date'])) }}
                                                                </td>
                                                                <td class="align-middle" rowspan="{{ $credit_count }}">
                                                                    {{ $data['debit'][0]['voucher_no'] }}</td>
                                                                <td class="align-middle" rowspan="{{ $credit_count }}">
                                                                    {{ $data['debit'][0]['reference_no'] }}</td>
                                                                <td class="text-right align-middle"
                                                                    rowspan="{{ $credit_count }}">
                                                                    {{ number_format($data['debit'][0]['debit'], 2) }}</td>
                                                                <td class="text-right align-middle"
                                                                    rowspan="{{ $credit_count }}">
                                                                    {{ number_format($data['debit'][0]['settled_amount'], 2) }}
                                                                </td>
                                                            @endif

                                                        </tr>
                                                    @endfor
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer">
                                        <div class="float-right">
                                            @if (auth()->user()->can('004-transaction_allocation-delete'))
                                                <button type="button" class="btn btn-dark px-3"
                                                    wire:click="deallocate">Deallocate</button>
                                            @else
                                                <button type="button" class="btn btn-dark px-3"
                                                    disabled>Deallocate</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(!empty($unsettled_credit_entries) || !empty($unsettled_debit_entries))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-dark card-outline">
                                <div class="card-light">
                                    <div class="card-header bg-white py-2">
                                        <h3 class="card-title text-bold pt-1 d-flex justify-content-start">
                                            Unallocated Transactions
                                        </h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="w-100 d-flex justify-content-between">
                                            <div class="w-50 table-responsive pl-2 pr-1 pt-2">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="7" class="bg-dark" style="font-size: 15px">
                                                                Credit
                                                                Transactions - Unallocated Deposits</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="align-middle text-center">
                                                                <span>
                                                                    <input type="checkbox" class="form-control"
                                                                        wire:model="select_all_credit"
                                                                        @if (empty($select_all_credit)) disabled @endif>
                                                                </span>
                                                            </th>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Posting Date</th>
                                                            <th>Voucher No.</th>
                                                            <th>Reference No.</th>
                                                            <th class="text-right">Amount</th>
                                                            <th class="text-right">Unallocated</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($unsettled_credit_entries))

                                                            @foreach ($unsettled_credit_entries as $k => $uce)
                                                                <tr>
                                                                    <td class="align-middle text-center">
                                                                        <span>
                                                                            <input type="checkbox" class="form-control"
                                                                                wire:model="credit_checkbox.{{ $uce['id'] }}"
                                                                                @if ($first_check == 'credit' && $first_voucher != $uce['id']) disabled @endif>
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                                    <td class="text-center">
                                                                        {{ date('d/m/Y', strtotime($uce['posting_date'])) }}
                                                                    </td>
                                                                    <td>{{ $uce['voucher_no'] }}</td>
                                                                    <td>{{ $uce['reference_no'] }}</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($uce['credit'], 2) }}</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($uce['unallocated'], 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            <tr>
                                                                <th colspan="5" class="text-right">Total </th>
                                                                <th class="text-right">
                                                                    {{ number_format(collect($unsettled_credit_entries)->sum('credit'), 2) }}
                                                                </th>
                                                                <th class="text-right">
                                                                    {{ number_format(collect($unsettled_credit_entries)->sum('unallocated'), 2) }}
                                                                </th>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="w-50 table-responsive pl-1 pr-2 pt-2">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="7" class="bg-dark" style="font-size: 15px">
                                                                Unpaid
                                                                - Sales Invoices</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="align-middle text-center">
                                                                <span>
                                                                    <input type="checkbox" class="form-control"
                                                                        wire:model="select_all_debit"
                                                                        @if (empty($select_all_debit)) disabled @endif>
                                                                </span>
                                                            </th>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Posting Date</th>
                                                            <th>Voucher No.</th>
                                                            <th>Reference No.</th>
                                                            <th class="text-right">Amount</th>
                                                            <th class="text-right">Unallocated</th>
                                                        </tr>
                                                    </thead>
                                                    @if (!empty($unsettled_debit_entries))

                                                        @foreach ($unsettled_debit_entries as $k => $ude)
                                                            <tr>
                                                                <td class="align-middle text-center">
                                                                    <span>
                                                                        <input type="checkbox" class="form-control"
                                                                            wire:model="debit_checkbox.{{ $ude['id'] }}"
                                                                            @if ($first_check == 'debit' && $first_voucher != $ude['id']) disabled @endif>
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td class="text-center">
                                                                    {{ date('d/m/Y', strtotime($ude['posting_date'])) }}
                                                                </td>
                                                                <td>{{ $ude['voucher_no'] }}</td>
                                                                <td>{{ $ude['reference_no'] }}</td>
                                                                <td class="text-right">
                                                                    {{ number_format($ude['debit'], 2) }}</td>
                                                                <td class="text-right">
                                                                    {{ number_format($ude['unallocated'], 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <th colspan="5" class="text-right">Total </th>
                                                            <th class="text-right">
                                                                {{ number_format(collect($unsettled_debit_entries)->sum('debit'), 2) }}
                                                            </th>
                                                            <th class="text-right">
                                                                {{ number_format(collect($unsettled_debit_entries)->sum('unallocated'), 2) }}
                                                            </th>
                                                        </tr>
                                                    @endif

                                                </table>

                                            </div>
                                        </div>
                                        @if (!empty($unsettled_debit_entries) || !empty($unsettled_credit_entries))
                                            <div class='w-100 d-flex justify-content-between'>

                                                <div class="w-50">
                                                    &nbsp;
                                                </div>

                                                <div class="w-50">

                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td class="border-0">
                                                                <div class="d-flex justify-content-end">
                                                                    <div class="px-3 pt-1">
                                                                        <b>Selected Credit Transactions - Unallocated
                                                                            Deposits</b>
                                                                    </div>
                                                                    <div>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend ">
                                                                                <span
                                                                                    class="input-group-text py-0 bg-transparent">
                                                                                    <input type="text"
                                                                                        class="border-0 text-center bg-transparent"
                                                                                        value="{{ !empty($credit_checkbox) ? count($credit_checkbox) : 0 }}"
                                                                                        disabled style="width: 15px">
                                                                                </span>
                                                                            </div>
                                                                            <input type="text" tabindex=-1
                                                                                class="form-control text-right bg-transparent"
                                                                                value="{{ number_format($selected_credit_amount, 2) }}"
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="border-0">
                                                                <div class="d-flex justify-content-end">
                                                                    <div class="px-3 pt-1">
                                                                        <b>Selected Unpaid - Sales Invoices</b>
                                                                    </div>

                                                                    <div>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span
                                                                                    class="input-group-text py-0 bg-transparent">
                                                                                    <input type="text"
                                                                                        class="border-0 text-center bg-transparent"
                                                                                        value="{{ !empty($debit_checkbox) ? count($debit_checkbox) : 0 }}"
                                                                                        disabled style="width: 15px">
                                                                                </span>
                                                                            </div>
                                                                            <input type="text" tabindex=-1
                                                                                class="form-control text-right bg-transparent"
                                                                                value="{{ number_format($selected_debit_amount, 2) }}"
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="float-right">
                                            @if (auth()->user()->can('004-transaction_allocation-add'))
                                                <button type="button" class="btn btn-dark px-3"
                                                    wire:click="allocate">Allocate</button>
                                            @else
                                                <button type="button" class="btn btn-dark px-3"
                                                    disabled>Allocate</button>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- @if (!empty($customer_account_id))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-dark card-outline">
                                <div class="card-light ">
                                    <div class="card-header bg-white">
                                        <h3 class="card-title text-bold ">
                                            Activity Log</h3>
                                    </div>
                                    {{-- @livewire('audit-log.audit-log', ['log_name' => ['Allocation', 'Deallocation'], 'target_id' => $customer_account_id]) --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#toggle_allocate').on('change', function(e) {
                    toggleCheckBoxEvent('toggle_allocate', 'toggle_allocate_text', 'Deallocated', 'Allocated');
                });

                function toggleCheckBoxEvent(checkbox_id, text_id, true_text, false_text) {
                    var toggle_checkbox = $('#' + checkbox_id).prop('checked');
                    var toggle_text = $('#' + text_id)

                    toggle_text.removeClass(toggle_checkbox ? 'text-danger' : 'text-success')
                        .addClass(toggle_checkbox ? 'text-success' : 'text-danger')
                        .val(toggle_checkbox ? true_text : false_text);
                }

                $('#customers').select2({
                    placeholder: "Select Customer",
                    ajax: {
                        url: '/dropdown/customer-account',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                // customers
                $('#submitButton').on('click', function(e) {
                    var data = $('#customers').select2("val");
                    @this.
                    set(`selected_customer`, data);
                });

                $('#type').select2({
                    placeholder: "Select Transaction Type",
                });

                // customers
                $('#submitButton').on('click', function(e) {
                    var data = $('#type').select2("val");
                    @this.
                    set(`selected_type`, data);
                });
            });

            document.addEventListener('resetSelect2Fields', function() {
                $('#level').val('').trigger('change');
                $('#account_type').val('').trigger('change');
                $('#parent_account').val('').trigger('change');
            })
        </script>
    @endpush
    @endsection
@else
    @section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-4 sm:px-0">
             <h3 class="text-lg font-medium text-gray-900">Feature not available in Tailwind mode yet.</h3>
        </div>
    </div>
    @endsection
@endif
