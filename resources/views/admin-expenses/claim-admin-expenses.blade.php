@extends('ams::layouts.master')

@section('title')
    Claim Reimbursement
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('admin-expenses.claim-admin-expenses')
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('admin-expenses.claim-admin-expenses')
        </div>
    @endif

@endsection
