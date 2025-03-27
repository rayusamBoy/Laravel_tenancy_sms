@extends('layouts.master')

@section('page_title', 'Select Exam Year')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title"><i class="material-symbols-rounded mr-2">alarm</i> Select Exam Year</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form method="post" action="{{ route('marks.year_select', $student_id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="year" class="font-weight-bold col-form-label-lg">Select Exam Year:</label>
                        <select required id="year" name="year" data-placeholder="Select Exam Year" class="form-control select select-lg">
                            @foreach($years as $y)
                            <option value="{{ $y->year }}">{{ $y->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary btn-lg">Submit <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
