@extends('ams::layouts.master')

@section('title')
    Approved Petty Expenses
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        <div class="content-wrapper">
            <div class="d-flex justify-content-center">
                <div class="col-12 ">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title"><b>Petty Expenses</b></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 pl-2 pr-3 pb-1">
                        <div class="pr-2 pl-2">
                            @livewire('petty-expenses.tab')
                        </div>
                        @livewire('petty-expenses.approved-petty-expenses-list')
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('petty-expenses.tab')
            @livewire('petty-expenses.approved-petty-expenses-list')
        </div>
    @endif

@endsection
