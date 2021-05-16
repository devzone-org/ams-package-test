@extends('ams::layouts.master')

@section('title') Edit Journal Entry @endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @include('ams::journal.links')
        @livewire('journal.edit',['voucher_no'=> $voucher_no])
    </div>
@endsection
