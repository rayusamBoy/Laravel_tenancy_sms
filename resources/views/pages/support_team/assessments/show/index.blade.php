@extends('layouts.master')
@section('page_title', 'Student Assessmentsheet')
@section('content')

<div class="card">
    <div class="card-header text-center">
        <h4 class="card-title font-weight-bold">Student Assessmentsheet for => {{ $sr->user->name . ' (' . $sr->my_class->name . ' ' . $sr->section->name . ')' }} </h4>
    </div>
</div>

@foreach($assessments as $as)

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="font-weight-bold">Assessments - {{ $as->exam->name.' - '.$as->exam->year }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body collapse">

        {{--Sheet Table--}}
        @include('pages.support_team.assessments.show.sheet')

        {{--Print Button--}}
        <div class="text-center mt-3">
            <div class="list-icons">
                <div class="dropdown">
                    @if(Qs::getSetting('allow_assessmentsheet_print') || Qs::userIsSuperAdmin())
                    @if(Qs::userIsTeamSAT())
                    <a href="#" class="btn btn-secondary btn-lg" data-toggle="dropdown">
                        Print Assessment<i class="material-symbols-rounded ml-2">print</i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-left">
                        <a target="_blank" href="{{ route('assessments.print_cover', Qs::hash($student_id)) }}" class="dropdown-item">Cover </a>
                        <a target="_blank" href="{{ route('assessments.print_minimal', [Qs::hash($student_id), $as->exam->id, $year]) }}" class="dropdown-item">Minimal </a>
                        <a target="_blank" href="{{ route('assessments.print_detailed', [Qs::hash($student_id), $as->exam->id, $year]) }}" class="dropdown-item">Detailed </a>
                    </div>

                    @else
                    <a target="_blank" href="{{ route('assessments.print_detailed', [Qs::hash($student_id), $as->exam->id, $year]) }}" class="btn btn-secondary btn-lg">Print Assessment<i class="material-symbols-rounded ml-2">print</i></a>
                    @endif
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ASSESSMENT COMMENTS   --}}
@include('pages.support_team.assessments.show.comments')

{{-- SKILL RATING --}}
@include('pages.support_team.assessments.show.skills')

@endforeach

@endsection
