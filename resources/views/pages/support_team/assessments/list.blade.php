@extends('layouts.master')

@section('page_title', 'Continous Assessments List')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info border-0 alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span>You are viewing Continous Assessments (CA) list for <strong>all years.</strong> For actions about, consider navigating to <a href="{{ route('exams.index') }}">Exams List</a> instead.</span>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">List of Continous Assessments</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Term</th>
                            <th>Session</th>
                            <td>Category</td>
                            <td>Editable</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessments as $as)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $as->exam->name }} Assessment</td>
                            <td>{{ 'Term ' . $as->exam->term }}</td>
                            <td>{{ $as->exam->year }}</td>
                            <td>{{ $as->exam->category->name }}</td>
                            <td>{{ $as->exam->editable ? "Yes" : "No" }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{--Class List Ends--}}

@endsection
