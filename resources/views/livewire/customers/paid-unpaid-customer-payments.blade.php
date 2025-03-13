<div wire:init="checkForRedirect()">
    @if(!$redirect_back)
        <div class="pb-5 border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <span class="ml-4">Paid/Unpaid Customer Payments</span>
            </h3>
        </div>

        <form wire:submit.prevent="markPaidOrUnpaid">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Paid/Unpaid Customer Payments</h3>
                    </div>
                    @include('include.alert')

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Customer</label>
                            <select wire:model="data.customer_id"
                                    class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                @foreach($customer_list as $cl)
                                    <option value="{{$cl['id']}}">{{$cl['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(!empty($data['customer_id']))
                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select wire:model="data.type"
                                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value=""></option>
                                    <option value="paid">Paid</option>
                                    <option value="un-paid">Un-Paid</option>
                                </select>
                            </div>

                            @if($data['type'] == 'paid')
                                <div class="col-span-6 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Voucher No.</label>
                                    <input wire:model.defer="data.voucher_no" type="text" autocomplete="off"
                                           class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            @endif

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Months</label>
                                <select wire:model="selected_months" multiple
                                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach($months_array as $key => $ma)
                                        <option value="{{$key}}">{{$ma}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit"
                            class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        Save
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>