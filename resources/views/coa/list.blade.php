@extends('ams::layouts.master')

@section('title') Chart of Accounts @endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('chart-of-accounts.listing')
    @else
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('chart-of-accounts.listing')
    </div>
    @endif
@endsection
