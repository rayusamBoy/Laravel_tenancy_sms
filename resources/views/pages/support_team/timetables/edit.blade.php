@extends('layouts.master')

@section('page_title', 'Edit TimeTable Record')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit TimeTable Record</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="col-md-8">
            <form class="ajax-update" method="post" action="{{ route('ttr.update', $ttr->id) }}">
                @csrf @method('PUT')
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <input name="name" value="{{ $ttr->name }}" required type="text" class="form-control" placeholder="Name of TimeTable">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="exam_id" class="col-lg-3 col-form-label font-weight-semibold">Type (Class or Exam)</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" disabled value="{{ $ttr->exam?->name ?? 'Class Timetable' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <select required data-placeholder="Select Class" onchange="getClassSections(this.value)" class="form-control select" name="my_class_id" id="my_class_id">
                            @foreach($my_classes as $mc)
                            <option @selected($ttr->my_class_id == $mc->id) value="{{ $mc->id }}">{{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- If timetable type is class --}}
                @if($ttr->exam_id === null)
                <div class="form-group row">
                    <label for="section_id" class="col-lg-3 col-form-label font-weight-semibold">Section <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <select required data-placeholder="Select Section" class="form-control select" name="section_id" id="section_id">
                            @foreach($sections as $section)
                            <option @selected($ttr->section_id == $section->id) value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                            <option @selected($ttr->section_id == null) value="all">All</option>
                        </select>
                    </div>
                </div>
                @endif

                <div class="text-right">
                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--TimeTable Edit Ends--}}

@endsection
