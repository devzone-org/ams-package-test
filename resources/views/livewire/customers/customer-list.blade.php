<div wire:init="checkForRedirect()">
    @if(!$redirect_back)
        <div class="pb-5 border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                <span class="ml-4">Customer List</span>
            </h3>
        </div>

    @can('4.add.customers')
        <form wire:submit.prevent="create">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Add Customer</h3>
                    </div>
                    @include('include.alert')

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Customer Name<span class="text-red-500">*</span></label>
                            <input wire:model.defer="customer_data.name" type="text" autocomplete="off"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Customer Email</label>
                            <input wire:model.defer="customer_data.email" type="text" autocomplete="off"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Mobile #<span class="text-red-500">*</span></label>
                            <input wire:model.defer="customer_data.mobile_no" type="number" autocomplete="off"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Phone #</label>
                            <input wire:model.defer="customer_data.phone" type="number" autocomplete="off"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input wire:model.defer="customer_data.city" type="text" autocomplete="off"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model.defer="customer_data.status"
                                    class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value=""></option>
                                <option value="Active">Active</option>
                                <option value="In-Active">In-Active</option>
                                <option value="Opportunity">Opportunity</option>
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input wire:model.defer="customer_data.address" type="text" autocomplete="off"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>



                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit"
                            class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        Add
                    </button>
                </div>
            </div>
        </form>
    @endcan
        <div class="mt-5 shadow sm:rounded-md sm:overflow-hidden">
            <!-- This example requires Tailwind CSS v2.0+ -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Customers</h3>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 text-left text-sm font-medium text-gray-900">
                                            #
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-sm font-medium text-gray-900">
                                            Name
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-sm font-medium text-gray-900">
                                            Mobile #
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-sm font-medium text-gray-900">
                                            Email
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-sm font-medium text-gray-900">
                                            Status
                                        </th>
                                        <th scope="col" class="relative px-3 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customers as $c)
                                        <tr>
                                            <td class="px-3 py-3 text-sm font-medium text-gray-500">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-3 py-3 text-sm font-medium text-gray-500">
                                                {{ $c->name }}
                                            </td>
                                            <td class="px-3 py-3 text-sm font-medium text-gray-500">
                                                {{ $c->mobile_no }}
                                            </td>
                                            <td class="px-3 py-3 text-sm font-medium text-gray-500">
                                                {{ $c->email }}
                                            </td>
                                            <td class="px-3 py-3 text-sm font-medium text-gray-500">
                                                {{ $c->status }}
                                            </td>
                                            <td class="px-3 py-3 text-right text-sm font-medium">
                                                @can('4.edit.customers')
                                                    <p wire:click="openEditModal('{{ $c->id }}')"
                                                       class="cursor-pointer text-indigo-600 hover:text-indigo-900">Edit</p>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="bg-white p-3 border-t rounded-b-md  ">
                                {{ $customers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-data="{ open: @entangle('edit_modal') }" x-cloak x-show="open"
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
                <div  x-show="open" x-description="Modal panel, show/hide based on modal state."
                      x-transition:enter="ease-out duration-300"
                      x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                      x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                      x-transition:leave="ease-in duration-200"
                      x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                      x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                      class="inline-block align-bottom bg-white rounded-lg  text-left
                     overflow-hidden shadow-xl transform transition-all
                     sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full  "
                      role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class=" p-4">
                        <div class="mb-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Customer</h3>
                        </div>
                        @include('include.alert')
                        <div class="grid grid-cols-6 gap-6 @if(!empty($success)|| ($errors->any())) mt-2 @endif">

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Customer Name<span class="text-red-500">*</span></label>
                                <input wire:model.defer="edit_customer_data.name" type="text" autocomplete="off"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input wire:model.defer="edit_customer_data.email" type="text" autocomplete="off"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Mobile #<span class="text-red-500">*</span></label>
                                <input wire:model.defer="edit_customer_data.mobile_no" type="number" autocomplete="off"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input wire:model.defer="edit_customer_data.phone" type="number" autocomplete="off"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input wire:model.defer="edit_customer_data.city" type="text" autocomplete="off"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="col-span-6 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model.defer="edit_customer_data.status"
                                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value=""></option>
                                    <option value="Active">Active</option>
                                    <option value="In-Active">In-Active</option>
                                    <option value="Opportunity">Opportunity</option>
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <input wire:model.defer="edit_customer_data.address" type="text" autocomplete="off"
                                       class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="py-3  text-right ">
                            <button type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="open = false">
                                Close
                            </button>
                            <button type="button" wire:click="updateCustomer()"
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
