@extends('ams::layouts.master')

@section('title')
    Petty Expenses
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('petty-expenses.add-petty-expenses',['id'=>request('id')])
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('petty-expenses.add-petty-expenses',['id'=>request('id')])
        </div>
    @endif

@endsection
