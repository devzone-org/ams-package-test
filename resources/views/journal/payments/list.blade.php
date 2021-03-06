@extends('ams::layouts.master')

@section('title') Payments & Receiving @endsection

@section('content')
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('journal.payments.listing')
    </div>
@endsection
