@extends('ams::layouts.master')
@section('title')
    Admin Expenses
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('admin-expenses.add-admin-expenses')
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('admin-expenses.add-admin-expenses')
        </div>
    @endif

@endsection
