@extends('layouts.master')
@section('page_title', 'Students ID cards')
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="material-symbols-rounded mr-2">id_card</i> Students ID cards</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('students.id_cards_manage') }}">
            @csrf
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="my_class_id" class="col-form-label font-weight-bold">Class: <span class="text-danger">*</span></label>
                    <select required id="my_class_id" name="my_class_id" class="form-control select">
                        <option value="">Select Class</option>
                        @foreach($my_classes as $c)
                        <option {{ ($selected && $my_class_id == $c->id || old('my_class_id') == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 form-group">
                    <label for="issued_date" class="col-form-label font-weight-bold">Issued Date:</label>
                    <input name="issued_date" value="{{ date('M, Y') }}" type="text" class="form-control date-pick-day-none" placeholder="Select Date...">
                </div>

                <div class="col-md-2 form-group">
                    <label for="expire_date" class="col-form-label font-weight-bold">Expire Date: <span class="text-danger">*</span></label>
                    <input name="expire_date" value="{{ ($selected) ? $expire_date : old('expire_date') }}" type="text" class="form-control date-pick-day-none" placeholder="Select Date..." required>
                </div>

                <div class="form-group col-md-2">
                    <label for="id_theme" class="col-form-label font-weight-bold">Theme: </label>
                    <select required id="id_theme" name="id_theme" data-placeholder="Select Theme" class="form-control select">
                        <option selected value="">Select Theme</option>
                        @foreach ($theme_names as $name)
                            <option @if(old('id_theme') === $name) selected @endif value="{{ $name }}">theme {{ $loop->iteration }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label for="brightness" class="col-form-label font-weight-bold">Brightness: <span class="text-info">*</span></label>
                    <input class="form-control" type="number" value="{{ old('brightness') }}" name="brightness" step=".01" placeholder="Type a number" min="1" max="100">
                </div>

                <div class="form-group col-md-2">
                    <label for="phone" class="col-form-label font-weight-bold">Phone number: <span class="text-danger">*</span></label>
                    <input required type="text" value="{{ ($selected) ? $phone : old('phone') }}" name="phone" data-mask="+999 999 999 999" placeholder="+255 123 456 789" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 d-flex">
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="class_from" class="col-form-label font-weight-bold">Class from:</label>
                            <select id="class_from" name="class_from" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                <option {{ ($selected && $class_from == $c->name) ? 'selected' : '' }} value="{{ $c->name }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <i class="material-symbols-rounded pr-3 pl-3 m-auto">arrow_right_alt</i>

                    <div class="form-group row">
                        <div class="col-12">
                            <label for="class_to" class="col-form-label font-weight-bold">Class to:</label>
                            <select id="class_to" name="class_to" class="form-control select">
                                <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                <option {{ ($selected && $class_to == $c->name) ? 'selected' : '' }} value="{{ $c->name }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                 <div class="form-group col-md-3">
                    <label for="web-link" class="col-form-label font-weight-bold">Website Link:</label>
                    <input id="web-link" type="text" name="web_link" class="form-control">
                </div>

                <div class="form-group col-md-4">
                    <label for="motto" class="col-form-label font-weight-bold">Motto: <span class="text-danger">*</span></label>
                    <input required type="text" value="BETTER EDUCATION FOR BETTER LIFE" name="motto" id="motto" class="form-control text-uppercase">
                </div>

                <div class="form-group mt-md-4 col-md-1">
                    <div class="text-right mt-1">
                        <button type="submit" class="btn btn-primary"><i class="material-symbols-rounded">send</i></button>
                    </div>
                </div>
            </div>
            <div>
                <span class="text-info font-weight-bold">*</span> <span class="font-weight-bold font-italic text-info-800">{{ __('msg.image_print_brightness') }}</span>
            </div>
        </form>
    </div>
</div>

@if($selected)
{{--Info--}}
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info border-0 alert-dismissible has-do-not-show-again-button" id="id-cards-info">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span>
                Here you can print Students' cards as per the details. Click the <strong>Print ID Cards For All Students</strong> button right below to print all the students' card
                of the selected class. Otherwise select the specific student(s) by checking the check boxes in the table beside the student record. Then, click the
                <strong>Print ID Cards For the Selected Student(s)</strong> button to print the card(s) when done.
            </span>
        </div>
    </div>
</div>
{{--Warning--}}
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger border-0 alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span>Make sure all required students' data are filled in the data table below. Otherwise, consider <a target="_self" href="{{ route('students.list', $my_class_id) }}">updating</a> the data before proceeding.</span>
        </div>
    </div>
</div>
<div class="card">
    <div class="form-group mt-3 mr-3">
        <div class="text-right mt-1">
            <a target="_blank" href="{{ route('students.print_class_id_cards', [$my_class_id, $issued_date, $expire_date, $id_theme, $phone, $class_from == '' ? 'NULL' : $class_from, $class_to == '' ? 'NULL' : $class_to, $motto, $brightness ?? 1, $web_link == '' ? 'NULL' : $web_link]) }}" class="btn btn-secondary btn-sm">Print ID Cards For All Students <i class="material-symbols-rounded ml-2">print</i></a>
        </div>
    </div>
    <form method="post" class="print-selected" action="{{ route('students.print_selected_id_cards') }}">
        @csrf

        <input type="hidden" name="issued" value="{{ $issued_date }}">
        <input type="hidden" name="expire" value="{{ $expire_date }}">
        <input type="hidden" name="theme" value="{{ $id_theme }}">
        <input type="hidden" name="phone" value="{{ $phone }}">
        <input type="hidden" name="class_from" value="{{ $class_from }}">
        <input type="hidden" name="class_to" value="{{ $class_to }}">
        <input type="hidden" name="motto" value="{{ $motto }}">
        <input type="hidden" name="brightness" value="{{ $brightness }}">
        <input type="hidden" name="web_link" value="{{ $web_link }}">

        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Check</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Admission Number</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $s)
                    <tr>
                        <td><input type="checkbox" name="students_ids[]" value="{{ $s->user->id }}" class="form-input-styled text-center st-id-checkbox" data-fouc></td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ Usr::getTenantAwarePhoto($s->user->photo) }}" alt="photo"></td>
                        <td>{{ $s->user->name }}</td>
                        <td>{{ $s->adm_no }}</td>
                        <td>{{ $s->user->gender }}</td>
                        <td>{{ $s->user->dob }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="form-group mt-md-4 mr-3">
            <div class="text-right mt-1">
                <button type="submit" disabled class="btn btn-primary cursor-not-allowed"><i class="material-symbols-rounded mr-2">print</i>Print ID Cards For the<strong class="ml-1 mr-1" id="checked-count">0</strong>Selected Student(s)</button>
            </div>
        </div>
    </form>
</div>

@endif
@endsection
