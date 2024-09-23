@extends('layouts.master')
@section('page_title', 'Manage Subjects')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Subjects</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-subject" class="nav-link active" data-toggle="tab">Add Subject</a></li>
            <li class="nav-item dropdown">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Subjects</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($my_classes as $c)
                    <a href="#c{{ $c->id }}" class="dropdown-item" data-toggle="tab">{{ $c->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show  active fade" id="new-subject">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info border-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            <span>
                                If the Subject you are adding applies to all sections or does not apply at all just leave <strong>Not Applicable</strong> in the section field.
                                Otherwise select the section that the subject applies to. If it only applies to specific student(s), select the student(s) fron the student field.
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <form class="ajax-store" method="post" action="{{ route('subjects.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Select Class <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Class" onchange="getClassTypeSubjects(this.options[this.selectedIndex].dataset.class_type_id); getClassSections(this.value, '#sec_id_add_not_applicable'); getClassStudents(this.value)" class="form-control select" name="my_class_id" id="my_class_id">
                                        <option value=""></option>
                                        @foreach($my_classes as $c)
                                        <option data-class_type_id="{{ $c->class_type_id }}" {{ old('my_class_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sec_id_add_not_applicable" class="col-lg-3 col-form-label font-weight-semibold">Section:</label>
                                <div class="col-lg-9">
                                    <select id="sec_id_add_not_applicable" name="section_id" data-placeholder="Not Apllicable" class="form-control select">
                                        <option selected value="">Not Applicable</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="students-ids" class="col-lg-3 col-form-label font-weight-semibold">Select Student(s):</label>
                                <div class="col-lg-9">
                                    <select id="students-ids" onchange="updateSectionDisableState()" name="students_ids[]" multiple="multiple" class="form-control select">
                                        <option disabled value="">Select class first</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Name of subject" class="form-control select-search append-editable-option" name="name" id="name">
                                        <option selected value="">Select class first</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="slug" class="col-lg-3 col-form-label font-weight-semibold">Short Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="slug" required name="slug" value="{{ old('slug') }}" type="text" class="form-control" placeholder="Eg. B.Eng">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="core" class="col-lg-3 col-form-label font-weight-semibold">Core Subject</label>
                                <div class="col-lg-2">
                                    <select class="form-control select" name="core" id="core">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-lg-7">
                                    <span class="font-weight-bold font-italic text-info-800">{{ __('msg.core_subject') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Teacher <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Teacher" class="form-control select-search" name="teacher_id" id="teacher_id">
                                        <option value=""></option>
                                        @foreach($teachers as $t)
                                        <option {{ old('teacher_id') == Qs::hash($t->id) ? 'selected' : '' }} value="{{ Qs::hash($t->id) }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach($my_classes as $c)
            <div class="tab-pane fade" id="c{{ $c->id }}">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Short Name</th>
                            <td>Core</td>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Teacher</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects->where('my_class_id', $c->id) as $s)
                        @if($s->record->first())
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->name }} </td>
                            <td>{{ $s->slug }} </td>
                            <td>{{ ($s->core == 1) ? 'Yes' : 'No' }}</td>
                            <td>{{ $s->my_class->name }}</td>
                            <td>
                                @foreach($s->record as $key => $rec)

                                @if(isset($rec->section->name))
                                {{ $rec->section->name }}

                                @elseif(isset($rec->students_ids))
                                @include('pages/modals/subject_assigned_students')
                                <a href="javascript:;" data-toggle="modal" data-target="#subjects-assigned-students-{{ $rec->id }}">View Assigned</a>

                                @else
                                Not Apllicable
                                @endif

                                @if($key != array_key_last($s->record->toArray()))
                                <hr class="m-0 divider" />
                                @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($s->record as $key => $rec)
                                {{ $rec->teacher->name }}
                                @if($key != array_key_last($s->record->toArray()))
                                <hr class="m-0 divider" />
                                @endif
                                @endforeach
                            </td>
                            <td class="text-center">
                                @foreach($s->record as $key => $rec)
                                
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- Edit --}}
                                            <a href="{{ route('subjects.edit_record', $rec->id) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            {{-- Delete --}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ $s->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ $s->id }}" action="{{ route('subjects.delete_record', [$s->id, $rec->id]) }}" class="hidden">@csrf @method('delete')</form>            
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($key != array_key_last($s->record->toArray()))
                                <br />
                                @endif
                                @endforeach
                            </td>
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
            @endforeach

        </div>
    </div>
</div>

{{-- Subject List Ends --}}

@endsection
