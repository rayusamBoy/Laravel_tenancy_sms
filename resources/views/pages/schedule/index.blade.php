@extends('layouts.master')

@section('page_title', 'Schedule')

@section('content')

{{-- Calendar Events --}}
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Calendar Events</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    @include('pages.schedule.calendar.index')
</div>

{{-- Manage Events --}}
@if(Qs::userIsTeamSA())
@include('pages.schedule.calendar.manage')
@endif

@endsection
