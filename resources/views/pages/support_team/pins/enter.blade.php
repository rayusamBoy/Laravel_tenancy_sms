@extends('layouts.master')

@section('page_title', 'Enter PIN')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title"><i class="material-symbols-rounded mr-2">alarm</i> Enter PIN</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form method="post" action="{{ route('pins.verify', Qs::hash($student->id)) }}">
                    @csrf
                    <div class="form-group">
                        <label for="pin_code" class="font-weight-bold col-form-label">Enter Exam Pin for <span class="text-success font-size-lg">{{ $student->name }}</span></label>
                        <input title="XXXXX-XXXXX-XXXXXX" class="form-control form-control-lg" placeholder="XXXXX-XXXXX-XXXXXX" style="text-transform:uppercase" pattern="[A-Za-z0-9]{5}-[A-Za-z0-9]{5}-[A-Za-z0-9]{6}" required name="pin_code" autocomplete="off" value="{{ old('pin_code') }}" type="text">
                    </div>

                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-danger btn-lg">Submit <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
