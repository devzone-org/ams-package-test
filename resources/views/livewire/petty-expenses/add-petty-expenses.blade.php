<div>
    <div class="mb-54 shadow sm:rounded-md sm:overflow-hidden bg-white">
            <div class="py-6 px-4 sm:p-6 flex justify-between border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">Add Petty Expenses</h3>
            </div>
        <div class="py-6 px-4 space-y-6 sm:p-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-6 sm:col-span-2">
                    <label for="users" class="block text-sm font-medium text-gray-700">User</label>
                    <select wire:model.defer="user_account_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""></option>
{{--                        @foreach($users as $u)--}}
{{--                            <option value="{{ $u['account_id'] }}">{{ $u['account_name'] }}</option>--}}
{{--                        @endforeach--}}
                    </select>

                </div>
                <div class="col-span-6 sm:col-span-1">
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="text" wire:model.lazy="from_date" readonly id="from_date" autocomplete="off"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>


{{--                <div class="col-span-6 sm:col-span-2">--}}
{{--                    <div class="mt-6 flex-shrink-0 flex ">--}}
{{--                        <button type="button" wire:click="search" wire:loading.attr="disabled"--}}
{{--                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">--}}
{{--                            <span wire:loading wire:target="search">Searching ...</span>--}}
{{--                            <span wire:loading.remove wire:target="search">Search</span>--}}
{{--                        </button>--}}
{{--                        <button type="button" wire:click="resetSearch" wire:loading.attr="disabled"--}}
{{--                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">--}}
{{--                            Reset--}}
{{--                        </button>--}}
{{--                        @if(!empty($report))--}}
{{--                            <a href="{{'day-closing/export'}}?id={{$user_account_id}}&from_date={{date('d M Y', strtotime($from_date))}}&to_date={{date('d M Y', strtotime($to_date))}}" target="_blank"--}}
{{--                               class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">--}}
{{--                                Export.csv--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                    </div>--}}

                </div>

            </div>
{{--            <div>--}}
{{--                <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Day Closing Report</h3>--}}
{{--                <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>--}}
{{--                <p class="text-md  font-sm text-gray-500 text-center">Statement--}}
{{--                    Period {{ date('d M, Y',strtotime($from_date)) }} to {{ date('d M, Y',strtotime($to_date)) }} </p>--}}
{{--            </div>--}}
        </div>
    </div>
</div>