@extends('ams::layouts.master')
@section('title')
    {{ucwords(request()->segment(3))}} Admin Expenses
@endsection

@section('content')
    @if(env('AMS_BOOTSTRAP') == 'true')
        @livewire('admin-expenses.add-admin-expenses',['id'=>request('id')])
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('admin-expenses.add-admin-expenses',['id'=>request('id')])
        </div>
    @endif
@endsection
