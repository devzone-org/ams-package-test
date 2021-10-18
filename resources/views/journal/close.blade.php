@extends('ams::layouts.master')

@section('title') Closing Day @endsection

@section('content')
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('journal.close')
    </div>
@endsection
