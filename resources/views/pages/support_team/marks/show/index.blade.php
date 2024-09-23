@extends('layouts.master')
@section('page_title', 'Student Marksheet')
@section('content')

<div class="card">
    <div class="card-header text-center">
        <h4 class="card-title font-weight-bold">Student Marksheet for &rArr; {{ $sr->user->name.' (' . $sr->my_class->name . ' ' . $sr->section->name . ')' }} </h4>
    </div>
</div>

@foreach($exams as $ex)
@foreach($exam_records->where('exam_id', $ex->id) as $exr)

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="font-weight-bold">{{ $ex->name.' - '.$ex->year }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body collapse">

        {{--Sheet Table--}}
        @include('pages.support_team.marks.show.sheet')

        {{--Print Button--}}
        @if(Qs::getSetting('allow_marksheet_print') || Qs::userIsSuperAdmin())
        <div class="text-center mt-3">
            <a target="_blank" href="{{ route('marks.print', [Qs::hash($student_id), $ex->id, $year]) }}" class="btn btn-secondary btn-lg">Print Marksheet <i class="material-symbols-rounded ml-2">print</i></a>
        </div>
        @endif
    </div>
</div>

{{-- EXAM COMMENTS   --}}
@include('pages.support_team.marks.show.comments')

{{-- SKILL RATING --}}
{{-- @include('pages.support_team.marks.show.skills') --}}

@endforeach
@endforeach

@endsection
