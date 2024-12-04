@extends('layouts.master')

@section('page_title', 'Progressive Sheet')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">folder_copy</i> Progressive Sheet</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('assessments.progressive_select') }}">
            @csrf
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exam_id" class="col-form-label font-weight-bold">Assessments for:</label>
                        <select required id="exam_id" name="exam_id" class="form-control select" data-placeholder="Select Exam">
                            @foreach ($assessments->where('exam.year', Qs::getCurrentSession()) as $as)
                            <option {{ $selected && $exam_id == $as->exam->id ? 'selected' : '' }} value="{{ $as->exam->id }}">{{ $as->exam->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                        <select onchange="(<?php echo Qs::userIsClassSectionTeacher() ?>) ? getTeacherClassSections(this.value, '#section_id') : getClassSections(this.value, '#section_id');" required id="my_class_id" name="my_class_id" class="form-control select" data-placeholder="Select Class">
                            <option value=""></option>
                            @foreach ($my_classes as $c)
                            <option {{ $selected && $my_class_id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                        <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                            @if ($selected)
                            @foreach ($sections->where('my_class_id', $my_class_id) as $s)
                            <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mt-4">
                    <div class="text-right mt-1">
                        <button type="submit" class="btn btn-primary">View Sheet <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- if Selection Has Been Made --}}

@if ($selected)

@php $all_marks = Mk::getAllMksTotal($students, $ex->id, $my_class_id); @endphp

<div class="card w-fit">
    <div class="card-header">
        @php $term = ($asr->first()->exam->term == 1) ? "First" : "Second" @endphp
        <h5 class="card-title font-weight-bold">Progressive Sheet for {{ $my_class->name . ' ' }} @if (isset($section)) {{ $section->name }} @endif {{ ' - ' . 'of ' . $ex->name . ' (' . $term . ' term, ' . $year . ')' }} </h5>
    </div>
    <div class="card-body">
        <table class="fls-scrolls-hoverable table-styled" data-fl-scrolls id="results-table">
            <thead class="bg-light">
                <tr>
                    <th class="font-weight-bold text-center">#</th>
                    <th class="font-weight-bold pl-2">STUDENT NAME</th>
                    <th class="font-weight-bold text-center">GENDER</th>

                    @foreach ($subjects as $sub)
                    <th class="font-weight-bold text-center" title="{{ $sub->name }}" rowspan="2">
                        {{ strtoupper($sub->slug ?: $sub->name) }}
                    </th>
                    <th class="font-weight-bold text-center text-info-800" title="Grade">Grd</th>
                    @endforeach

                    <th class="font-weight-bold text-center text-red-600">Total</th>
                    <th class="font-weight-bold text-center text-blue-800">Average</th>
                    <th class="font-weight-bold text-center text-orange-800" title="Average Grade">Avg Grade</th>
                    <th class="font-weight-bold text-center text-green-800">Position</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $s)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="pl-2">{{ $s->user->name }}</td>
                    <td class="gender text-center">{{ substr($s->user->gender, 0, 1) }}</td>

                    @foreach ($subjects as $sub)
                    @php $as_recs = $asr->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first() @endphp
                    <td class="text-center">{{ $as_recs->$tex ?? '-' }}</td>
                    <td class="text-center text-info-800">{{ is_null($as_recs) || is_null($as_recs->grade) ? '-' : $as_recs->grade->name }}</td>
                    @endforeach

                    <td class="text-center text-red-600">{{ $asr->where('student_id', $s->user_id)->first()->total ?? '-' }}</td>
                    @php $average = $asr->where('student_id', $s->user_id)->first()->ave @endphp
                    <td class="text-center text-blue-800">{{ $average ?? '-' }}</td>
                    <td class="text-center text-orange-800">{{ is_null($average) ? "-" : Mk::getGrade($average) }}</td>
                    <td class="text-center text-green-800">{{ $asr->where('student_id', $s->user_id)->first()->pos ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Print Button --}}
        <div class="text-center mt-4">
            <a target="_blank" href="{{ route('assessments.print_progressive', [$exam_id, $my_class_id, $section_id]) }}" class="btn btn-danger btn-lg"><i class="material-symbols-rounded mr-2">print</i> Print Progressive Sheet</a>
        </div>
    </div>
</div>

@include('partials.js.manage')

@endif

@endsection
