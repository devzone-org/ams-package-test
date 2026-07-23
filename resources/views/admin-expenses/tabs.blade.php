@php
    $current_tab = Request::segment(3);
@endphp

@if(env('AMS_BOOTSTRAP') == 'true')
    <ul class="nav nav-pills nav-fill mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link rounded {{ $current_tab == 'list' ? 'active bg-gray' : '' }}"
               href="{{ route('admin-expenses.list') }}" role="tab">All Expenses</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link rounded {{ $current_tab == 'claim' ? 'active bg-gray' : '' }}"
               href="{{ route('admin-expenses.claim') }}" role="tab">Unclaimed Expenses</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link rounded {{ $current_tab == 'statement' ? 'active bg-gray' : '' }}"
               href="{{ route('admin-expenses.statement') }}" role="tab">Statement</a>
        </li>
    </ul>
@else
    @php
        $a_default = "text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 group";
        $a_current = "text-indigo-600 border-b-2 border-indigo-500";
    @endphp
    <div class="mb-5 overflow-x-auto">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                <a href="{{ route('admin-expenses.list') }}"
                   class="{{ $current_tab == 'list' ? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                    <span>All Expenses</span>
                </a>
                <a href="{{ route('admin-expenses.claim') }}"
                   class="{{ $current_tab == 'claim' ? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                    <span>Unclaimed Expenses</span>
                </a>
                <a href="{{ route('admin-expenses.statement') }}"
                   class="{{ $current_tab == 'statement' ? $a_current : $a_default }} inline-flex items-center px-1 py-4 text-sm font-medium">
                    <span>Statement</span>
                </a>
            </nav>
        </div>
    </div>
@endif
