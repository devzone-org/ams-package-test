@extends('ams::layouts.master')

@section('title')
    Payments & Receiving
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.payments.listing')
    @else
        <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('journal.payments.listing')
        </div>
    @endif
@endsection
