@extends('ams::layouts.master')

@section('title')
    Add new account
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('chart-of-accounts.add')
    @else
        <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('chart-of-accounts.add')
        </div>
    @endif
@endsection
