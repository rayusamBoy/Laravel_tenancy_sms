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
                <h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->name }} {{ (!isset($section_id_is_null)) ? $m->section->name : '' }} </h6>
            </div>
            <div class="col-md-5">
                <h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' - '.$m->year }}</h6>
            </div>
        </div>
    </div>

    <div class="card-body">
        @include('pages.support_team.marks.edit')
    </div>
</div>
{{--Marks Manage End--}}

@include('partials.js.manage')

@endsection