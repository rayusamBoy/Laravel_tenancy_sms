@extends('layouts.master')

@section('page_title', 'Manage Grades')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Grades</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-grades" class="nav-link active" data-toggle="tab">Manage Grades</a></li>
            <li class="nav-item"><a href="#new-grade" class="nav-link" data-toggle="tab"><i class="material-symbols-rounded">add</i> Add Grade</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-grades">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Grade Type</th>
                            <th>Range</th>
                            <th>Point</th>
                            <td>Credit</td>
                            <th>Remark</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $gr)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $gr->name }}</td>
                            <td>{{ $gr->class_type_id ? $class_types->where('id', $gr->class_type_id)->first()->name : 'Not Applicable'}}</td>
                            <td>{{ $gr->mark_from.' - '.$gr->mark_to }}</td>
                            <td>{{ $gr->point }}</td>
                            <td>{{ $gr->credit }}</td>
                            <td>{{ $gr->remark }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="material-symbols-rounded" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if(Qs::userIsTeamSA())
                                            {{--Edit--}}
                                            <a href="{{ route('grades.edit', $gr->id) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            @endif
                                            @if(Qs::userIsSuperAdmin())
                                            {{--Delete--}}
                                            <a id="{{ $gr->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ $gr->id }}" action="{{ route('grades.destroy', $gr->id) }}" class="hidden">@csrf @method('delete')</form>
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

            <div class="tab-pane fade" id="new-grade">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info border-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            <span>If the grade you are creating applies to all class types select <strong>Not Applicable.</strong> Otherwise select the Class Type that the grade applies to</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <form method="post" action="{{ route('grades.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control text-uppercase" placeholder="E.g., A">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Grade Type</label>
                                <div class="col-lg-9">
                                    <select class="form-control select" name="class_type_id" id="class_type_id">
                                        <option value="">Not Applicable</option>
                                        @foreach($class_types as $ct)
                                        <option {{ old('class_type_id') == $ct->id ? 'selected' : '' }} value="{{ $ct->id }}">{{ $ct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Mark From <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <input min="0" max="100" name="mark_from" value="{{ old('mark_from') }}" required type="number" class="form-control" placeholder="E.g., 0">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Mark To <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <input min="0" max="100" name="mark_to" value="{{ old('mark_to') }}" required type="number" class="form-control" placeholder="E.g., 35">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Point <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-3">
                                    <input name="point" min="0" max="10" required type="number" class="form-control" placeholder="E.g., 1">
                                </div>
                                <div class="col-lg-6 display-i-on-hover"><span class="font-weight-bold font-italic text-info-800">This point will be used in calculating student division.</span>
                                    <i class="material-symbols-rounded float-right text-info display-none" data-toggle="tooltip" title="Sample points: 1 &rArr; A, 2 &rArr; B, 3 &rArr; C, 4 &rArr; D, 5 &rArr; F, as well as 6 &rArr; S or 7 &rArr; F.">info</i>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Credit <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <input name="credit" min="0" max="5" required type="number" class="form-control" placeholder="E.g., 5">
                                </div>
                                <div class="col-lg-6 display-i-on-hover"><span class="font-weight-bold font-italic text-info-800">This weight will be used in calculating GPA. Usually, the highest grade tekes the highest credit, whereas other grades are scaled accordingly.</span>
                                    <i class="material-symbols-rounded float-right text-info display-none" data-toggle="tooltip" title="Sample credits: 5 &rArr; A, 4 &rArr; B, 3 &rArr; C, 2 &rArr; D, 1 &rArr; E, 0 &rArr; F, as well as 0.5 &rArr; S.">info</i>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Remark</label>
                                <div class="col-lg-9">
                                    <select class="form-control select" name="remark" id="remark">
                                        <option value="">Select Remark...</option>
                                        @foreach(Mk::getRemarks() as $rem)
                                        <option {{ old('remark') == $rem ? 'selected' : '' }} value="{{ $rem }}">{{ $rem }}</option>
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
        </div>
    </div>
</div>

{{--Grades List Ends--}}

@endsection