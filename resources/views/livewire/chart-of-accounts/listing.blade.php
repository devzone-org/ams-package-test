@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="d-flex justify-content-between mx-2">
                                    <h4 class="card-title pt-2  mt-1"><b>Chart of Accounts</b></h4>
                                    <div class="col-xs-6 col-sm-3 mt-1">
                                        <div class="form-group">
                                            <select name="first_name" wire:model="type" id="first_name"
                                                    class="form-control">
                                                <option value="">All</option>
                                                <option value="Assets">Assets</option>
                                                <option value="Liabilities">Liabilities</option>
                                                <option value="Equity">Equity</option>
                                                <option value="Income">Income</option>
                                                <option value="Expenses">Expenses</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                    <a href="{{'chart-of-accounts/add'}}?type={{$type}}" target="_blank"
                                       class="btn btn-primary " style="height: 40px">
                                        Add
                                    </a>
                                    <a href="{{'chart-of-accounts/export'}}?type={{$type}}" target="_blank"
                                       class="btn btn-primary " style="height: 40px">
                                        Export.csv
                                    </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0 m-0">
                                <table class="table table-bordered border-0">
                                    <thead>
                                    <tr>
                                        <th class="text-muted border-0">Name</th>
                                        <th class="text-muted border-0">Code</th>
                                        <th class="text-muted border-0">Balance</th>
                                        <th class="text-muted border-0">Date</th>
                                        <th class="text-muted border-0">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coa->where('level','1') as $one)
                                        <tr>
                                            <td class="add-services-table align-middle text-muted"
                                                style="padding-left: 10px !important;" colspan="6">
                                                {!! str_repeat('&nbsp;', 4) !!}{{ $one->name }}
                                            </td>
                                        </tr>
                                        @foreach($coa->where('sub_account',$one->id) as $two)
                                            <tr>
                                                <td class="add-services-table align-middle text-muted" colspan="6">
                                                    {!! str_repeat('&nbsp;', 20) !!} {{ $two->name }}
                                                </td>
                                            </tr>
                                            @foreach($coa->where('sub_account',$two->id) as $three)
                                                <tr>
                                                    <td class="add-services-table align-middle text-muted" colspan="6">
                                                        {!! str_repeat('&nbsp;', 40) !!} {{ $three->name }}
                                                    </td>
                                                </tr>
                                                @foreach($coa->where('sub_account',$three->id) as $four)
                                                    <tr>
                                                        <td class="add-services-table align-middle text-muted"
                                                            colspan="6">
                                                            {!! str_repeat('&nbsp;', 60) !!} {{ $four->name }}
                                                        </td>
                                                    </tr>
                                                    @foreach($coa->where('sub_account',$four->id) as $five)
                                                        <tr class="{{ $five->status=='f'?'bg-danger':'' }}">
                                                            <td title="This is contra account"
                                                                class="add-services-table align-middle"
                                                                style="border-right: none">
                                                                <div class=" items-center">
                                                                    <span >{!! str_repeat('&nbsp;', 90) !!}</span>
                                                                    @if($five->is_contra == 't')
                                                                        <svg
                                                                                class="{{ $five->status == 'f' ? 'text-danger' : 'text-success' }}"
                                                                                fill="currentColor" viewBox="0 0 20 20"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill-rule="evenodd"
                                                                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                                  clip-rule="evenodd"></path>
                                                                        </svg>
                                                                    @endif
                                                                    <span > &nbsp;<b>{{ $five->name }}</b></span>

                                                                </div>
                                                            </td>
                                                            <td class="add-services-table align-middle col-1"
                                                                style="border-left: none;border-right: none">
                                                                <b>{{ $five->code }}</b></td>
                                                            <td class="add-services-table align-middle col-2"
                                                                style="border-left: none;border-right: none">
                                                                @if($one->name == 'Assets')
                                                                    @cannot('2.hide-assets')
                                                                        @php
                                                                            $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                                            if($clo<0){
                                                                                echo '<b>('.number_format(abs($clo),2).')</b>';
                                                                            } else {
                                                                                echo '<b>' . number_format(abs($clo),2) . '</b>';
                                                                            }
                                                                        @endphp
                                                                    @endcannot
                                                                @elseif($one->name == 'Liabilities')
                                                                    @cannot('2.hide-liabilities')
                                                                        @php
                                                                            $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                                            if($clo<0){
                                                                                echo '<b>('.number_format(abs($clo),2).')</b>';
                                                                            } else {
                                                                                echo '<b>' . number_format(abs($clo),2) . '</b>';
                                                                            }
                                                                        @endphp
                                                                    @endcannot
                                                                @elseif($one->name == 'Equity')
                                                                    @cannot('2.hide-equity')
                                                                        @php
                                                                            $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                                            if($clo<0){
                                                                                echo '<b>('.number_format(abs($clo),2).')</b>';
                                                                            } else {
                                                                                echo '<b>' . number_format(abs($clo),2) . '</b>';
                                                                            }
                                                                        @endphp
                                                                    @endcannot
                                                                @elseif($one->name == 'Income')
                                                                    @cannot('2.hide-income')
                                                                        @php
                                                                            $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                                            if($clo<0){
                                                                                echo '<b>('.number_format(abs($clo),2).')</b>';
                                                                            } else {
                                                                                echo '<b>' . number_format(abs($clo),2) . '</b>';
                                                                            }
                                                                        @endphp
                                                                    @endcannot
                                                                @elseif($one->name == 'Expenses')
                                                                    @cannot('2.hide-expenses')
                                                                        @php
                                                                            $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                                            if($clo<0){
                                                                                echo '<b>('.number_format(abs($clo),2).')</b>';
                                                                            } else {
                                                                                echo '<b>' . number_format(abs($clo),2) . '</b>';
                                                                            }
                                                                        @endphp
                                                                    @endcannot
                                                                @else
                                                                    @php
                                                                        $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                                        if($clo<0){
                                                                            echo '<b>('.number_format(abs($clo),2).')</b>';
                                                                        } else {
                                                                            echo '<b>' . number_format(abs($clo),2) . '</b>';
                                                                        }
                                                                    @endphp
                                                                @endif
                                                            </td>
                                                            <td class="add-services-table align-middle col-1"
                                                                style="border-left: none;border-right: none">
                                                                @if(!empty($five->posting_date))
                                                                    <b>{{date('d M, Y',strtotime($five->posting_date))}}</b>
                                                                @endif</td>

                                                            <td class="add-services-table align-middle"
                                                                style="border-left: none;width:50px">

                                                                <div class="nav-item dropdown">
                                                                    <div class="user-panel d-flex nav-link m-0 p-0 pr-2"
                                                                         data-toggle="dropdown"
                                                                         style="cursor: pointer; ">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             fill="none" viewBox="0 0 24 24"
                                                                             stroke-width="1.5" stroke="currentColor"
                                                                             class="w-6 h-6">
                                                                            <path stroke-linecap="round"
                                                                                  stroke-linejoin="round"
                                                                                  d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                                                        </svg>

                                                                    </div>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a href="{{ url('accounts/accountant/ledger') }}?account_id={{$five->id}}"
                                                                           class="dropdown-item" target="_blank">
                                                                            View Ledger
                                                                        </a>
                                                                        <a type="button"
                                                                           wire:click="changeStatus('{{ $five->id }}')"
                                                                           class="text-danger mx-3"> Mark Inactive </a>
                                                                    </div>
                                                                </div>


                                                                {{--                                                                <a--}}
                                                                {{--                                                                        href="{{ url('accounts/accountant/ledger') }}?account_id={{$five->id}}"--}}
                                                                {{--                                                                        target="_blank"--}}
                                                                {{--                                                                        class="btn btn-success btn-sm"> View Ledger </a>--}}
                                                                {{--                                                                <button type="button"--}}
                                                                {{--                                                                        wire:click="changeStatus('{{ $five->id }}')"--}}
                                                                {{--                                                                        class="btn btn-primary btn-sm"--}}
                                                                {{--                                                                >--}}
                                                                {{--                                                                    Mark Inactive--}}
                                                                {{--                                                                </button>--}}
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
                </div>
            </div>
        </div>
    </div>
@else
    <div class="shadow sm:rounded-md">
        <div class="bg-white rounded-md">
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
                    <a href="{{'chart-of-accounts/export'}}?type={{$type}}" target="_blank"
                       class="ml-3 disabled:opacity-30 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none ">
                        Export.csv
                    </a>
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
                                    <tr class="{{ $five->status=='f'?'bg-red-200':'' }}">
                                        <td title="This is contra account"
                                            class="px-3 py-2  whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex flex-wrap content-center items-center">
                                                <span>{!! str_repeat('&nbsp;', 24) !!}</span>

                                                @if($five->is_contra == 't')
                                                    <svg
                                                            class="w-4 h-4 {{ $five->status=='f'?'text-red-600':'text-green-500' }}"
                                                            fill="currentColor" viewBox="0 0 20 20"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                              clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                <span> &nbsp;{{ $five->name }}</span>
                                            </div>
                                        </td>

                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $five->code }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            @if($one->name == 'Assets')
                                                @cannot('2.hide-assets')
                                                    @php
                                                        $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                        if($clo<0){
                                                            echo '('.number_format(abs($clo),2).')';
                                                        } else {
                                                            echo number_format(abs($clo),2);
                                                        }
                                                    @endphp
                                                @endcannot
                                            @elseif($one->name == 'Liabilities')
                                                @cannot('2.hide-liabilities')
                                                    @php
                                                        $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                        if($clo<0){
                                                            echo '('.number_format(abs($clo),2).')';
                                                        } else {
                                                            echo number_format(abs($clo),2);
                                                        }
                                                    @endphp
                                                @endcannot
                                            @elseif($one->name == 'Equity')
                                                @cannot('2.hide-equity')
                                                    @php
                                                        $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                        if($clo<0){
                                                            echo '('.number_format(abs($clo),2).')';
                                                        } else {
                                                            echo number_format(abs($clo),2);
                                                        }
                                                    @endphp
                                                @endcannot
                                            @elseif($one->name == 'Income')
                                                @cannot('2.hide-income')
                                                    @php
                                                        $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                        if($clo<0){
                                                            echo '('.number_format(abs($clo),2).')';
                                                        } else {
                                                            echo number_format(abs($clo),2);
                                                        }
                                                    @endphp
                                                @endcannot
                                            @elseif($one->name == 'Expenses')
                                                @cannot('2.hide-expenses')
                                                    @php
                                                        $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                        if($clo<0){
                                                            echo '('.number_format(abs($clo),2).')';
                                                        } else {
                                                            echo number_format(abs($clo),2);
                                                        }
                                                    @endphp
                                                @endcannot
                                            @else
                                                @php
                                                    $clo = (\Devzone\Ams\Helper\GeneralJournal::closingBalance($five->nature,$five->is_contra,$five->debit,$five->credit));
                                                    if($clo<0){
                                                        echo '('.number_format(abs($clo),2).')';
                                                    } else {
                                                        echo number_format(abs($clo),2);
                                                    }
                                                @endphp
                                            @endif


                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            @if(!empty($five->posting_date))
                                                {{date('d M, Y',strtotime($five->posting_date))}}
                                            @endif</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">


                                            <div class="relative inline-block text-left" x-data="{open:false}">
                                                <div class="pt-0 pl-0">
                                                    <svg class="w-6 h-6 cursor-pointer" @click="open = true;"
                                                         fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                                    </svg>
                                                </div>

                                                <div @click.away="open=false;" x-show="open"
                                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 focus:outline-none"
                                                     role="menu" aria-orientation="vertical"
                                                     aria-labelledby="menu-button"
                                                     tabindex="-1">
                                                    <div class="" role="none">

                                                        <a @click="open = false;"
                                                           href="{{ url('accounts/accountant/ledger') }}?account_id={{$five->id}}"
                                                           target="_blank"
                                                           class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                                           role="menuitem" tabindex="-1"
                                                           id="menu-item-0"> View Ledger </a>

                                                        <button type="button"

                                                                wire:click="changeStatus('{{ $five->id }}')"
                                                                class="text-red-700 block  px-4 py-2 text-sm hover:bg-red-100 rounded-b-md"
                                                                role="menuitem" tabindex="-1"
                                                                id="menu-item-0">
                                                        <span wire:loading.remove wire:target="changeStatus">
                                                        @if($five->status=='f')
                                                                Make Active
                                                            @else
                                                                Make Inactive
                                                            @endif
                                                        </span>
                                                            <span wire:loading wire:target="changeStatus">
                                                            Processing...
                                                        </span>
                                                        </button>


                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <p class="">&nbsp;</p>

        </div>

        <div x-data="{ open: @entangle('confirm') }" x-cloak x-show="open"
             class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-description="Background overlay, show/hide based on modal state."
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                <div x-show="open" x-description="Modal panel, show/hide based on modal state."
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg  text-left
                                    overflow-hidden shadow-xl transform transition-all
                                    sm:my-8 sm:align-middle sm:max-w-xl sm:w-full  "
                     role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="px-4 py-5 sm:px-6" @click.away="open = false;">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Attention
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Are you sure you want to perform this action?
                        </p>
                        <button type="button"
                                wire:loading.attr="disabled"
                                wire:click="changeStatusConfirm"
                                class="inline-flex items-center mt-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                role="menuitem" tabindex="-1"
                                id="menu-item-0">
                                                        <span wire:loading.remove wire:target="changeStatusConfirm">
                                                         Proceed
                                                        </span>
                            <span wire:loading wire:target="changeStatusConfirm">
                                                            Processing...
                                                        </span>
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endif
