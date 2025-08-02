<div>
    <style>
        .select2-container--default .select2-selection--multiple {
            margin-top: 4px;
            min-height: 38px !important;
            padding: 4px 8px !important;
            font-size: 0.875rem; /* text-sm */
            border-radius: 0.375rem; /* rounded-md */
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            background-color: white !important;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        /* Remove excess spacing inside the rendered area */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display{
            cursor: default;
            padding-left: 13px;
            padding-right: 5px;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline{
            padding-bottom: 4px !important;
            width: 100% !important;
        }

        /* Input field for typing (search) inside the multiple select */
        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            margin: 0;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
            font-size: 0.875rem;
            color: #374151; /* text-gray-700 */
            height: 22px !important;
            white-space: nowrap !important;
        }

        /* Selected items (badges) */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4F46E5 !important;
            border: 1px solid #4F46E5 !important;
            color: white !important;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            padding: 0.15rem 0.5rem;
            margin: 2px 4px 2px 0;
        }

        /* Hover and focus ring styles */
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #4F46E5 !important; /* focus:border-indigo-500 */
            box-shadow: 0 0 0 1px #4F46E5 !important; /* ring-indigo-500 */
        }

        /* Dropdown styling */
        .select2-container .select2-dropdown {
            border-radius: 0.375rem !important;
            border: 1px solid #4F46E5 !important;
        }

        /* Highlighted dropdown item */
        .select2-container--default .select2-results__option--highlighted {
            background-color: #4F46E5 !important;
            color: white !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover, .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:focus{
            background-color: #4F46E5;
            color: #333;
            outline: none;
        }

        /* Disable default blue outline */
        .select2-container--default .select2-selection--multiple:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Adjust remove (x) button inside badge */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            margin-right: 4px;
        }
    </style>
    <div class="mb-4 shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add Template</h3>

            <form wire:submit.prevent="saveTemplate()">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-6 sm:col-span-3" wire:ignore>
                        <label class="block text-sm font-medium text-gray-700">Income Accounts<span class="text-red-500">*</span></label>
                        <select id="income-acc-select-2" multiple tabindex="1"
                                class="select2 mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3
                                   focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            @foreach($income_accounts as $key => $a)
                                <option value="{{ $key }}">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3" wire:ignore>
                        <label class="block text-sm font-medium text-gray-700">Expense Accounts<span class="text-red-500">*</span></label>
                        <select id="expense-acc-select-2" multiple tabindex="1"
                                class="select2 mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3
                                   focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            @foreach($expense_accounts as $key => $a)
                                <option value="{{ $key }}">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="report" class="block text-sm font-medium text-gray-700">Report Name</label>
                        <input type="text" id="report" wire:model.defer="detail.report_name"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3
                                  focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    </div>

                    <div class="col-span-6 sm:col-span-2 mt-6">
                        <button type="submit"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{$is_edit ? 'Edit' : 'Add'}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="shadow sm:rounded-md   bg-white">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6 rounded-md">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Template Manager</h3>
            </div>
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: x-circle -->
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                @php
                                    $count = count($errors->all());
                                @endphp
                                There {{ $count > 1 ? "were {$count} errors" : "was {$count} error" }} with your
                                submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">

                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (!empty($success))
                <div class="rounded-md bg-green-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: check-circle -->
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ $success }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" wire:click="$set('success', '')"
                                        class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                    <span class="sr-only">Dismiss</span>
                                    <!-- Heroicon name: x -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <table class="min-w-full divide-y divide-gray-200  rounded-md ">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                </th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Report Name
                </th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Created By
                </th>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Created At
                </th>
                <th scope="col" class="relative px-6 py-3">

                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200  rounded-md">
            @if(!empty($pnl_template_lists))
                @foreach ($pnl_template_lists as $key => $cl)
                    <tr>
                        <td class="px-6 py-4  text-sm font-medium text-gray-900">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">

                            {{ $cl['report_name'] }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ $cl['created_by_name'] }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ date('d M Y h:i A', strtotime($cl['created_at'])) }}
                        </td>
                        <td class="px-6 py-4  text-right text-sm font-medium">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <div>
                                    <button type="button" x-on:click="open=true;" @click.away="open=false;"
                                            class="  rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                                            id="menu-button" aria-expanded="true" aria-haspopup="true">
                                        <span class="sr-only">Open options</span>
                                        <!-- Heroicon name: solid/dots-vertical -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>


                                <div x-show="open"
                                     class="origin-top-right absolute right-0 mt-2 w-56 z-10 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                     tabindex="-1">
                                    <div class="py-1" role="none">
                                        <button type="button" wire:click.prevent="edit({{ $cl['id'] }})"
                                                class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                                role="menuitem">
                                            Edit
                                        </button>

                                        <button type="button" wire:click="confirmDelete('{{ $cl['id'] }}')"
                                                class="text-red-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                                role="menuitem">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-sm text-red-500 rounded-md overflow-hidden">
                        <div class="flex items-center justify-center py-5">
                            <div class="flex justify-between">
                                <span class="ml-2">No Records Found!</span>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif



            </tbody>
        </table>
        <div x-data="{ open: @entangle('delete_modal') }" x-show="open" class="fixed z-10 inset-0 overflow-y-auto"
             aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="open" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-description="Background overlay, show/hide based on modal state."
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     aria-hidden="true"></div>


                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                <div x-show="open" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-description="Modal panel, show/hide based on modal state."
                     class="inline-block align-bottom bg-white rounded-lg px-4 sm:p-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" wire:click="closeDeleteModal" wire:loading.attr="disabled"
                                class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                @click="open = false">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" x-description="Heroicon name: outline/x"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @if($delete_modal)
                        <div class="sm:flex sm:items-start">
                            <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" x-description="Heroicon name: outline/exclamation"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Attention !
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete? This can't be
                                        undone.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="delete" wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="open = false">
                                Delete
                            </button>
                            <button type="button" wire:click="closeDeleteModal" wire:loading.attr="disabled"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="open = false">
                                Cancel
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        $(document).ready(function() {
            $("#income-acc-select-2").select2({
                placeholder: "Search"
            });
            $("#expense-acc-select-2").select2({
                placeholder: "Search"
            });

            $('#income-acc-select-2').on('change', function (e) {
                var data = $('#income-acc-select-2').select2("val");
                @this.set('detail.income_accounts', data);
            });
            $('#expense-acc-select-2').on('change', function (e) {
                var data = $('#expense-acc-select-2').select2("val");
                @this.set('detail.expense_accounts', data);
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-container--open .select2-search__field').focus();
            });
        });
        window.addEventListener('reset-select-2',function(){
            $('#income-acc-select-2').val(null).trigger('change');
            $('#expense-acc-select-2').val(null).trigger('change');
        });
        window.addEventListener('edit', function (event) {
            const detail = event.detail.detail;
            const incomeAccounts = detail.income_accounts;
            const expenseAccounts = detail.expense_accounts;

            // Set values to Select2 fields
            $('#income-acc-select-2')
                .val(incomeAccounts)
                .trigger('change');

            $('#expense-acc-select-2')
                .val(expenseAccounts)
                .trigger('change');
            $('#report').val(detail.report_name);
        });



    </script>
</div>