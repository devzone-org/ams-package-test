<div>

    <div class="shadow sm:rounded-md   bg-white">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6 rounded-md">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Equity Ratio</h3>
            </div>

            @if ($errors->any())

                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: x-circle -->
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">

                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>


        <table class="min-w-full divide-y divide-gray-200  rounded-md ">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Partner Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ratio (%)
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Account
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Drawing Account
                </th>
            </tr>
            </thead>
            @if(!empty($equity_data))
                <tbody class="bg-white divide-y divide-gray-200  rounded-md">
                @foreach($equity_data as $data)
                    <tr>
                        <td class="px-6 py-4  text-sm font-medium text-gray-900">
                            {{$loop->iteration}}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ucwords($data['partner_name'])}}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{number_format($data['ratio']*100, 2)}} %
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            @if($data['account_name'])
                                {{ucwords($data['account_name'])}}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            @if($data['drawing_account_name'])
                                {{ucwords($data['drawing_account_name'])}}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @endif
        </table>
    </div>


</div>
