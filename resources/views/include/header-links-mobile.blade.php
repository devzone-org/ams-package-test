
<a href="{{ url('accounts') }}" class="{{ Request::segment(1)=='accounts' && empty(Request::segment(2))? $a_current : $a_default }}   block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
<a href="{{ url('accounts/chart-of-accounts') }}" class="{{ Request::segment(1)=='accounts' && Request::segment(2)=='chart-of-accounts' ? $a_current : $a_default }}   block px-3 py-2 rounded-md text-base font-medium">Chart of Accounts</a>
