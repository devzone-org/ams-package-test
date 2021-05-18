@extends('ams::layouts.master')

@section('title') General Ledger @endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @include('ams::reports.links')
        @livewire('reports.ledger',['account_id' => request()->query('account_id') ])
    </div>
@endsection
