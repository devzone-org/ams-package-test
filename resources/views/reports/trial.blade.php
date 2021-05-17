@extends('ams::layouts.master')

@section('title') Trial Balance @endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @include('ams::reports.links')
        @livewire('reports.trial')
    </div>
@endsection
