<div class="  max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">


            <div>
                <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Statement of Financial Position</h3>
                <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                <p class="text-md  font-sm text-gray-500 text-center">
                    As At {{ date('d M, Y') }} </p>
            </div>
        </div>


        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col"
                    class="w-3/5 px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                </th>
                <th scope="col"
                    class="w-1/5 px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ env('CURRENCY','PKR') }}
                </th>
                <th scope="col"
                    class="w-1/5 px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ env('CURRENCY','PKR') }}
                </th>

            </tr>
            </thead>
            <tbody x-data="{{ $data }}">
            @foreach(collect($level3)->groupBy('type')->toArray() as $type => $lvl3)
                <tr class="bg-white">
                    <th colspan="3" class=" text-left  underline  px-2 py-1">
                        {{ $type }}
                    </th>
                </tr>
                @foreach($lvl3 as $l3)
                    <tr class="bg-white hover:bg-gray-100" >
                        <td class="px-2 py-1 whitespace-nowrap text-sm  flex text-gray-500">
                            <div @click="l3{{$l3['id']}} = ! l3{{$l3['id']}}" class="cursor-pointer">
                                <template x-if="! l3{{$l3['id']}}">
                                <svg   xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                </template>
                                <template x-if=" l3{{$l3['id']}}">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </template>
                            </div>
                            <div>
                                {{ $l3['name'] }}
                            </div>
                        </td>
                        <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                        </td>
                        <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-900 font-medium">
                            @if($l3['balance']>=0)
                                {{ number_format($l3['balance'],2) }}
                            @else
                            ({{ number_format(abs($l3['balance']),2) }})
                            @endif
                        </td>
                    </tr>
                    @foreach(collect($level4)->where('sub_account',$l3['id']) as $l4)
                        <tr class="bg-white hover:bg-gray-100" x-show="l3{{$l3['id']}}"
                            >
                            <td class="pl-12 px-2 py-1 whitespace-nowrap text-sm  text-gray-500 flex">
                                <div @click="l4{{$l4['id']}} = ! l4{{$l4['id']}}" class="cursor-pointer">
                                    <template x-if="! l4{{$l4['id']}}">
                                        <svg   xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                    <template x-if="l4{{$l4['id']}}">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                </div>
                                <div>
                                    {{ $l4['name'] }}
                                </div>
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                            </td>
                            <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500 ">
                                @if($l4['balance']>=0)
                                    {{ number_format($l4['balance'],2) }}
                                @else
                                ({{ number_format(abs($l4['balance']),2) }})
                                @endif
                            </td>
                        </tr>
                        @foreach(collect($level5)->where('sub_account',$l4['id']) as $l5)
                            <tr class="bg-white hover:bg-gray-100" x-show="l4{{$l4['id']}} && l3{{$l3['id']}}">
                                <td class="pl-24 px-2 py-1 whitespace-nowrap text-sm   text-gray-500">
                                    {{ $l5['name'] }}
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                                    @if($l5['balance']>=0)
                                        {{ number_format($l5['balance'],2) }}
                                    @else
                                    ({{ number_format(abs($l5['balance']),2) }})
                                    @endif
                                </td>
                                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">

                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                <tr class=" bg-white ">
                    <th colspan="2" class=" text-left align-top  pb-12  px-2 py-1">
                        Total {{ $type }}
                    </th>

                    <th class=" text-left   align-top   px-2 py-1">
                        @php
                            $total = collect($level3)->where('type',$type)->sum('balance');
                        @endphp
                        @if($total>=0)
                            {{ number_format($total,2) }}
                        @else
                        ({{ number_format(abs($total),2) }})
                        @endif
                    </th>
                </tr>
            @endforeach

            </tbody>
        </table>


    </div>
</div>

