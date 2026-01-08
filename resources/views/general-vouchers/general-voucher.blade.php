@extends('ams::layouts.master')

@section('title')
    Transaction Allocation
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('general-vouchers.manual-allocation')
    @else
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Feature not available in Tailwind mode yet.</h3>
            </div>
        </div>
    @endif
@endsection


