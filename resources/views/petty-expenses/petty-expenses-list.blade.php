@extends('ams::layouts.master')

@section('title') Petty Expenses @endsection

@section('content')
    <div class=" mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('petty-expenses.tab',['type'=>request('type')])
        @livewire('petty-expenses.petty-expenses-list',['type'=>request('type')])
    </div>

@endsection
