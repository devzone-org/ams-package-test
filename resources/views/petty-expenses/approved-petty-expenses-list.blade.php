@extends('ams::layouts.master')

@section('title') Approved Petty Expenses @endsection

@section('content')
    <div class=" mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('petty-expenses.tab')
        @livewire('petty-expenses.approved-petty-expenses-list')
    </div>

@endsection
