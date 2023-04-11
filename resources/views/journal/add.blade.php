@extends('ams::layouts.master')

@section('title') Add Journal Entry @endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.add')

    @else
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

            @livewire('journal.add')
        </div>
    @endif
@endsection
