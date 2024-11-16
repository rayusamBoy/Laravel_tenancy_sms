@extends('layouts.master')
@section('page_title', 'Tabulation Sheet')
@pushOnce('css')
<style>
    .table-sm td,
    .table-sm th {
        text-align: center;
        padding: 0.3rem 1rem !important;
    }

</style>
@endPushOnce

@section('content')

{{-- Selector --}}
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">table</i> Tabulation Sheet</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" class="page-block" action="{{ route('marks.tabulation_select') }}">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="year" class="col-form-label font-weight-bold">Select Year:</label>
                        <select onchange="getYearExams(this.value)" data-placeholder="Select Year" required id="year" name="year" class="form-control select">
                            <option value="">Select Year</option>
                            @foreach($years as $yr)
                            <option {{ ($selected && $yr->year == $year) ? 'selected' : '' }} value="{{ $yr->year }}">{{ $yr->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                        <select required id="exam_id" name="exam_id" class="form-control select-search" data-placeholder="Select Year First">
                            <option value="">Select Year First</option>
                            @if($selected)
                            @foreach ($exams as $exm)
                            <option {{ $selected && $exam_id == $exm->id ? 'selected' : '' }} value="{{ $exm->id }}">{{ $exm->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                        <select onchange="getClassSections(this.value, '#section_id_add_all')" required id="my_class_id" name="my_class_id" class="form-control select" data-placeholder="Select Class">
                            <option value="">Select Class</option>
                            @foreach ($my_classes as $c)
                            <option {{ $selected && $my_class->id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="section_id_add_all" class="col-form-label font-weight-bold">Section:</label>
                        <select required id="section_id_add_all" name="section_id" data-placeholder="Select Class First" class="form-control select">
                            @if($selected)
                            @foreach ($sections->where('my_class_id', $my_class->id) as $s)
                            <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">
                                {{ $s->name }}
                            </option>
                            @endforeach
                            <option {{ $section_id == 'all' ? 'selected' : '' }} value="all" title="All Sections">All</option>
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

{{-- if Selction Has Been Made --}}
@if ($selected)

<div class="card tabulation w-fit">
    <div class="card-header">
        <h5 class="card-title font-weight-bold">Tabulation Sheet for {{ $my_class->name . ' ' }} @if (isset($section)) {{ $section->name }} @endif {{ ' - ' . $ex->name . ' (' . $year . ')' }} </h5>
    </div>

    <div class="card-body">
        <div class="row">
            {{--Division Summary--}}
            <div class="col-3 mw-fit">
                <h6>Division Summary</h6>
                {!! Mk::renderDivisionsSummary($exam_id, $my_class->id, $my_class->class_type_id, $section_id) !!}
            </div>
            {{--Grades--}}
            <div class="col-3 mw-fit">
                <h6>Grade Criteria</h6>
                {!! Mk::renderGrades($class_type_id) !!}
            </div>
            {{--Divisions--}}
            <div class="col-3 mw-fit">
                <h6>Division Criteria</h6>
                {!! Mk::renderDivisions($class_type_id) !!}
            </div>
            {{--School GPA--}}
            <div class="w-60 col-3 text-center">
                <h6>School GPA <i>(out of 5)</i></h6>
                <table class="table-styled text-center">
                    <thead>
                        <tr>
                            <td>{{ Mk::getGPA($exam_id, $my_class->id, $my_class->class_type_id, $section_id) }}</td>
                        </tr>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <h5 class="text-center font-weight-bold text-uppercase">Current Results</h5>
            </div>

            <table id="results-table" class="table-styled" border="1">
                <thead>
                    <tr>
                        <th class="font-weight-bold text-center">#</th>
                        <th class="font-weight-bold text-center">STUDENT NAME</th>
                        <th class="font-weight-bold text-center">GENDER</th>

                        @foreach ($subjects as $sub)
                        <th class="font-weight-bold text-center" title="{{ $sub->name }}" rowspan="2">
                            {{ strtoupper($sub->slug ?: $sub->name) }}
                        </th>
                        <th class="font-weight-bold text-center text-green" title="Grade">Grd</th>
                        @endforeach

                        <th class="font-weight-bold text-center text-danger">Total</th>
                        <th class="font-weight-bold text-center text-secondary">Average</th>
                        <th class="font-weight-bold text-center text-info" title="Average Grade">Grd</th>
                        <th class="font-weight-bold text-center text-pink" title="Division Points">Points</th>
                        <th class="font-weight-bold text-center text-orange" title="Division">Division</th>
                        <th class="font-weight-bold text-center text-violet" title="Gpa">GPA</th>
                        <th class="font-weight-bold text-center text-primary">Position</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students->sortBy('user.name')->sortBy('user.gender') as $s)

                    @php
                    $gender = $s->user->gender;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        {{--Student name--}}
                        <td class="pl-1">{{ $s->user->name }}</td>
                        {{--Gender--}}
                        <td class="gender text-center">{{ substr($gender, 0, 1) }}</td>

                        @php $total = $exr->where('student_id', $s->user_id)->first()->total ?? 0; @endphp

                        @foreach ($subjects as $sub)
                        {{-- Exams mark --}}
                        <td class="text-center">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->$tex ?? '-') }}</td>
                        {{-- Mark Grade--}}
                        <td class="text-center text-green">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : $marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->grade->name ?? '-' }}</td>
                        @endforeach

                        {{--Exams total marks--}}
                        <td class="total text-center text-danger">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($total ?? '-') }}</td>
                        {{--Exam average--}}
                        <td class="average text-center text-secondary"> {{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->ave ?? '-') }}</td>
                        {{--Exam grade--}}
                        <td class="grade text-center text-info">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->grade->name ?? '-') }}</td>
                        {{--Points--}}
                        <td class="points text-center text-pink">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->points ?? '-') }}</td>
                        {{--Division--}}
                        <td class="division text-center text-orange">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->division->name ?? '-') }}</td>
                        {{--Gpa--}}
                        <td class="gpa text-center text-violet">{{ $total === 0 ? Qs::convertEncoding('&#10006;') : ($exr->where('student_id', $s->user_id)->first()->gpa ?? '-') }}</td>
                        {{--Position--}}
                        <td class="position text-center text-primary">
                            @if($total === 0)
                            &#10006;
                            @elseif($section_id != "all")
                            {{ $exr->where('student_id', $s->user_id)->first()->pos ?? '-' }}
                            @elseif($section_id == "all")
                            {{ $exr->where('student_id', $s->user_id)->first()->class_pos ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <h6 class="font-weight-bold mt-2">Subjects Performance Summary</h6>
            </div>

            <table class="table-styled" border="1">
                <thead>
                    <tr>
                        <th class="text-center">SLUG</th>
                        <th class="text-center">NAME</th>
                        <th class="text-center">REG</th>
                        <th class="text-center">SAT</th>
                        <th class="text-center">ABS</th>
                        <th class="text-center">AVG</th>
                        <th class="text-center text-green">GRADE</th>
                        <th class="text-center">GRADES COUNT</th>
                        <th class="text-center">GPA</th>
                        <th class="text-center">RANK</th>
                    </tr>
                </thead>

                <tbody>
                    {!! Mk::renderSubsPerformanceSummary($section_id, $ex->id, $my_class) !!}
                </tbody>
            </table>
        </div>

        {{-- Print Button --}}
        <div class="text-center mt-4">
            <a target="_blank" href="{{ route('marks.print_tabulation', [$exam_id, $my_class->id, $section_id, $year]) }}" class="btn btn-danger print-tabulation"><i class="material-symbols-rounded mr-2">print</i> Print Tabulation Sheet</a>
        </div>
    </div>
</div>

@include('partials.js.manage')
@endif

@endsection
