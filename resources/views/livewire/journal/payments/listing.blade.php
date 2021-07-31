<div>
    <div class="shadow sm:rounded-md sm:overflow-hidden bg-white">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Payments & Receiving</h3>
                <a href="{{  url('accounts/accountant/payments/add') }}" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    Create
                </a>
            </div>

            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-2">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="type" wire:model="type"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""></option>
                        <option value="pay">Amount Pay to Anyone</option>
                        <option value="receive">Amount Receive from Anyone</option>
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <label for="date" class="block text-sm font-medium text-gray-700">Transaction Date</label>
                    <input type="text" wire:model.defer="date"
                           class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>


            </div>


        </div>



    </div>


</div>


