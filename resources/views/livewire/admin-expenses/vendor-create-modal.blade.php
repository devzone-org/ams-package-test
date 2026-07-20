@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="CreateVendor" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="vendorCreateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="vendorCreateLabel">New Vendor</h5>
                        <button type="button" class="close" wire:click.prevent="closeVendorCreate"
                                onclick="$('#CreateVendor').modal('hide')" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label class="font-weight-normal mb-1">Business Name <span
                                        class="text-danger">*</span></label>
                            <input type="text" wire:model.lazy="new_vendor.business_name" autocomplete="off"
                                   class="form-control form-control-sm @error('new_vendor.business_name') is-invalid @enderror">
                            @error('new_vendor.business_name')
                            <span class="invalid-feedback d-block">{!! $message !!}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-normal mb-1">Business Address</label>
                            <input type="text" wire:model.lazy="new_vendor.business_address" autocomplete="off"
                                   class="form-control form-control-sm @error('new_vendor.business_address') is-invalid @enderror">
                            @error('new_vendor.business_address')
                            <span class="invalid-feedback d-block">{!! $message !!}</span>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-normal mb-1">Contact No.</label>
                                    <input type="text" wire:model.lazy="new_vendor.contact_no" autocomplete="off"
                                           class="form-control form-control-sm @error('new_vendor.contact_no') is-invalid @enderror">
                                    @error('new_vendor.contact_no')
                                    <span class="invalid-feedback d-block">{!! $message !!}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-normal mb-1">Owner Name</label>
                                    <input type="text" wire:model.lazy="new_vendor.owner_name" autocomplete="off"
                                           class="form-control form-control-sm @error('new_vendor.owner_name') is-invalid @enderror">
                                    @error('new_vendor.owner_name')
                                    <span class="invalid-feedback d-block">{!! $message !!}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" wire:click.prevent="closeVendorCreate"
                                onclick="$('#CreateVendor').modal('hide')" class="btn btn-sm btn-light">
                            Cancel
                        </button>
                        <button type="button" wire:click="saveVendor" wire:loading.attr="disabled"
                                class="btn btn-sm btn-primary">
                            <span wire:loading.remove wire:target="saveVendor">Save Vendor</span>
                            <span wire:loading wire:target="saveVendor">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            window.addEventListener('open-vendor-create-modal', function () {
                $('#CreateVendor').modal('show');
            })
            window.addEventListener('close-vendor-create-modal', function () {
                $('#CreateVendor').modal('hide');
            })
        </script>
    @endpush
@else
    <div x-data="{ open: false }" x-cloak x-show="open"
         @open-vendor-create-modal.window="open = true"
         @close-vendor-create-modal.window="open = false"
         class="fixed z-40 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 role="dialog" aria-modal="true" aria-labelledby="vendor-create-headline">

                <div class="flex items-center px-4 py-3 border-b border-gray-200">
                    <h3 class="flex-1 text-base font-medium text-gray-900" id="vendor-create-headline">
                        New Vendor
                    </h3>
                    <button type="button" wire:click="closeVendorCreate" @click="open = false"
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

                <div class="px-4 py-4 space-y-3">
                    <div>
                        <label for="new_business_name" class="block text-sm font-medium text-gray-700">
                            Business Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="new_business_name" wire:model.lazy="new_vendor.business_name"
                               autocomplete="off"
                               class="shadow-sm mt-1 block w-full sm:text-sm rounded-md @error('new_vendor.business_name') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @enderror">
                        @error('new_vendor.business_name')
                        <p class="mt-1 text-xs text-red-600">{!! $message !!}</p>
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
                        <p class="mt-1 text-xs text-red-600">{!! $message !!}</p>
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
                            <p class="mt-1 text-xs text-red-600">{!! $message !!}</p>
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
                            <p class="mt-1 text-xs text-red-600">{!! $message !!}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="button" wire:click="closeVendorCreate" @click="open = false"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveVendor" wire:loading.attr="disabled"
                            class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span wire:loading.remove wire:target="saveVendor">Save Vendor</span>
                        <span wire:loading wire:target="saveVendor">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
