@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="SelectVendor" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="vendorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="form-group px-2 pt-2 mb-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="vendor_query" id="vendorModalLabel" class="mb-1">Search Vendor</label>
                                <button type="button" wire:click="createVendor"
                                        onclick="window.dispatchEvent(new CustomEvent('close-vendor-modal'));window.dispatchEvent(new CustomEvent('open-vendor-create-modal'))"
                                        class="btn btn-link btn-sm p-0">
                                    + Create New Vendor
                                </button>
                            </div>
                            <input type="text"
                                   wire:model.debounce.500ms="vendor_query"
                                   wire:keydown.arrow-up="vendorDecrementHighlight"
                                   wire:keydown.arrow-down="vendorIncrementHighlight"
                                   wire:keydown.enter="vendorSelection"
                                   wire:keydown.escape="vendorReset"
                                   wire:keydown.tab="vendorReset"
                                   id="vendor_query"
                                   class="rounded vendor_query" style="width: 480px"
                                   autocomplete="off">
                        </div>

                        <div wire:loading.block wire:target="vendor_query" class="px-2 pb-2">
                            <p class="text-muted mb-0">Loading...</p>
                        </div>

                        <div wire:loading.remove wire:target="vendor_query">
                            @if(!empty($vendor_data))
                                <table class="table border-0 table-hover">
                                    <thead>
                                    <tr>
                                        @foreach($vendor_column as $c)
                                            <th scope="col" class="px-2 py-2 text-left">
                                                {{ ucwords(str_replace('_', ' ', $c)) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    @foreach($vendor_data as $key => $v)
                                        <tr style="cursor: pointer; {{ $vendor_highlight_index === $key ? 'background-color: #3d40e0; color: white;' : '' }}"
                                            onmouseover="this.style.backgroundColor='#3d40e0';this.style.color='#ffffff';"
                                            onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#000000';"
                                            wire:click="vendorSelection('{{ $key }}')">
                                            @foreach($vendor_column as $c)
                                                <td style="padding: 7px;border-top: none;">{{ $v[$c] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if(strlen($vendor_query) < 2)
                                    <p class="pt-0 px-2 text-muted">Please
                                        enter {{ 2 - strlen($vendor_query) }} or more
                                        {{ (2 - strlen($vendor_query)) > 1 ? 'characters' : 'character' }}</p>
                                @else
                                    <p class="pt-0 px-2 text-muted">No Record Found</p>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            window.addEventListener('open-vendor-modal', function () {
                $("#vendor_query").blur();
                $('#SelectVendor').modal('show');
                setTimeout(function () {
                    $("#vendor_query").focus();
                }, 500);
            })
            window.addEventListener('close-vendor-modal', function () {
                $('#SelectVendor').modal('hide');
            })
        </script>
    @endpush
@else
    <div x-data="{ open: false }" x-cloak x-show="open"
         @open-vendor-modal.window="open = true"
         @close-vendor-modal.window="open = false"
         class="fixed z-40 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div @click.away="open = false" x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 role="dialog" aria-modal="true" aria-labelledby="vendor-modal-headline">

                <div class="px-2 pt-2 pb-2">
                    <div class="flex items-center justify-between">
                        <label for="vendor_query" id="vendor-modal-headline"
                               class="block text-sm font-medium text-gray-600">Search Vendor</label>
                        <button type="button" wire:click="createVendor"
                                @click="$dispatch('close-vendor-modal'); $dispatch('open-vendor-create-modal')"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 focus:outline-none">
                            + Create New Vendor
                        </button>
                    </div>
                    <input type="text"
                           wire:model.debounce.500ms="vendor_query"
                           wire:keydown.arrow-up="vendorDecrementHighlight"
                           wire:keydown.arrow-down="vendorIncrementHighlight"
                           wire:keydown.enter="vendorSelection"
                           wire:keydown.escape="vendorReset"
                           wire:keydown.tab="vendorReset"
                           id="vendor_query"
                           class="shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           autocomplete="off">
                </div>

                <div wire:loading.block wire:target="vendor_query">
                    <p class="text-sm opacity-25 pt-0 p-3">Loading...</p>
                </div>

                <div wire:loading.remove wire:target="vendor_query" class="max-h-96 overflow-y-auto">
                    @if(!empty($vendor_data))
                        <table class="mt-3 min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                @foreach($vendor_column as $c)
                                    <th scope="col"
                                        class="px-2 py-2 text-left text-xs font-medium text-gray-500 tracking-wider">
                                        {{ ucwords(str_replace('_', ' ', $c)) }}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vendor_data as $key => $v)
                                <tr class="hover:bg-indigo-600 hover:text-white cursor-pointer {{ $vendor_highlight_index === $key ? 'bg-indigo-600 text-white' : 'text-gray-500' }}"
                                    wire:click="vendorSelection('{{ $key }}')">
                                    @foreach($vendor_column as $c)
                                        <td class="px-2 py-2 whitespace-nowrap text-sm">{{ $v[$c] }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        @if(strlen($vendor_query) < 2)
                            <p class="text-sm opacity-25 pt-0 p-3">Please enter {{ 2 - strlen($vendor_query) }}
                                or more
                                {{ (2 - strlen($vendor_query)) > 1 ? 'characters' : 'character' }}</p>
                        @else
                            <p class="text-sm opacity-25 pt-0 p-3">No Record Found</p>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
@endif
