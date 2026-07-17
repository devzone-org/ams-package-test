@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="SelectVendor" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="vendorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header py-2">
                        @if($vendor_create)
                            <button type="button" wire:click="$set('vendor_create', false)"
                                    class="btn btn-link btn-sm p-0 mr-2 text-secondary" title="Back to list">
                                &larr;
                            </button>
                        @endif
                        <h5 class="modal-title" id="vendorModalLabel">
                            {{ $vendor_create ? 'New Vendor' : 'Select Vendor' }}
                        </h5>
                        <button type="button" class="close" wire:click="closeVendorModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        @if(!$vendor_create)
                            <div class="p-2 border-bottom">
                                <input type="text" wire:model.debounce.500ms="vendor_search" id="vendor_search"
                                       class="form-control form-control-sm"
                                       placeholder="Search by business, owner or contact no." autocomplete="off">
                            </div>

                            <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                                @forelse($vendors as $v)
                                    @php
                                        $meta = array_filter([$v['owner_name'] ?? null, $v['business_address'] ?? null]);
                                    @endphp
                                    <a href="javascript:void(0)" wire:key="vendor-{{ $v['id'] }}"
                                       wire:click="selectVendor('{{ $v['id'] }}')"
                                       class="list-group-item list-group-item-action py-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="text-truncate pr-2">
                                                <div class="font-weight-bold text-truncate">{{ $v['business_name'] }}</div>
                                                <small class="text-muted text-truncate d-block">
                                                    {{ !empty($meta) ? implode(' · ', $meta) : 'No further details' }}
                                                </small>
                                            </div>
                                            <small class="text-muted text-nowrap">{{ $v['contact_no'] }}</small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center">
                                        <p class="text-muted mb-0">
                                            @if(empty($vendor_search))
                                                No vendors have been added yet.
                                            @else
                                                No vendors match "{{ $vendor_search }}".
                                            @endif
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="p-3">
                                <div class="form-group mb-2">
                                    <label class="font-weight-normal mb-1">Business Name <span
                                                class="text-danger">*</span></label>
                                    <input type="text" wire:model.lazy="new_vendor.business_name" autocomplete="off"
                                           class="form-control form-control-sm @error('new_vendor.business_name') is-invalid @enderror">
                                    @error('new_vendor.business_name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label class="font-weight-normal mb-1">Business Address</label>
                                    <input type="text" wire:model.lazy="new_vendor.business_address"
                                           autocomplete="off"
                                           class="form-control form-control-sm @error('new_vendor.business_address') is-invalid @enderror">
                                    @error('new_vendor.business_address')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-normal mb-1">Contact No.</label>
                                            <input type="text" wire:model.lazy="new_vendor.contact_no"
                                                   autocomplete="off"
                                                   class="form-control form-control-sm @error('new_vendor.contact_no') is-invalid @enderror">
                                            @error('new_vendor.contact_no')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-normal mb-1">Owner Name</label>
                                            <input type="text" wire:model.lazy="new_vendor.owner_name"
                                                   autocomplete="off"
                                                   class="form-control form-control-sm @error('new_vendor.owner_name') is-invalid @enderror">
                                            @error('new_vendor.owner_name')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer py-2">
                        @if(!$vendor_create)
                            <button type="button" wire:click="createVendor"
                                    class="btn btn-sm btn-outline-primary btn-block m-0">
                                + Create New Vendor
                            </button>
                        @else
                            <button type="button" wire:click="$set('vendor_create', false)"
                                    class="btn btn-sm btn-light">
                                Cancel
                            </button>
                            <button type="button" wire:click="saveVendor" wire:loading.attr="disabled"
                                    class="btn btn-sm btn-primary">
                                Save Vendor
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            document.addEventListener('open-vendor-modal', function () {
                $('#SelectVendor').modal('show');
                setTimeout(function () {
                    $("#vendor_search").focus();
                }, 500);
            })
            document.addEventListener('close-vendor-modal', function () {
                $('#SelectVendor').modal('hide');
            })
        </script>
    @endpush
@else
    <div x-data="{ open: @entangle('vendor_modal') }" x-cloak x-show="open"
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
                 class="inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg"
                 role="dialog" aria-modal="true" aria-labelledby="vendor-modal-headline">

                {{-- Header --}}
                <div class="flex items-center px-4 py-3 border-b border-gray-200">
                    @if($vendor_create)
                        <button type="button" wire:click="$set('vendor_create', false)"
                                class="mr-2 -ml-1 p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span class="sr-only">Back to list</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @endif
                    <h3 class="flex-1 text-base font-medium text-gray-900" id="vendor-modal-headline">
                        {{ $vendor_create ? 'New Vendor' : 'Select Vendor' }}
                    </h3>
                    <button type="button" wire:click="closeVendorModal"
                            class="-mr-1 p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                @if(!$vendor_create)
                    {{-- Search --}}
                    <div class="px-4 py-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input type="text" wire:model.debounce.500ms="vendor_search" id="vendor_search"
                                   placeholder="Search by business, owner or contact no."
                                   class="shadow-sm block w-full pl-9 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                   autocomplete="off">
                        </div>
                    </div>

                    {{-- Results --}}
                    <div class="max-h-96 overflow-y-auto border-t border-gray-200">
                        @forelse($vendors as $v)
                            @php
                                $meta = array_filter([$v['owner_name'] ?? null, $v['business_address'] ?? null]);
                            @endphp
                            <div wire:key="vendor-{{ $v['id'] }}" wire:click="selectVendor('{{ $v['id'] }}')"
                                 class="group flex items-start justify-between px-4 py-3 border-b border-gray-100 cursor-pointer hover:bg-indigo-50">
                                <div class="min-w-0 pr-3">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-indigo-700">
                                        {{ $v['business_name'] }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-gray-500 truncate">
                                        {{ !empty($meta) ? implode(' · ', $meta) : 'No further details' }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500 whitespace-nowrap">{{ $v['contact_no'] }}</span>
                            </div>
                        @empty
                            <div class="px-4 py-10 text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">
                                    @if(empty($vendor_search))
                                        No vendors have been added yet.
                                    @else
                                        No vendors match &ldquo;{{ $vendor_search }}&rdquo;.
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Footer --}}
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <button type="button" wire:click="createVendor"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Create New Vendor
                        </button>
                    </div>
                @else
                    {{-- Create form --}}
                    <div class="px-4 py-4 space-y-3">
                        <div>
                            <label for="new_business_name" class="block text-sm font-medium text-gray-700">
                                Business Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="new_business_name" wire:model.lazy="new_vendor.business_name"
                                   autocomplete="off"
                                   class="shadow-sm mt-1 block w-full sm:text-sm rounded-md @error('new_vendor.business_name') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @enderror">
                            @error('new_vendor.business_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_business_address" class="block text-sm font-medium text-gray-700">
                                Business Address
                            </label>
                            <input type="text" id="new_business_address" wire:model.lazy="new_vendor.business_address"
                                   autocomplete="off"
                                   class="shadow-sm mt-1 block w-full sm:text-sm rounded-md @error('new_vendor.business_address') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @enderror">
                            @error('new_vendor.business_address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="new_contact_no" class="block text-sm font-medium text-gray-700">
                                    Contact No.
                                </label>
                                <input type="text" id="new_contact_no" wire:model.lazy="new_vendor.contact_no"
                                       autocomplete="off"
                                       class="shadow-sm mt-1 block w-full sm:text-sm rounded-md @error('new_vendor.contact_no') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @enderror">
                                @error('new_vendor.contact_no')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="new_owner_name" class="block text-sm font-medium text-gray-700">
                                    Owner Name
                                </label>
                                <input type="text" id="new_owner_name" wire:model.lazy="new_vendor.owner_name"
                                       autocomplete="off"
                                       class="shadow-sm mt-1 block w-full sm:text-sm rounded-md @error('new_vendor.owner_name') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @enderror">
                                @error('new_vendor.owner_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button type="button" wire:click="$set('vendor_create', false)"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="button" wire:click="saveVendor" wire:loading.attr="disabled"
                                class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Vendor
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
