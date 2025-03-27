@extends('layouts.master')

@section('page_title', 'Manage Exam Marks')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title"><i class="material-symbols-rounded mr-2">manage_history</i> Manage Exam Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @include('pages.support_team.marks.selector')
    </div>
</div>

@endsection
