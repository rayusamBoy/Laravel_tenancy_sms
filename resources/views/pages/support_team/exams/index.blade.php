@extends('layouts.master')

@section('page_title', 'Manage Exams')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Exams</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            {{-- Manage exam item --}}
            <li class="nav-item"><a href="#all-exams" class="{{ session('announce_exam') || $errors->any() ? 'nav-link' : 'nav-link active' }}" data-toggle="tab">Manage Exam</a></li>
            {{-- Add exam item --}}
            <li class="nav-item"><a href="#new-exam" class="{{ $errors->any() ? 'nav-link active' : 'nav-link' }}" data-toggle="tab"><i class="material-symbols-rounded">add</i> Add Exam</a></li>
            {{-- Announce exam item --}}
            @if (session('announce_exam'))
            <li class="nav-item"><a href="#announce-exam" class="nav-link active" data-toggle="tab"></i> Announce the Exam</a></li>
            @endif
        </ul>

        {{-- Manage exam tab --}}
        <div class="tab-content">
            <div class="{{ session('announce_exam') || $errors->any() ? 'tab-pane fade show' : 'tab-pane fade show active' }}" id="all-exams">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Term</th>
                            <th>Session</th>
                            <th>Category</th>
                            <th>Class Type</th>
                            <th>Editable</th>
                            <th>Locked</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $ex)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ex->name }}@if($ex->published) <span class="badge bg-success">Published</span> @endif</td>
                            <td>{{ "Term {$ex->term}" }}</td>
                            <td>{{ $ex->year }}</td>
                            <td>{{ $ex->category->name }}</td>
                            <td>{{ $ex->class_type->name }}</td>

                            @if (Qs::userIsSuperAdmin())
                            <td><label class="form-switch m-0"><input id="checkbox-exam-edit-{{ $ex->id }}" onchange="updateExamEditState(<?php echo $ex->id; ?>, this)" type="checkbox" @if ($ex->editable) checked @endif><i></i></label></td>
                            @else
                            <td><label class="form-switch disabled m-0"><input type="checkbox" @if ($ex->editable) checked @endif><i></i></label></td>
                            @endif

                            @if(Qs::headSA(auth()->id()))
                            <td><label class="form-switch m-0"><input id="checkbox-exam-lock-{{ $ex->id }}" onchange="updateExamLockState(<?php echo $ex->id; ?>, this)" type="checkbox" @if ($ex->locked) checked @endif><i></i></label></td>
                            @else
                            <td><label class="form-switch disabled m-0"><input type="checkbox" @if ($ex->locked) checked @endif><i></i></label></td>
                            @endif

                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="javascript:;" data-toggle="dropdown" class="material-symbols-rounded">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if (Qs::userIsTeamSA())
                                            {{-- Edit --}}
                                            <a href="{{ route('exams.edit', $ex->id) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            {{-- Publish --}}
                                            @if (!$ex->published)
                                            @if (Qs::userIsSuperAdmin())
                                            <a id="{{ $ex->id }}" onclick="confirmPublish(this.id)" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> Publish</a>
                                            <form method="get" id="item-publish-{{ $ex->id }}" action="{{ route('exams.publish', $ex->id) }}" class="hidden">@csrf @method('get')</form>
                                            @endif
                                            @endif

                                            @endif
                                            {{-- Delete --}}
                                            @if (Qs::userIsSuperAdmin())
                                            <a id="{{ $ex->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ $ex->id }}" action="{{ route('exams.destroy', $ex->id) }}" class="hidden">
                                                @csrf @method('delete')</form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Add exam tab --}}
            <div class="{{ $errors->any() ? 'tab-pane fade show active' : 'tab-pane fade' }}" id="new-exam">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="exam-alert-info">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            <span>You are creating an Exam for the Current Session <strong>{{ Qs::getCurrentSession() }}</strong>.</span>
                            <span>
                                You may set the format for the exam number to be generated. Structure the number however you like. Use <strong>{{ Usr::getStudentExamNumberPlaceholder() }}</strong> character where the students numbers
                                need to be generated automatically ie., <strong>{{ Usr::getStudentExamNumberPlaceholder() }} (1 digit), {{ str_repeat(Usr::getStudentExamNumberPlaceholder(), 2) }} (2 digit) etc.</strong>, while other characters will remain constant. Allowed characters are both cases letters, slashes, dash and numbers.
                                You may edit the numbers later during print.
                            </span>
                        </div>
                    </div>
                </div>
                <form method="post" action="{{ route('exams.store') }}">
                    <div class="row">
                        <div class="col-md-7">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of Exam">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category" class="col-lg-3 col-form-label font-weight-semibold">Category <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select" data-summative_exm_cat_ids="{{ json_encode(Mk::getSummativeExamCategoryIds()) }}" class="form-control select-search" name="category_id" id="category">
                                        <option selected disabled value="">Select</option>
                                        @foreach ($exams_cat as $category)
                                        <option @selected(old('category_id')==$category->id) value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class-type" class="col-lg-3 col-form-label font-weight-semibold">Class Type <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select" class="form-control select-search" name="class_type_id" id="class-type">
                                        <option selected disabled value="">Select</option>
                                        @foreach ($class_types as $ct)
                                        <option @selected(old('class_type_id')==$ct->id) value="{{ $ct->id }}">{{ $ct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Term <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select" class="form-control select" name="term" id="term">
                                        <option selected disabled value="">Select</option>
                                        <option @selected(old('term')==1) value="1">First Term</option>
                                        <option @selected(old('term')==2) value="2">Second Term</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-7 col-form-label font-weight-semibold">Exam Denominator <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input name="exam_denominator" min="0" max="100" value="{{ old('exm_denominator') ?? 100 }}" required type="number" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label font-weight-semibold">Calculate Exam Student Position By <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    @foreach (Mk::getStudentExamPositionByValues() as $key => $value)
                                    <div class="d-flex mb-2">
                                        <span><input type="radio" id="exm-pos-by-{{ $key }}" name="exam_student_position_by_value" value="total" class="form-input-styled text-center" data-fouc></span>
                                        <label for="exm-pos-by-{{ $key }}" class="ml-2 w-50 m-auto-0">{{ $value }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="ca-setup {{ !in_array(old('category_id'), Mk::getSummativeExamCategoryIds()) ? 'display-none' : '' }}">
                                <strong>Exam CA setup</strong>
                                <div class="form-group row">
                                    <label class="col-lg-7 col-form-label font-weight-semibold">Class Work Denominator <span class="text-danger">*</span></label>
                                    <div class="col-lg-5">
                                        <input name="cw_denominator" min="0" max="100" value="{{ old('cw_denominator') ?? 10 }}" type="number" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-7 col-form-label font-weight-semibold">Home Work Denominator <span class="text-danger">*</span></label>
                                    <div class="col-lg-5">
                                        <input name="hw_denominator" min="0" max="100" value="{{ old('hw_denominator') ?? 10 }}" type="number" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-7 col-form-label font-weight-semibold">Topic Test Denominator <span class="text-danger">*</span></label>
                                    <div class="col-lg-5">
                                        <input name="tt_denominator" min="0" max="100" value="{{ old('tt_denominator') ?? 20 }}" type="number" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-7 col-form-label font-weight-semibold">Termed Test Denominator <span class="text-danger">*</span></label>
                                    <div class="col-lg-5">
                                        <input name="tdt_denominator" min="0" max="100" value="{{ old('tdt_denominator') ?? 60 }}" type="number" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-7 col-form-label font-weight-semibold">Calculate CA Student Position By <span class="text-danger">*</span></label>
                                    <div class="col-lg-5">
                                        @foreach(Mk::getStudentCAPositionByValues() as $key => $value)
                                        <div class="d-flex mb-2">
                                            <span><input type="radio" id="ca-position-by-{{ $key }}" name="ca_student_position_by_value" value="{{ $key }}" class="form-input-styled text-center" data-fouc></span>
                                            <label for="ca-position-by-{{ $key }}" class="ml-2 w-50 m-auto-0">{{ $value }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-12 number-formats">
                                    <label class="col-form-label font-weight-semibold">Number Format</label>
                                    <div class="text-info"><em>Select Class Type first</em></div>
                                    @foreach ($my_classes as $class)
                                    <div class="mb-2 div number-format-{{ $class->class_type_id }} d-none">
                                        <span class="m-auto">
                                            <input type="checkbox" id="class-id-{{ $class->id }}" name="class_id_{{ $class->id }}" value="{{ $class->id }}" class="form-input-styled text-center" data-fouc>
                                        </span>

                                        @if (old("class_id_{$class->id}") == $class->id)
                                        <script>
                                            // If the input was checked, set as clicked to check the previous checked checkbox(es) programmatically
                                            $("#class-id-{{ $class->id }}").click();

                                        </script>
                                        @endif

                                        <label for="class-id-{{ $class->id }}" class="ml-2 w-50 m-auto-0">{{ $class->name }}</label>
                                        <input name="exam_number_format_{{ $class->id }}" type="text" value="{{ old("exam_number_format_{$class->id}") }}" class="form-control" placeholder="ie., S6237-F{{ $class->id }}-{{ str_repeat(Usr::getStudentExamNumberPlaceholder(), 2) }}-{{ date('Y') }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>

            {{-- Announce exam tab --}}
            @if (session('announce_exam'))
            <div class="tab-pane fade show active" id="announce-exam">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="announce-exam-info">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            You have just published the exam titled "{{ session('exam_name') }}". Would you like to announce it to the public (i.e., have it appear on the login page)?
                            If <strong>yes</strong>, please fill in the form below to proceed. <strong>The form can only be submitted once at this time, and once submitted, the content cannot be edited</strong>. If you prefer not to announce it, you may ignore this step; it is optional.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" class="ajax-store" action="{{ route('exams.announce') }}">
                            @csrf
                            <input type="hidden" name="exam_id" value="{{ session('exam_id') }}">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-form-label font-weight-semibold">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" required class="form-control text-capitalize">{{ session('exam_name') }} exam results have been published, signin and check them now</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="duration" class="col-form-label font-weight-semibold">Duration
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="duration" value="259200" class="form-control" min="0" max="2505600" id="duration" name="duration" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">seconds</span>
                                        </div>
                                    </div>
                                    <span class="status-styled pt-2 pb-1">(default: 3 days = 259200 seconds)</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
