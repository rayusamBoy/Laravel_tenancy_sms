@extends('layouts.master')

@section('page_title', 'Manage Divisions')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Divisions</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-divisions" class="nav-link active" data-toggle="tab">Manage Divisions</a></li>
            <li class="nav-item"><a href="#new-grade" class="nav-link" data-toggle="tab"><i class="material-symbols-rounded">add</i> Add Division</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-divisions">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Division Type</th>
                            <th>Range</th>
                            <th>Remark</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($divisions as $dv)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dv->name }}</td>
                            <td>{{ $dv->class_type_id ? $class_types->where('id', $dv->class_type_id)->first()->name : 'Not Applicable'}}</td>
                            <td>{{ "{$dv->points_from} - {$dv->points_to}" }}</td>
                            <td>{{ $dv->remark }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="javascript:;" class="material-symbols-rounded" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if(Qs::userIsTeamSA())
                                            {{--Edit--}}
                                            <a href="{{ route('divisions.edit', $dv->id) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            @endif
                                            @if(Qs::userIsSuperAdmin())
                                            {{--Delete--}}
                                            <a id="{{ $dv->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ $dv->id }}" action="{{ route('divisions.destroy', $dv->id) }}" class="hidden">@csrf @method('delete')</form>
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

                            <span>If The division you are creating applies to all class types select <strong>Not Applicable.</strong> Otherwise select the Class Type That the grade applies to</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="{{ route('divisions.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control text-uppercase" placeholder="E.g. I">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Division Type</label>
                                <div class="col-lg-9">
                                    <select class="form-control select" name="class_type_id" id="class_type_id">
                                        <option value="">Not Applicable</option>
                                        @foreach($class_types as $ct)
                                        <option @selected(old('class_type_id')==$ct->id) value="{{ $ct->id }}">{{ $ct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Points From <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <input min="0" max="100" name="points_from" value="{{ old('points_from') }}" required type="number" class="form-control" placeholder="0">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Points To <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <input min="0" max="100" name="points_to" value="{{ old('points_to') }}" required type="number" class="form-control" placeholder="0">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Remark</label>
                                <div class="col-lg-9">
                                    <select class="form-control select" data-placeholder="Select Remark" name="remark" id="remark">
                                        <option value="">Select Remark</option>
                                        @foreach(Mk::getRemarks() as $rem)
                                        <option @selected(old('remark')==$rem) value="{{ $rem }}">{{ $rem }}</option>
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

{{--Class List Ends--}}

@endsection
