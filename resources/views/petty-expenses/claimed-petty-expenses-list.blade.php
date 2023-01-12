@extends('ams::layouts.master')

@section('title') Claimed Petty Expenses @endsection

@section('content')
    <div class=" mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('petty-expenses.tab')
        @livewire('petty-expenses.claimed-petty-expenses-list')
    </div>

@endsection
