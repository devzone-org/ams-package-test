@if(env('AMS_BOOTSTRAP') == 'true')
    @include('layouts.master')
@elseif(env('ACCOUNTS_LAYOUT','topnav') == 'topnav')
    @include('ams::include.layouts.topnav')
@elseif(env('ACCOUNTS_LAYOUT') == 'sidebar')
    @include('ams::include.layouts.sidebar')
    @include('ams::include.layouts.popup')
@endif