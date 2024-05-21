@extends('ams::layouts.master')

@section('title')
    Payments & Receiving
@endsection

@section('content')
    @if (env('AMS_BOOTSTRAP') == 'true')
        @livewire('journal.payments.listing')
    @else
        <style>
            .pagination {
                display: flex !important;
                flex-direction: row !important;
                justify-content: right
            }

            .page-item {
                padding: 6px !important;
                width: 38px !important;
                margin: 0 3px !important;
                background-color: rgb(222, 244, 253) !important;
                border: 1px solid darkgray !important;
                border-radius: 3px !important;
                vertical-align: middle !important;
                text-align: center !important;
            }
        </style>
        <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('journal.payments.listing')
        </div>
    @endif
@endsection
