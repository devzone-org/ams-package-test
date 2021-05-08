<div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">

    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white ">
            <div class="py-6 px-4   sm:p-6 ">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Chart of Accounts</h3>
                    <select name="first_name" wire:model="type" id="first_name"
                            class="w-1/4 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All</option>
                        <option value="Assets">Assets</option>
                        <option value="Liabilities">Liabilities</option>
                        <option value="Equity">Equity</option>
                        <option value="Income">Income</option>
                        <option value="Expenses">Expenses</option>
                    </select>
                </div>

            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>

                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500  tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500  tracking-wider">
                        Code
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500  tracking-wider">
                        Balance
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500  tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="relative px-3 py-2">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($coa->where('level','1') as $one)
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                            {{ $one->name }}
                        </td>
                    </tr>
                    @foreach($coa->where('sub_account',$one->id) as $two)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                {!! str_repeat('&nbsp;', 6) !!} {{ $two->name }}
                            </td>
                        </tr>
                        @foreach($coa->where('sub_account',$two->id) as $three)
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {!! str_repeat('&nbsp;', 12) !!} {{ $three->name }}
                                </td>
                            </tr>
                            @foreach($coa->where('sub_account',$three->id) as $four)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                        {!! str_repeat('&nbsp;', 18) !!} {{ $four->name }}
                                    </td>
                                </tr>
                                @foreach($coa->where('sub_account',$four->id) as $five)
                                    <tr>
                                        <td class="px-3 py-2  whitespace-nowrap text-sm font-medium text-gray-900">
                                            {!! str_repeat('&nbsp;', 24) !!} {{ $five->name }}
                                        </td>

                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $five->code }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            20.00
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{date('d M, Y')}}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                            <svg class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                            </svg>
                                        </td>

                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>


        </div>


    </div>
</div>
