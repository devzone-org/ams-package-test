@extends('ams::layouts.master')

@section('title')
    Temp General Journal
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.temp-list')
    @else
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('journal.temp-list')
        </div>
    @endif
@endsection


