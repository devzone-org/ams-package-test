@if(env('AMS_BOOTSTRAP') == 'true')
    <form wire:submit.prevent="create">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <h1>Add Chart Of Account</h1>
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
                                    <h4 class="card-title"><b>Add New Account</b></h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if(!empty($success))
                                            <div class="col-12">
                                                <div class="alert alert-success alert-dismissible" style="">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true" wire:click.prevent="dismissErrorMsg">
                                                        ×
                                                    </button>
                                                    {{ $success }}
                                                </div>
                                            </div>
                                        @endif
                                        @if ($errors->any())
                                            <div class="col-12">
                                                <div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">
                                                        ×
                                                    </button>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{!! $error !!}</li>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="at_level" class="font-weight-normal">Choose Level</label>
                                                <select id="at_level" wire:model="at_level"
                                                        class="form-control @error('at_level')  is-invalid @enderror">
                                                    <option value=""></option>
                                                    <option value="3">Level 4</option>
                                                    <option value="4">Level 5</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="account_type" class="font-weight-normal">Account
                                                    Type</label>
                                                <select id="account_type" wire:model="account_type"
                                                        class="form-control @error('account_type')  is-invalid @enderror">
                                                    <option value=""></option>
                                                    <option value="Assets">Assets</option>
                                                    <option value="Liabilities">Liabilities</option>
                                                    <option value="Equity">Equity</option>
                                                    <option value="Income">Income</option>
                                                    <option value="Expenses">Expenses</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="parent_account" class="font-weight-normal">Parent
                                                    Account</label>
                                                <select id="parent_account" wire:model="parent_account"
                                                        class="form-control @error('parent_account')  is-invalid @enderror">
                                                    <option value=""></option>
                                                    @foreach($sub_accounts as $sa)
                                                        <option value="{{ $sa['id'] }}">{{ $sa['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="account_name" class="font-weight-normal">Account
                                                    Name</label>
                                                <input type="text" wire:model="account_name" id="account_name"
                                                       autocomplete="off"
                                                       class="form-control @error('account_name')  is-invalid @enderror">
                                            </div>
                                        </div>

                                        @if($show_opening_balance)
                                            <div class="col-xs-6 col-sm-4">
                                                <div class="form-group">
                                                    <label for="opening_balance"
                                                           class="font-weight-normal">Opening
                                                        Balance</label>
                                                    <input type="number" step="0.01" wire:model="opening_balance"
                                                           id="opening_balance"
                                                           class="form-control @error('opening_balance')  is-invalid @enderror">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xs-6 col-sm-4">
                                            <div class="form-group">
                                                <label for="date" class="font-weight-normal">Date</label>
                                                <input type="text" wire:model.lazy="date" id="date"
                                                       class="form-control @error('date')  is-invalid @enderror"
                                                       autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-12 pt-3">
                                            <div class="h-5 flex items-center">
                                                <input wire:model="is_contra" id="is_contra" name="is_contra"
                                                       type="checkbox"
                                                       class="">
                                                <label for="is_contra" class="px-2 py-0 font-weight-normal">Is
                                                    Contra?</label>

                                            </div>
                                            <div class="ml-3 text-sm">
                                                <p class="text-muted">The behaviour of account will be treated as
                                                    inverse.</p>
                                            </div>
                                        </div>

                                        <div class="col-12 pt-3 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    Create
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
            let from_date = new Pikaday({
                field: document.getElementById('date'),
                format: "DD MMM YYYY"
            });


            from_date.setDate(new Date('{{ $date }}'));


        </script>

    @endpush
@else
    <form wire:submit.prevent="create">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add new account</h3>

                </div>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Heroicon name: x-circle -->
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    @php
                                        $count = count($errors->all());
                                    @endphp
                                    There {{ $count > 1 ? "were {$count} errors": "was {$count} error" }}
                                    with
                                    your submission
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
                @if(!empty($success))
                    <div class="rounded-md bg-green-50 p-4">
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
                                    {{ $success }}
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
                @endif

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="at_level" class="block text-sm font-medium text-gray-700">Choose Level</label>
                        <select id="at_level" wire:model="at_level"
                                class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            <option value="3">Level 4</option>
                            <option value="4">Level 5</option>
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="account_type" class="block text-sm font-medium text-gray-700">Account Type</label>
                        <select id="account_type" wire:model="account_type"
                                class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            <option value="Assets">Assets</option>
                            <option value="Liabilities">Liabilities</option>
                            <option value="Equity">Equity</option>
                            <option value="Income">Income</option>
                            <option value="Expenses">Expenses</option>
                        </select>
                    </div>


                    <div class="col-span-6 sm:col-span-3">
                        <label for="parent_account" class="block text-sm font-medium text-gray-700">Parent
                            Account</label>
                        <select id="parent_account" wire:model="parent_account"
                                class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value=""></option>
                            @foreach($sub_accounts as $sa)
                                <option value="{{ $sa['id'] }}">{{ $sa['name'] }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-span-6  sm:col-span-3">
                        <label for="account_name" class="block text-sm font-medium text-gray-700">Account Name</label>
                        <input type="text" wire:model="account_name" id="account_name" autocomplete="off"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    @if($show_opening_balance)
                        <div class="col-span-6 sm:col-span-3">
                            <label for="opening_balance" class="block text-sm font-medium text-gray-700">Opening
                                Balance</label>
                            <input type="number" step="0.01" wire:model="opening_balance" id="opening_balance"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    @endif

                    <div class="col-span-6 sm:col-span-3">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="text" readonly wire:model.lazy="date" id="date"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-6">
                        <div class="flex items-start">
                            <div class="h-5 flex items-center">
                                <input wire:model="is_contra" id="is_contra" name="is_contra" type="checkbox"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_contra" class="font-medium text-gray-700">Is Contra?</label>
                                <p class="text-gray-500">The behaviour of account will be treated as inverse.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit"
                        class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    Create
                </button>
            </div>
        </div>
    </form>
@endif




@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script>
        let from_date = new Pikaday({
            field: document.getElementById('date'),
            format: "DD MMM YYYY"
        });


        from_date.setDate(new Date('{{ $date }}'));


    </script>
@endsection
