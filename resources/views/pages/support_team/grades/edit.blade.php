@extends('layouts.master')

@section('page_title', 'Edit Grade')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit Grade</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form method="post" action="{{ route('grades.update', $gr->id) }}">
                    @csrf @method('PUT')
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="name" value="{{ $gr->name }}" required type="text" class="form-control" placeholder="Eg. C4">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Grade Type</label>
                        <div class="col-lg-9">
                            <select class="form-control select" name="class_type_id" id="class_type_id">
                                <option value="">Not Applicable</option>
                                @foreach($class_types as $ct)
                                <option @selected($gr->class_type_id == $ct->id) value="{{ $ct->id }}">{{ $ct->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Mark From <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input name="mark_from" min="0" max="100" value="{{ $gr->mark_from }}" required type="number" class="form-control" placeholder="E.g., 0">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Mark To <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input name="mark_to" min="0" max="100" value="{{ $gr->mark_to }}" required type="number" class="form-control" placeholder="E.g., 35">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Point <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input name="point" min="1" max="7" value="{{ $gr->point }}" required type="number" class="form-control" placeholder="E.g., 1">
                        </div>
                        <div class="col-lg-6"><span class="font-weight-bold font-italic text-info-800">This point will be used in calculating student division.</span></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Credit <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input name="credit" step=".1" min="0" max="10" value="{{ $gr->credit }}" required type="number" class="form-control" placeholder="E.g., 5">
                        </div>
                        <div class="col-lg-6"><span class="font-weight-bold font-italic text-info-800">This credit will be used in calculating subject GPA.</span></div>
                    </div>

                    <div class="form-group row">
                        <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Remark</label>
                        <div class="col-lg-9">
                            <select class="form-control select" data-placeholder="Select Remark" name="remark" id="remark">
                                <option value="">Select Remark</option>
                                @foreach(Mk::getRemarks() as $rem)
                                <option @selected($gr->remark == $rem) value="{{ $rem }}">{{ $rem }}</option>
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

{{--Grade Edit Ends--}}

@endsection
