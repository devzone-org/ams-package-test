@extends('ams::layouts.master')

@section('title') Payments & Receiving Form @endsection

@section('content')

    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.payments.add')
    @else
    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('journal.payments.add')
    </div>
    @endif
@endsection
