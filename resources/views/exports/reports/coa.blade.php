<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">

    <tr>
        <th colspan="4">Chart of Accounts</th>
    </tr>
    <tr>
        <th colspan="4">{{env('APP_NAME')}}</th>
    </tr>

    <tr>
        <th scope="col">
            Name
        </th>
        <th scope="col" >
            Code
        </th>
        <th scope="col">
            Balance
        </th>
        <th scope="col" >
            Date
        </th>

    </tr>
    </thead>
    <tbody>
    @foreach($coa->where('level','1') as $one)
        <tr>
            <td>
                {{ $one->name }}
            </td>
        </tr>
        @foreach($coa->where('sub_account',$one->id) as $two)
            <tr>
                <td >
                    {!! str_repeat('&nbsp;', 6) !!}  {{ $two->name }}
                </td>
            </tr>
            @foreach($coa->where('sub_account',$two->id) as $three)
                <tr>
                    <td>
                        {!! str_repeat('&nbsp;', 12) !!}  {{ $three->name }}
                    </td>
                </tr>
                @foreach($coa->where('sub_account',$three->id) as $four)
                    <tr>
                        <td >
                            {!! str_repeat('&nbsp;', 18) !!}    {{ $four->name }}
                        </td>
                    </tr>
                    @foreach($coa->where('sub_account',$four->id) as $five)
                        <tr>
                            <td>
                                    <span>{!! str_repeat('&nbsp;', 24) !!}</span>

{{--                                    @if($five->is_contra == 't')--}}
{{--                                        <svg--}}
{{--                                                class="w-4 h-4 {{ $five->status=='f'?'text-red-600':'text-green-500' }}"--}}
{{--                                                fill="currentColor" viewBox="0 0 20 20"--}}
{{--                                                xmlns="http://www.w3.org/2000/svg">--}}
{{--                                            <path fill-rule="evenodd"--}}
{{--                                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"--}}
{{--                                                  clip-rule="evenodd"></path>--}}
{{--                                        </svg>--}}
{{--                                    @endif--}}
                                    <span>  {{ $five->name }}</span>

                            </td>

                            <td>{{ $five->code }}</td>
                            <td>
                                @php
                                    $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                    if($clo<0){
                                        echo abs($clo);
                                    } else {
                                        echo abs($clo);
                                    }
                                @endphp
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if(!empty($five->posting_date)) {{date('d M, Y',strtotime($five->posting_date))}} @endif</td>


                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>