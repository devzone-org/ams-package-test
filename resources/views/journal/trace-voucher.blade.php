@extends('ams::layouts.master')

@section('title')
    Trace Voucher
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.trace-voucher')
    @else
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('journal.trace-voucher')
        </div>
    @endif
@endsection


