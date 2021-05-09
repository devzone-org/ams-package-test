@php
    $as_default = "text-gray-500 hover:text-gray-700";
    $as_current = "text-gray-900";
@endphp

<div class="mb-4">
    <nav class="relative z-0 rounded-lg shadow flex divide-x divide-gray-200" aria-label="Tabs">

        <a href="{{ url('accounts/journal') }}"
           class="{{  empty(Request::segment(3)) ? $as_current : $as_default }}     rounded-l-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10"
           aria-current="page">
            <span>Temp General Journal</span>
            <span aria-hidden="true" class="{{  empty(Request::segment(3)) ? 'bg-indigo-500' : 'bg-transparent' }}  absolute inset-x-0 bottom-0 h-0.5"></span>
        </a>


        <a href="{{ url('accounts/journal/add') }}"
           class="{{  (Request::segment(3)=='add') ? $as_current : $as_default }}  rounded-r-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10">
            <span> Add Journal Entry</span>
            <span aria-hidden="true" class="{{  (Request::segment(3)=='add') ? 'bg-indigo-500' : 'bg-transparent' }}  absolute inset-x-0 bottom-0 h-0.5"></span>
        </a>
    </nav>

</div>


