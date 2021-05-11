@extends('ams::layouts.master')

@section('title') Temp General Journal @endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        @include('ams::journal.links')



         @livewire('journal.temp-list')


    </div>
@endsection


