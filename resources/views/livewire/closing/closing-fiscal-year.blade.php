<div>

    <div class="shadow sm:rounded-md   bg-white">
        
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6 rounded-md">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Fiscal Year Closing</h3>
            </div>
        </div>

        <div class="px-4 pr-6 space-y-6 bg-white sm:px-6 sm:pr-8">
            @if (view()->exists('include.error-template'))
                @include('include.error-template')
            @else
                @include('ams::include.error-template-ams')
            @endif
        </div>

        <form wire:submit.prevent="closeFiscalYear">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-6 space-y-6 bg-white sm:p-6">
                    <div class="flex">
                        <div class="flex-1 pr-3">
                            <div class="grid grid-cols-4 gap-4 ">
                            </div>
                            <div class="">
                                <label class="block text-sm font-medium text-gray-700">
                                    Closing Year
                                    <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.defer='closing_year'
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value=""></option>
                                    @foreach ($fiscal_years as $year)
                                        @php
                                            $account = $summary_account->where('fiscal_year', $year['year'])->first();
                                        @endphp

                                        <option @if (!empty($account)) disabled @endif
                                            value="{{ $year['year'] }}"> {{ $year['year'] }} @if (!empty($account))
                                                (Already Closed Voucher No is: {{ $account['voucher_no'] }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <input wire:model.defer='entries_confirm' type="checkbox"
                                    class="rounded border border-gray-400 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <span class="text-sm font-medium text-gray-700">Have you done all your entries?</span>
                                <span class="text-red-500">*</span>
                            </div>
                            <div class="">
                                <small class="text-gray-500">
                                    Please confirm that you have done all the entries of the fiscal
                                    year {{ $closing_year }} before moving to the next step.
                                </small>
                            </div>
                            <div class="py-3 text-right">

                                <button type="button" wire:click="getSummary"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm disabled:opacity-25 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Get Closing Summary
                                </button>
                            </div>

                            <div class="min-w-full flex justify-center">
                                <table class="mt-4 w-2/3 divide-y divide-gray-200 rounded-md">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider border-r border-gray-200">
                                                Dr
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider">
                                                Cr
                                            </th>
                                        </tr>
                                    </thead>

                                    @if (!empty($closing_data_array))
                                        <tbody class="bg-white divide-y divide-gray-200  rounded-md">
                                            <tr>
                                                    @php
                                                        $total_credit = 0;
                                                        $total_debit = 0;
                                                    @endphp
                                                <td
                                                    class="px-6 py-4 text-center text-sm font-medium text-gray-500 border-r border-gray-200">
                                                    @foreach (collect($closing_data_array)->where('type', 'Expenses') as $d)
                                                        @php
                                                            $debit = $d['debit'] - $d['credit'];
                                                            $total_debit = $total_debit + $debit;
                                                            $record = $d['name'] . ' - PKR ' . number_format($debit, 2);
                                                        @endphp
                                                        {{ $record }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                                                    @foreach (collect($closing_data_array)->where('type', 'Income') as $c)
                                                        @php
                                                            $credit = $c['credit'] - $c['debit'];
                                                            $total_credit = $total_credit + $credit;
                                                            $record =
                                                                $d['name'] . ' - PKR ' . number_format($credit, 2);
                                                        @endphp
                                                        {{ $record }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                            </tr>

                                        </tbody>
                                        <tfoot class="bg-gray-50 divide-y divide-gray-200 rounded-md">
                                            <tr>
                                                <td
                                                    class="bold px-6 py-4 text-center text-sm font-medium text-gray-900 border-r border-gray-200">
                                                    <strong>
                                                        {{ env('CURRENCY', 'PKR') }}
                                                        {{ number_format($total_debit, 2) }}
                                                    </strong>
                                                </td>
                                                <td
                                                    class="bold px-6 py-4 text-center text-sm font-medium text-gray-900">
                                                    <strong>
                                                        {{ env('CURRENCY', 'PKR') }}
                                                        {{ number_format($total_credit, 2) }}
                                                    </strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                @if ($total_debit > $total_credit)
                                                    <td
                                                        class="bg-red-50 bold px-6 py-4 text-center text-sm font-medium text-red-900">
                                                        <strong>
                                                            Loss: {{ env('CURRENCY', 'PKR') }}
                                                            {{ number_format($total_debit - $total_credit, 2) }}
                                                        </strong>
                                                    </td>
                                                    <td
                                                        class="bg-white bold px-6 py-4 text-center text-sm font-medium text-gray-900">

                                                    </td>
                                                @elseif($total_debit < $total_credit)
                                                    <td
                                                        class="bg-white bold px-6 py-4 text-center text-sm font-medium text-gray-900">
                                                    </td>
                                                    <td
                                                        class="bg-green-50 bold px-6 py-4 text-center text-sm font-medium text-green-900">
                                                        <strong>
                                                            Profit: {{ env('CURRENCY', 'PKR') }}
                                                            {{ number_format($total_credit - $total_debit, 2) }}
                                                        </strong>
                                                    </td>
                                                @endif
                                            </tr>
                                        </tfoot>
                                    @endif

                                </table>
                            </div>

                            <div class="mt-4">
                                <input wire:model.defer='agree_confirm' type="checkbox"
                                    class="rounded border border-gray-400 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <span class="text-sm font-medium text-gray-700">I Agree</span>
                                <span class="text-red-500">*</span>
                            </div>
                            <div class="">
                                <small class="text-gray-500">
                                    You need to agree with all the details in order to close the fiscal
                                    year {{ $closing_year }}.
                                </small>
                            </div>
                            <div class="py-3 text-right">

                                <button type="submit"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm disabled:opacity-25 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Close year
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

</div>
