@extends('ams::layouts.master')

@section('title') Equity Ratio @endsection

@section('content')
    <div class=" mx-auto py-6 sm:px-6 lg:px-8">


        @livewire('chart-of-accounts.equity-ratio')


    </div>
@endsection