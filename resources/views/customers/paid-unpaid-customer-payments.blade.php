@extends('ams::layouts.master')

@section('title')
    Paid/Unpaid Customer Payments
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @livewire('customers.paid-unpaid-customer-payments')
    </div>
@endsection
