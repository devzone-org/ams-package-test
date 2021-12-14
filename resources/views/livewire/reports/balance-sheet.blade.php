<div class="  max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">


            <div>
                <h3 class="text-lg leading-6 text-center font-medium text-gray-900">Balance Sheet Report</h3>
                <p class="text-md  font-sm text-gray-500 text-center">{{ env('APP_NAME') }}</p>
                <p class="text-md  font-sm text-gray-500 text-center">Statement
                    Period as at {{ date('d M, Y') }} </p>
            </div>
        </div>


        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Account
                </th>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Level
                </th>
                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total
                </th>

            </tr>
            </thead>
            <tbody x-data="{{ $data }}">
             @foreach($level3 as $l3)
                 <tr class="bg-white hover:bg-gray-100"  @click="l3{{$l3['id']}} = ! l3{{$l3['id']}}">
                     <td class="px-2 py-1 whitespace-nowrap text-sm   text-gray-500">
                         {{ $l3['name'] }}
                     </td>
                     <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                         {{ $l3['level'] }}
                     </td>
                     <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                         {{ number_format($l3['balance'],2) }}
                     </td>
                 </tr>
                 @foreach(collect($level4)->where('sub_account',$l3['id']) as $l4)
                     <tr class="bg-white hover:bg-gray-100" x-show="l3{{$l3['id']}}"  @click="l4{{$l4['id']}} = ! l4{{$l4['id']}}">
                         <td  class="pl-6 px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900">
                             {{ $l4['name'] }}
                         </td>
                         <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                             {{ $l4['level'] }}
                         </td>
                         <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                             {{ number_format($l4['balance'],2) }}
                         </td>
                     </tr>
                     @foreach(collect($level5)->where('sub_account',$l4['id']) as $l5)
                         <tr class="bg-white hover:bg-gray-100" x-show="l4{{$l4['id']}}">
                             <td class="pl-12 px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900">
                                 {{ $l5['name'] }}
                             </td>
                             <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                                 {{ $l5['level'] }}
                             </td>
                             <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-500">
                                 {{ number_format($l5['balance'],2) }}
                             </td>
                         </tr>
                     @endforeach
                 @endforeach
             @endforeach

            </tbody>
        </table>


    </div>
</div>

