@extends('ams::layouts.master')

@section('title')
    Closing Fiscal Year
@endsection

@section('content')
    @if (env('AMS_BOOTSTRAP') == 'true')
    @else
        <div class=" mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('closing.closing-fiscal-year')
        </div>
    @endif
@endsection
