<div>
    <div class="mb-5">
        @php
            $a_default = "text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 group";
            $a_current = "text-indigo-600 border-b-2 border-indigo-500";
            $svg_default = "text-gray-400 group-hover:text-gray-500";
            $svg_current = "text-indigo-500 group-hover:text-gray-500";
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
                        <a href="/accounts/petty-expenses-list/unclaimed"
                           class="{{ $type == 'claimed'? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                            <span>Claimed</span>
                        </a>
                        <a href="/accounts/petty-expenses-list/unclaimed"
                           class="{{ $type == 'approved'? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                            <span>Approved</span>
                        </a>


                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>