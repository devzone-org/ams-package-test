@extends('ams::layouts.master')

@section('title')
    Expenses Claim Statement
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        <div class="content-wrapper">
            <div class="d-flex justify-content-center">
                <div class="col-12">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <h1>Admin Expenses</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 pl-2 pr-3 pb-1">
                        @include('ams::admin-expenses.tabs')
                        @livewire('admin-expenses.statement-admin-expenses')
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @include('ams::admin-expenses.tabs')
            @livewire('admin-expenses.statement-admin-expenses')
        </div>
    @endif
@endsection
