@extends('layouts.master')
@section('page_title', 'Batch Marks')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="note-info">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span><strong>Note:</strong>
                You can create assessment records of students either here through downloading <strong>batch template</strong> or in <a href="{{ route('marks.index') }}">
                Manage Exam Marks</a> section only for the current session and current students class. Once the student has been promoted to the next session you will not be able to create the records
                for the current session anymore.
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning border-0 alert-dismissible has-do-not-show-again-button" id="advance-level-info">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span>
                If you changed the section for a particular student; you can download the template again for the particular exam and update the template as well as upload it (if applicable), regardless you must update the exam marks.
                Select <strong>Yes</strong> if you want to delete exam records and marks of the old sections for any student whose section has changed.
            </span>
        </div>
    </div>
</div>

@include('pages/support_team/marks/batch/template')
@include('pages/support_team/marks/batch/upload')

@if(Qs::userIsTeamSA())
@include('pages/support_team/marks/batch/update')

@if(Qs::userIsSuperAdmin())
@include('pages/support_team/marks/batch/delete')
@endif
@endif

@endsection
