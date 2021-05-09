<div class="shadow sm:rounded-md sm:overflow-hidden">
    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add Journal Entry</h3>

        </div>

        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="posting_date" class="block text-sm font-medium text-gray-700">Posting Date</label>
                <input type="date" wire:model="posting_date"  id="posting_date" autocomplete="off" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="voucher_no" class="block text-sm font-medium text-gray-700">Temp Voucher #</label>
                <input type="text" wire:model="voucher_no" readonly id="voucher_no" autocomplete="off" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

        </div>
    </div>
    <div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account
                                </th>
                                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Debit
                                </th>
                                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Credit
                                </th>

                                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-2 py-2  text-sm font-medium text-gray-900">
                                    1
                                </td>
                                <td class="px-2 py-2  text-sm text-gray-500">
                                    Regional Paradigm Technician
                                </td>
                                <td class="px-2 py-2  text-sm text-gray-500">

                                </td>
                                <td class="px-2 py-2  text-sm text-gray-500">

                                </td>
                                <td class="px-2 py-2    text-sm font-medium">

                                </td>
                            </tr>

                            <!-- More people... -->
                            </tbody>
                        </table>


    </div>
    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save
        </button>
    </div>
</div>
