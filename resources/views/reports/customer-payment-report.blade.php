@extends('ams::layouts.master')

@section('title')
    Customer Payment Report
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('reports.customer-payment-report')
    </div>
@endsection
