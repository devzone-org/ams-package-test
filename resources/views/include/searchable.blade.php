@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="SelectAccount" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0" id="std_form">
                        <div>
                            <div class="form-group px-2 mb-1">
                                <label for="status"
                                       class="">Search {{ ucwords(str_replace('_', ' ', $searchable_type))  }}</label>
                                <input type="text"
                                       wire:model.debounce.500ms="searchable_query"
                                       wire:keydown.arrow-up="decrementHighlight"
                                       wire:keydown.arrow-down="incrementHighlight"
                                       wire:keydown.enter="searchableSelection"
                                       wire:keydown.escape="searchableReset"
                                       wire:keydown.tab="searchableReset"
                                       id="searchable_query"
                                       class="rounded searchable_query" style="width: 480px"
                                       autocomplete="off">
                            </div>
                            <div>
                                @if(!empty($searchable_data))
                                    <table class="table border-0 table-hover">
                                        <thead class="">
                                        <tr>
                                            @foreach($searchable_column[$searchable_type] as $c)
                                                @if($c == 'name')
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left">
                                                        {{ ucwords($c) }}
                                                    </th>
                                                @else
                                                    <th scope="col"
                                                        class=" col-1 px-2 py-2 text-left">
                                                        {{ ucwords($c) }}
                                                    </th>
                                                @endif

                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                        @foreach($searchable_data as $key=> $a)
                                            <tr class=""
                                                style="cursor: pointer; {{$highlight_index === $key ? 'background-color: #3d40e0; color: white;' : '' }}"
                                                onmouseover="this.style.backgroundColor='#3d40e0';this.style.color='#ffffff';"
                                                onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#000000';"
                                                wire:click="searchableSelection('{{ $key }}')">
                                                @foreach($searchable_column[$searchable_type] as $c)
                                                    <td style="padding: 7px;border-top: none;">
                                                        {{ $a[$c] }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    @if(strlen($searchable_query) < 2)
                                        <p class=" pt-0  px-2 text-muted">Please
                                            enter {{ 2 - strlen($searchable_query) }}
                                            or more
                                            {{ (2 - strlen($searchable_query)) > 1 ? 'characters' : 'character' }}</p>
                                    @else
                                        <p class="pt-0 px-2 text-muted">{{ empty($searchable_data) ? 'No Record Found': '' }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('js')
        <script>

            document.addEventListener('open-modal', function () {
                $("#searchable_query").blur();
                $('#SelectAccount').modal('show');
                setTimeout(function () {
                    $("#searchable_query").focus();
                }, 500);
            })
            document.addEventListener('close-modal', function () {
                $('#SelectAccount').modal('hide');
            })
        </script>
    @endpush
@else
    <div x-data="{ open: @entangle('searchable_modal') }" x-cloak x-show="open"
         class="fixed z-40 inset-0 overflow-y-auto">
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
            <div @click.away="open = false;" x-show="open" x-description="Modal panel, show/hide based on modal state."
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="h-1/3 inline-block align-bottom bg-white rounded-lg  text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full  "
                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="  px-2 pt-2 pb-2">


                    <div class="">
                        <label for="status"
                               class="block text-sm font-medium text-gray-600">Search {{ ucwords(str_replace('_', ' ', $searchable_type))  }}</label>
                        <input type="text"
                               wire:model.debounce.500ms="searchable_query"
                               wire:keydown.arrow-up="decrementHighlight"
                               wire:keydown.arrow-down="incrementHighlight"
                               wire:keydown.enter="searchableSelection"
                               wire:keydown.escape="searchableReset"
                               wire:keydown.tab="searchableReset"
                               id="searchable_query"
                               class="shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               autocomplete="off">


                    </div>


                </div>

                @if(!empty($searchable_data))
                    <table class="mt-3 min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            @foreach($searchable_column[$searchable_type] as $c)
                                <th scope="col"
                                    class="px-2 py-2 text-left text-xs font-medium text-gray-500   tracking-wider">
                                    {{ ucwords($c) }}
                                </th>
                            @endforeach


                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($searchable_data as $key=> $a)
                            <tr class="hover:bg-indigo-600 hover:text-white cursor-pointer  {{ $highlight_index === $key ? 'bg-indigo-600 text-white' : ' text-gray-500' }}"
                                wire:click="searchableSelection('{{ $key }}')">
                                @foreach($searchable_column[$searchable_type] as $c)
                                    <td class="px-2 py-2 whitespace-nowrap text-sm ">
                                        {{ $a[$c] }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    @if(strlen($searchable_query) < 2)
                        <p class="text-sm opacity-25 pt-0 p-3 ">Please enter {{ 2 - strlen($searchable_query) }}
                            or more
                            {{ (2 - strlen($searchable_query)) > 1 ? 'characters' : 'character' }}</p>
                    @else
                        <p class="text-sm opacity-25 pt-0 p-3">{{ empty($searchable_data) ? 'No Record Found': '' }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endif

