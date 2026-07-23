@extends('ams::layouts.master')
@section('title')
    Claim Reimbursement
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        <div class="content-wrapper">
            <div class="w-100 pl-2 pr-3 pb-1">
                @include('ams::admin-expenses.tabs')
                @livewire('admin-expenses.claim-admin-expenses')
            </div>
        </div>
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @include('ams::admin-expenses.tabs')
            @livewire('admin-expenses.claim-admin-expenses')
        </div>
    @endif
@endsection
