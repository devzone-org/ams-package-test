@extends('ams::layouts.master')

@section('title') @if(env('ACCOUNTS_NEW_SIDEBAR') == 'yes') Ledger @else GL: @endif   @endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('reports.ledger',['account_id' => request()->query('account_id') ])
    @else
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('reports.ledger',['account_id' => request()->query('account_id') ])
    </div>
    @endif
@endsection
