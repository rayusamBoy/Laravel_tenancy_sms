@extends('layouts.master')

@section('page_title', 'Edit Division')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit Division</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form method="post" action="{{ route('divisions.update', $dv->id) }}">
                    @csrf @method('PUT')
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="name" value="{{ $dv->name }}" required type="text" class="form-control" placeholder="Eg. C4">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Division Type</label>
                        <div class="col-lg-9">
                            <select class="form-control select" name="class_type_id" id="class_type_id">
                                <option value="">Not Applicable</option>
                                @foreach($class_types as $ct)
                                <option @selected($dv->class_type_id == $ct->id) value="{{ $ct->id }}">{{ $ct->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Points From <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input name="points_from" min="0" max="100" value="{{ $dv->points_from }}" required type="number" class="form-control" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Points To <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input name="points_to" min="0" max="100" value="{{ $dv->points_to }}" required type="number" class="form-control" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Remark</label>
                        <div class="col-lg-9">
                            <select class="form-control select" data-placeholder="Select Remark" name="remark" id="remark">
                                <option value="">Select Remark</option>
                                @foreach(Mk::getRemarks() as $rem)
                                <option @selected($dv->remark == $rem) value="{{ $rem }}">{{ $rem }}</option>
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

@endsection
