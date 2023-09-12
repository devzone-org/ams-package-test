@if(env('AMS_BOOTSTRAP') == 'true')
    @php
        $current_tab = Request::segment(3) ?? 'student';
    @endphp

    <div class="card rounded">
        <!-- Tabs navs -->
        <ul class="nav nav-pills nav-fill" id="v-pills-tab" role="tablist" >
            <li class="nav-item" role="presentation">
                <a class="nav-link rounded {{ $current_tab == 'unclaimed'? 'active bg-gray' : '' }}" id="ex-with-icons-tab-1" href="{{ ($current_tab == 'unclaimed') ? 'javascript:void(0)' : url('accounts/petty-expenses-list/unclaimed') }}" role="tab"
                   aria-controls="ex-with-icons-tabs-1" aria-selected="true">Unclaimed</a>
            </li>
            <li class="nav-item" role="presentation" >
                <a class="nav-link rounded {{ $current_tab == 'claimed'? 'active bg-gray' : '' }}" id="ex-with-icons-tab-1" href="{{ ($current_tab == 'claimed') ? 'javascript:void(0)' : url('accounts/petty-expenses-list/claimed') }}" role="tab"
                   aria-controls="ex-with-icons-tabs-1" aria-selected="true">Claimed</a>
            </li>
            <li class="nav-item" role="presentation" >
                <a class="nav-link rounded {{ $current_tab == 'approved'? 'active bg-gray' : '' }}" id="ex-with-icons-tab-1" href="{{ ($current_tab == 'approved') ? 'javascript:void(0)' : url('accounts/petty-expenses-list/approved') }}" role="tab"
                   aria-controls="ex-with-icons-tabs-1" aria-selected="true">Approved</a>
            </li>
        </ul>
        <!-- Tabs navs -->

        <!-- Tabs content -->

        <!-- Tabs content -->
    </div>
@else
    <div>
        <div class="mb-5">
            @php
                $a_default = "text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 group";
                $a_current = "text-indigo-600 border-b-2 border-indigo-500";
                $svg_default = "text-gray-400 group-hover:text-gray-500";
                $svg_current = "text-indigo-500 group-hover:text-gray-500";
                $type = Request::segment(3);
            @endphp

            <h3 class="text-lg font-medium leading-6 text-gray-900">
                Petty Expenses
            </h3>

            <div>
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs"
                            class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option selected>Unclaimed</option>
                        <option>Claimed</option>
                        <option>Approved</option>
                    </select>
                </div>
                <div class="hidden sm:block overflow-x-auto">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                            <a href="/accounts/petty-expenses-list/unclaimed"
                               class="{{ $type == 'unclaimed'? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                                <span>Unclaimed</span>
                            </a>
                            <a href="/accounts/petty-expenses-list/claimed"
                               class="{{ $type == 'claimed'? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                                <span>Claimed</span>
                            </a>
                            <a href="/accounts/petty-expenses-list/approved"
                               class="{{ $type == 'approved'? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                                <span>Approved</span>
                            </a>


                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif