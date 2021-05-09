@extends('ams::layouts.master')

@section('title') Add new account @endsection

@section('content')
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">

                @include('ams::coa.links')
                @livewire('chart-of-accounts.add')


    </div>
@endsection
