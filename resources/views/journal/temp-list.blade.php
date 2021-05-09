@extends('ams::layouts.master')

@section('title') Temp General Journal @endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class=" sm:px-0">

            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                @include('ams::journal.links')


            </div>

        </div>
    </div>
@endsection
