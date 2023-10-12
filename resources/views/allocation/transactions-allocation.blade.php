@extends('ams::layouts.master')

@section('title') Transactions Allocation @endsection

@section('content')
    <div class=" mx-auto py-6 sm:px-6 lg:px-8">

        @livewire('allocation.transactions-allocation')
        
    </div>
@endsection