@extends('ams::layouts.master')

@section('title')
    Trial Balance
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('reports.trial')
    @else
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('reports.trial')
        </div>
    @endif
@endsection
