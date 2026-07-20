@if(env('AMS_BOOTSTRAP') == 'true')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h1>{{ $is_view ? "View" : ($is_edit ? "Update" : "Add") }} Admin Expense</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><b>{{ $is_view ? "View" : ($is_edit ? "Update" : "Add") }} Admin Expenses</b></h3>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="save">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Expense On Date<span
                                                            class="text-danger">*</span> </label>
                                                <input type="date" wire:model.defer="admin_expenses.expense_date" @if($is_view) disabled @endif
                                                       autocomplete="off"
                                                       class="form-control @error('admin_expenses.expense_date')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4" wire:key="vendor-name">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Vendor <span
                                                            class="text-danger">*</span></label>
                                                <input type="text" readonly autocomplete="off"
                                                       @if(!$is_view) onclick="window.dispatchEvent(new CustomEvent('open-vendor-modal'))"
                                                       wire:click="vendorOpenModal('admin_expenses.vendor_id','admin_expenses.vendor_name')" @endif
                                                       wire:model="admin_expenses.vendor_name"
                                                       class="form-control @error('admin_expenses.vendor_id')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Invoice # </label>
                                                <input type="text" wire:model.defer="admin_expenses.invoice_no" @if($is_view) disabled @endif
                                                       autocomplete="off"
                                                       class="form-control @error('admin_expenses.invoice_no')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Amount <span
                                                            class="text-danger">*</span></label>
                                                <input type="number" step="0.1"
                                                       wire:model.defer="admin_expenses.amount" @if($is_view) disabled @endif autocomplete="off"
                                                       class="form-control @error('admin_expenses.amount')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4" wire:key="expense-account-name">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Expense on A/C Of <span
                                                            class="text-danger">*</span></label>
                                                <input type="text" readonly autocomplete="off"
                                                       @if(!$is_view) onclick="$('#SelectAccount').modal('show')"
                                                       wire:click="searchableOpenModal('admin_expenses.expense_account_id','admin_expenses.expense_account_name','accounts')" @endif
                                                       wire:model="admin_expenses.expense_account_name"
                                                       class="form-control @error('admin_expenses.expense_account_id')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Requisite By </label>
                                                <select wire:model.defer="admin_expenses.requisite_by" @if($is_view) disabled @endif
                                                        class="form-control @error('admin_expenses.requisite_by')  is-invalid @enderror">
                                                    <option value=""></option>
                                                    @foreach($fetch_users as $u)
                                                        <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Attachment </label>
                                                <input type="file" wire:model.defer="attachment" @if($is_view) disabled @endif autocomplete="off"
                                                       class="form-control p-0 m-0 pt-1 px-1">
                                            </div>
                                        </div>

                                        <div class="col-12 pt-3">
                                            <div class="form-group">
                                                <label class="font-weight-normal">Description </label>
                                                <textarea wire:model.defer="admin_expenses.description" @if($is_view) disabled @endif
                                                          autocomplete="off" rows="5"
                                                          class="form-control @error('admin_expenses.description')  is-invalid @enderror"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 pt-3">
                                            <div class="form-group d-flex justify-content-end">
                                                @if(!$is_view)
                                                    <button type="submit" wire:loading.attr="disabled"
                                                            class="btn btn-success mx-1">
                                                        <span wire:loading.remove wire:target="save">{{ $is_edit ? 'Update' : 'Save' }}</span>
                                                        <span wire:loading wire:target="save">{{ $is_edit ? 'Updating...' : 'Saving...' }}</span>
                                                    </button>

                                                    <button type="button" wire:click="clear"
                                                            wire:loading.attr="disabled"
                                                            class="btn btn-danger mx-1">
                                                        <span wire:loading.remove wire:target="clear">Reset</span>
                                                        <span wire:loading wire:target="clear">Resetting...</span>
                                                    </button>
                                                @endif
                                                <a href="{{url('/accounts/admin-expenses/list')}}"
                                                   class="btn btn-secondary mx-1">
                                                    Go Back
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include("ams::include.searchable")
        @include("ams::include.vendor-searchable")
        @include("ams::livewire.admin-expenses.vendor-create-modal")
    </div>
@else
    <div>
        <div class="mb-54 shadow sm:rounded-md sm:overflow-hidden bg-white">
            @if ($errors->any())
                <div class="px-6 pt-6">
                    <div class="p-4 rounded-md bg-red-50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    There {{ $errors->count() > 1? 'were' : 'was' }} {{ $errors->count() }} {{
                                $errors->count() > 1? 'errors' : 'error' }}
                                    with your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="pl-5 space-y-1 list-disc">
                                        @foreach ($errors->all() as $error)
                                            <li>{!! $error !!}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($success) || session()->has('success'))
                <div class="px-6 pt-6">
                    <div class="p-4  rounded-md bg-green-50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Heroicon name: check-circle -->
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    @if(!empty($success))
                                        {{ $success }}
                                    @elseif(session()->has('success'))
                                        {{ session('success') }}
                                    @endif
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" wire:click="$set('success', '')"
                                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                        <span class="sr-only">Dismiss</span>
                                        <!-- Heroicon name: x -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20"
                                             fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="py-6 px-4 sm:p-6 flex justify-between border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">{{ $is_view ? 'View' : ($is_edit ? 'Update' : 'Add') }}
                    Admin Expenses</h3>
            </div>
            <form wire:submit.prevent="save">
                <div class="py-6 px-4 space-y-6 sm:p-6">
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Expense On Date <span
                                        class="text-red-500">*</span></label>
                            <input type="date" wire:model.defer="admin_expenses.expense_date" @if($is_view) disabled @endif autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1" wire:key="vendor-name" x-data="{}">
                            <label class="block text-sm font-medium text-gray-700">Vendor <span
                                        class="text-red-500">*</span></label>
                            <input type="text" readonly autocomplete="off"
                                   @if(!$is_view) @click="$dispatch('open-vendor-modal')"
                                   wire:click="vendorOpenModal('admin_expenses.vendor_id','admin_expenses.vendor_name')" @endif
                                   wire:model="admin_expenses.vendor_name"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Invoice # </label>
                            <input type="text" wire:model.defer="admin_expenses.invoice_no" @if($is_view) disabled @endif autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Amount <span
                                        class="text-red-500">*</span></label>
                            <input type="number" step="0.1" wire:model.defer="admin_expenses.amount" @if($is_view) disabled @endif autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1" wire:key="expense-account-name" x-data="{}">
                            <label class="block text-sm font-medium text-gray-700">Expense on A/C Of <span
                                        class="text-red-500">*</span></label>
                            <input type="text" readonly autocomplete="off"
                                   @if(!$is_view) @click="$dispatch('open-modal')"
                                   wire:click="searchableOpenModal('admin_expenses.expense_account_id','admin_expenses.expense_account_name','accounts')" @endif
                                   wire:model="admin_expenses.expense_account_name"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Requisite By </label>
                            <select wire:model.defer="admin_expenses.requisite_by" @if($is_view) disabled @endif
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                @foreach($fetch_users as $u)
                                    <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Attachment </label>
                            <input type="file" wire:model.defer="attachment" @if($is_view) disabled @endif autocomplete="off"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="col-span-6">
                        <label class="block text-sm font-medium text-gray-700">Description </label>
                        <textarea wire:model.defer="admin_expenses.description" @if($is_view) disabled @endif autocomplete="off" rows="5"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="w-full flex justify-end">
                        <div>
                            @if(!$is_view)
                                <button type="submit" wire:loading.attr="disabled"
                                        class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span wire:loading.remove wire:target="save">{{ $is_edit ? 'Update' : 'Save' }}</span>
                                    <span wire:loading wire:target="save">{{ $is_edit ? 'Updating...' : 'Saving...' }}</span>
                                </button>

                                <button type="button" wire:click="clear" wire:loading.attr="disabled"
                                        class="ml-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <span wire:loading.remove wire:target="clear">Reset</span>
                                    <span wire:loading wire:target="clear">Resetting...</span>
                                </button>
                            @endif
                            <a href="{{url('/accounts/admin-expenses/list')}}"
                               class="ml-1 inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm disabled:opacity-25 hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Go Back
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include("ams::include.searchable")
        @include("ams::include.vendor-searchable")
        @include("ams::livewire.admin-expenses.vendor-create-modal")
        <script>
            document.addEventListener('livewire:load', () => {
                Livewire.on('focusInput', postId => {
                    setTimeout(() => {
                        document.getElementById('searchable_query').focus();
                    }, 300);
                });
            });
        </script>
    </div>
@endif
