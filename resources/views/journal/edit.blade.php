@extends('ams::layouts.master')

@section('title')
    Edit Journal Entry
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.edit',['voucher_no'=> $voucher_no])
    @else
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('journal.edit',['voucher_no'=> $voucher_no])
        </div>
    @endif
@endsection
