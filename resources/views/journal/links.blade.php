@php
    $as_default = "text-gray-900 hover:text-gray-900 hover:bg-gray-50";
    $as_current = "bg-gray-50 text-indigo-700 hover:text-indigo-700 hover:bg-white";
@endphp

<aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
    <nav class="space-y-1">
        <!-- Current: "bg-gray-50 text-indigo-700 hover:text-indigo-700 hover:bg-white", Default: "text-gray-900 hover:text-gray-900 hover:bg-gray-50" -->
        <a href="{{ url('accounts/journal') }}"
           class="{{  empty(Request::segment(3)) ? $as_current : $as_default }}   group rounded-md px-3 py-2 flex items-center text-sm font-medium"
           aria-current="page">
                            <span class="truncate">
                              Temp General Journal
                            </span>
        </a>

        <a href="{{ url('accounts/journal/add') }}"
           class="{{  (Request::segment(3)=='add') ? $as_current : $as_default }}   group rounded-md px-3 py-2 flex items-center text-sm font-medium"
           aria-current="page">
                            <span class="truncate">
                              Add Journal
                            </span>
        </a>
    </nav>
</aside>
