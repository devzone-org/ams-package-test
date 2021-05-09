@extends('ams::layouts.master')

@section('title') Add Journal @endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class=" sm:px-0">

            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                @include('ams::journal.links')
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                    @livewire('journal.add')
                </div>
            </div>

        </div>
    </div>
@endsection
