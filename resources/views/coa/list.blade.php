@extends('ams::layouts.master')

@section('title') Chart of Accounts @endsection

@section('content')
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        @include('ams::coa.links')
        @livewire('chart-of-accounts.listing')
    </div>
@endsection
