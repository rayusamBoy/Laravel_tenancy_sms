@extends('layouts.master')

@section('page_title', 'Manage Assessments')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Fill The Form To Manage Assessments</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @include('pages.support_team.assessments.selector')
    </div>
</div>

@if($selected)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="assessments-manage">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span>
                <strong>NB.</strong> Termed Test will be automatically filled when the respective exam marks are available. 
                The browser autosaves the contents of an input field, and if the browser is refreshed, it will restores the input field content so that no writing is lost.
            </span>
        </div>
    </div>
</div>

<div class="card w-fit">
    <div class="card-header">
        <h3 class="text-center">ASSESSMENT RECORDS FOR STUDENTS PER SUBJECT</h3>
        <div class="row">
            <div class="col-md-4">
                <h6 class="card-title"><strong>Subject: </strong> {{ $m->subject->name }}</h6>
            </div>
            <div class="col-md-4">
                <h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->name }} {{ (!isset($section_id_is_null)) ? $m->section->name : '' }}</h6>
            </div>
            <div class="col-md-4">
                <h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' - '.$m->year }}</h6>
            </div>
        </div>
    </div>

    <div class="card-body">
        @include('pages.support_team.assessments.edit')
    </div>
</div>
@endif
{{-- Assessments Manage End --}}

@include('partials.js.manage')

@endsection