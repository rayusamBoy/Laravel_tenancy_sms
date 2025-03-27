@extends('layouts.master')

@section('page_title', 'Manage Marks')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Fill The Form To Manage Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @include('pages.support_team.marks.selector')
    </div>
</div>

<div class="card w-fit">
    <div class="card-header">
        <h4 class="text-center">STUDENTS EXAM MARKS</h4>
        <div class="row">
            <div class="col-md-4">
                <h6 class="card-title"><strong>Subject: </strong> {{ $m->subject->name }}</h6>
            </div>
            <div class="col-md-3">
                <h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->name }} {{ !isset($section_id_is_null) ? $m->section->name : '' }} </h6>
            </div>
            <div class="col-md-5">
                <h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name . ' - ' . $m->year }}</h6>
            </div>
        </div>
    </div>

    @php $is_restricted = (!$exam->editable || $exam->locked) && !Qs::userIsSuperAdmin(); @endphp

    <div class="card-body">
        @include('pages.support_team.marks.edit')
    </div>

    <div>
        @if($is_restricted)
        <p class="float-right badge p-3 mr-3 text-warning font-size-base">Opened In Read Only Mode</p>
        @else
        <p class="float-right badge p-3 mr-3 text-info font-size-base">Autosave is on</p>
        @endif
    </div>
</div>

{{--Marks Manage End--}}

@endsection
