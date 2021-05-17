@php
    $as_default = "text-gray-500 hover:text-gray-700";
    $as_current = "text-gray-900";
@endphp

<div class="mb-4">
    <nav class="relative z-0 rounded-lg shadow flex divide-x divide-gray-200" aria-label="Tabs">
        <a href="{{ url('accounts/reports/ledger') }}"
           class="{{  (Request::segment(3)=='ledger') ? $as_current : $as_default }}     rounded-l-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10"
           aria-current="page">
            <span>General Ledger</span>
            <span aria-hidden="true" class="{{  (Request::segment(3)=='ledger') ? 'bg-indigo-500' : 'bg-transparent' }}  absolute inset-x-0 bottom-0 h-0.5"></span>
        </a>
        <a href="{{ url('accounts/reports/trial') }}"
           class="{{  (Request::segment(3)=='trial') ? $as_current : $as_default }}  rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
            <span> Trial Balance</span>
            <span aria-hidden="true" class="{{  (Request::segment(3)=='trial') ? 'bg-indigo-500' : 'bg-transparent' }}  absolute inset-x-0 bottom-0 h-0.5"></span>
        </a>
    </nav>

</div>


