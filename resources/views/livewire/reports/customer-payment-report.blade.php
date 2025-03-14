<div>
    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Customer Payments</h3>
            </div>

            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-6 sm:col-span-3">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">Customers</label>
                    <select wire:model.defer="customer_id"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""></option>
                        @foreach($customer_list as $cl)
                            <option value="{{$cl['id']}}">{{$cl['name']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="month" wire:model.lazy="from_date" id="from_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="month" wire:model.lazy="to_date" id="to_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model.defer="status"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""></option>
                        <option value="Active">Active</option>
                        <option value="In-Active">In-Active</option>
                        <option value="Opportunity">Opportunity</option>
                    </select>
                </div>

{{--                <div class="col-span-6 sm:col-span-3">--}}
{{--                    <label for="to_date" class="block text-sm font-medium text-gray-700">Payment Status</label>--}}
{{--                    <select wire:model.defer="payment_status"--}}
{{--                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">--}}
{{--                        <option value=""></option>--}}
{{--                        <option value="Paid">Paid</option>--}}
{{--                        <option value="Un-Paid">Un-Paid</option>--}}
{{--                    </select>--}}
{{--                </div>--}}

                <div class="col-span-6 sm:col-span-2 mt-6">
                    <form wire:submit.prevent="fetchReport()">
                        <button type="submit"
                            class="bg-white  py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <div wire:loading wire:target="search">
                                Searching ...
                            </div>
                            <div wire:loading.remove wire:target="search">
                                Search
                            </div>
                        </button>
                    </form>
                </div>
            </div>


        </div>
        <div>

            <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th scope="col"
                        class="px-2 border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                        #
                    </th>
                    <th scope="col"
                        class="px-2 border-r py-2 text-left text-sm font-bold text-gray-500  tracking-wider">
                        Customer Name
                    </th>
                    <th scope="col"
                        class="px-2 border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                        Status
                    </th>
                    @foreach($months_array as $ma)
                        <th scope="col"
                            class="px-2 border-r py-2 text-center text-sm font-bold text-gray-500  tracking-wider">
                            {{date('M Y', strtotime($ma))}}
                        </th>
                    @endforeach
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @if(!empty($customer_list_2))
                        @foreach($customer_list_2 as $cl)
                            @php
                                $payments_data = !empty($customer_payment_data[$cl['id']]) ? $customer_payment_data[$cl['id']] : [];
                            @endphp
                            <tr class="{{ $loop->even ? 'bg-gray-50' :'' }}">
                                <td class="px-2 py-2 border-r text-sm text-gray-500 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500">
                                    {{ $cl['name'] }}
                                </td>
                                <td class="px-2 py-2 border-r text-sm text-gray-500 text-center">
                                    {{ $cl['status'] }}
                                </td>
                                @foreach($months_array as $ma)
                                    @php
                                        $filtered_data = array_filter($payments_data, function ($item) use ($ma) {
                                            return $item['month'] === $ma;
                                        });
                                        $filtered_data = array_values($filtered_data);
                                        $vouchers_pluck = array_column($filtered_data, 'voucher_no');
                                    @endphp
                                    <td class="px-2 py-2 border-r text-sm text-gray-500 text-center">
                                        @if(!empty($vouchers_pluck))
                                            @foreach($vouchers_pluck as $vp)
                                                <a wire:click="print('{{ $vp }}','true')" style="cursor: pointer">
                                                    <span class="text-green-500">Paid ({{$vp}})</span><br>
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-red-500">Un-paid</span>
                                        @endif
                                    </td>

                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th class="text-red-500 text-center py-2" colspan="{{count($months_array) + 3}}">No record</th>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.addEventListener('print-voucher', event => {
            var url = "/accounts/journal/voucher/print/" + event.detail.voucher_no + "/" + event.detail.print;
            newwindow = window.open(url, 'voucher-print', 'height=500,width=800');
            if (window.focus) {
                newwindow.focus()
            }
            return false;
        })
    </script>
</div>