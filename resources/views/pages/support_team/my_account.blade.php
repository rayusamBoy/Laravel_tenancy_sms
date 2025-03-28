@extends('layouts.master')

@section('page_title', 'My Account')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">My Account</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    @php
    $errors_has_password_error = str_contains($errors->first(), 'password');
    @endphp

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#change-pass" class="{{ $errors_has_password_error ? 'nav-link active' : ($errors->any() ? 'nav-link' : 'nav-link active') }}" data-toggle="tab">Change Password</a></li>

            @if(Qs::userIsPTACLA() || Qs::userIsItGuy())
            <li class="nav-item"><a href="#edit-profile" class="{{ $errors_has_password_error ? 'nav-link' : ($errors->any() ? 'nav-link active' : 'nav-link') }}" data-toggle="tab">Manage Profile</i></a></li>
            @endif

            <li class="nav-item"><a href="#other" class="nav-link" data-toggle="tab">Other</a></li>
        </ul>

        <div class="tab-content">
            <div class="{{ $errors_has_password_error ? 'tab-pane fade show active' : ($errors->any() ? 'tab-pane fade show' : 'tab-pane fade show active') }}" id="change-pass">
                <div class="row">
                    <div class="col-md-8">
                        <form method="post" action="{{ route('my_account.change_pass') }}">
                            @csrf @method('put')
                            {{--CURRENT PASSWORD--}}
                            <div class="form-group row">
                                <label for="current_password" class="col-lg-3 col-form-label font-weight-semibold">Current Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="current_password" name="current_password" required type="password" class="form-control" autocomplete="current-password">
                                </div>
                            </div>
                            {{--NEW PASSWORD--}}
                            <div class="form-group row">
                                <label for="new_password" class="col-lg-3 col-form-label font-weight-semibold">New Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="new_password" name="new_password" required type="password" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                            {{--CONFIRM PASSWORD--}}
                            <div class="form-group row">
                                <label for="new_password_confirmation" class="col-lg-3 col-form-label font-weight-semibold">Confirm Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="new_password_confirmation" name="new_password_confirmation" required type="password" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                            {{--SUBMIT BUTTON--}}
                            <div class="float-right">
                                <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if(Qs::userIsPTACLA() || Qs::userIsItGuy())
            <div class="{{ $errors_has_password_error ? 'tab-pane fade' : ($errors->any() ? 'tab-pane fade show active' : 'tab-pane fade') }}" id="edit-profile">
                <form enctype="multipart/form-data" method="post" action="{{ route('my_account.update') }}">
                    @csrf @method('put')
                    <div class="row">
                        <div class="col-md-10">
                            {{--NAME--}}
                            <div class="form-group row">
                                <label for="name" class="col-lg-3 col-form-label font-weight-semibold">Name</label>
                                <div class="col-lg-9">
                                    <input disabled="disabled" id="name" class="form-control" type="text" value="{{ $my->name }}">
                                </div>
                            </div>
                            {{--USERNAME--}}
                            @if($my->username)
                            <div class="form-group row">
                                <label for="username" class="col-lg-3 col-form-label font-weight-semibold">Username</label>
                                <div class="col-lg-9">
                                    <input disabled="disabled" id="username" class="form-control" type="text" value="{{ $my->username }}">
                                </div>
                            </div>

                            @else

                            <div class="form-group row">
                                <label for="username" class="col-lg-3 col-form-label font-weight-semibold">Username </label>
                                <div class="col-lg-9">
                                    <input id="username" name="username" type="text" class="form-control">
                                </div>
                            </div>
                            @endif
                            {{--DATE OF BIRTH--}}
                            <div class="form-group row">
                                <label for="dob" class="col-lg-3 col-form-label font-weight-semibold">Date of Birth </label>
                                <div class="col-lg-9">
                                    <input autocomplete="off" id="dob" name="dob" value="{{ $my->dob ?? old('dob') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{--EMAIL--}}
                            @if(auth()->user()->hasVerifiedEmail())
                            <div class="form-group row">
                                <label for="email" class="col-lg-3 col-form-label font-weight-semibold">Email <span class="text-success">(verified)</span></label>
                                <div class="col-lg-9">
                                    <input id="email" value="{{ $my->email ?? old('email') }}" name="email" type="email" class="form-control">
                                </div>
                            </div>
                            @else
                            <div class="form-group row">
                                <label for="email" class="col-lg-3 col-form-label font-weight-semibold">Email <span class="text-warning">(not verified)</span></label>
                                <div class="input-group col-lg-9">
                                    <input id="email" value="{{ $my->email ?? old('email') }}" name="email" type="email" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text border-0 bg-transparent default">@if(auth()->user()->email === null) <span class="text-warning font-weight-semi-bold">No Email</span> @else <a href="{{ route('verification.send') }}" class="text-success font-weight-semi-bold">Send Link</a> @endif</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{--PHONE--}}
                            <div class="form-group row">
                                <label for="phone" class="col-lg-3 col-form-label font-weight-semibold">Phone </label>
                                <div class="col-lg-9">
                                    <input id="phone" value="{{ $my->phone ?? old('phone') }}" name="phone" type="text" data-mask="+9999?999999999" placeholder="+255 1234 567 89" class="form-control">
                                </div>
                            </div>
                            {{--TELEPHONE--}}
                            <div class="form-group row">
                                <label for="phone2" class="col-lg-3 col-form-label font-weight-semibold">Telephone </label>
                                <div class="col-lg-9">
                                    <input id="phone2" value="{{ $my->phone2 ?? old('phone2') }}" name="phone2" type="text" data-mask="+9999?999999999" placeholder="+255123456789" class="form-control">
                                </div>
                            </div>
                            {{--PRIMARY ID--}}
                            <div class="form-group row">
                                <label for="primary_id" class="col-lg-3 col-form-label font-weight-semibold">Primary ID:</label>
                                <div class="col-lg-9">
                                    <input value="{{ $my->primary_id ?? old('primary_id') }}" type="text" name="primary_id" data-mask="www?wwwwwwwwwwwwwwwww" class="form-control" placeholder="123456789" id="primary_id">
                                </div>
                            </div>
                            {{--SECONDARY ID--}}
                            <div class="form-group row">
                                <label for="secondary_id" class="col-lg-3 col-form-label font-weight-semibold">Secondary ID:</label>
                                <div class="col-lg-9">
                                    <input value="{{ $my->secondary_id ?? old('secondary_id') }}" type="text" name="secondary_id" data-mask="www?wwwwwwwwwwwwwwwww" class="form-control" placeholder="12345678123451234512" id="secondary_id">
                                </div>
                            </div>
                            {{--ADDRESS--}}
                            <div class="form-group row">
                                <label for="address" class="col-lg-3 col-form-label font-weight-semibold">Address </label>
                                <div class="col-lg-9">
                                    <input id="address" value="{{ $my->address ?? old('address') }}" name="address" type="text" class="form-control">
                                </div>
                            </div>
                            {{--PHOTO--}}
                            <div class="form-group row">
                                <label for="photo" class="col-lg-3 col-form-label font-weight-semibold">Change Photo </label>
                                <div class="col-lg-9">
                                    <input accept="image/*" type="file" name="photo" id="photo" class="form-input-styled" data-fouc>
                                </div>
                            </div>

                            @if((Qs::userIsTeamSATCL() && $staff_rec?->staff_data_edit) || Qs::userIsItGuy() || Qs::userIsTeamSA())

                            <hr>

                            {{--DATE OF EMPLOYMENT--}}
                            <div class="form-group row">
                                <label for="emp-date" class="col-lg-3 col-form-label font-weight-semibold">Date of Employment:</label>
                                <div class="col-lg-9">
                                    <input autocomplete="off" id="emp-date" name="emp_date" value="{{ $staff_rec->emp_date ?? old('emp_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{--DATE OF CONFIRMATION--}}
                            <div class="form-group row">
                                <label for="confirmation-date" class="col-lg-3 col-form-label font-weight-semibold">Date of Confirmation:</label>
                                <div class="col-lg-9">
                                    <input autocomplete="off" id="confirmation-date" name="confirmation_date" value="{{ $staff_rec->confirmation_date ?? old('confirmation_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{--LICENCE NUMBER--}}
                            <div class="form-group row">
                                <label for="licence_number" class="col-lg-3 col-form-label font-weight-semibold">Licence Number:</label>
                                <div class="col-lg-9">
                                    <input name="licence_number" value="{{ $staff_rec->licence_number ?? old('licence_number') }}" type="text" class="form-control" placeholder="Ie., LTT 12345" id="licence_number">
                                </div>
                            </div>
                            {{--FILE NUMBER--}}
                            <div class="form-group row">
                                <label for="file_number" class="col-lg-3 col-form-label font-weight-semibold">File Number:</label>
                                <div class="col-lg-9">
                                    <input name="file_number" value="{{ $staff_rec->file_number ?? old('file_number') }}" type="text" class="form-control" placeholder="EPP/123/12/1" id="file_number">
                                </div>
                            </div>
                            {{--SOCIAL SECURITY NUMBER--}}
                            <div class="form-group row">
                                <label for="ss_number" class="col-lg-3 col-form-label font-weight-semibold">Social Security Number:</label>
                                <div class="col-lg-9">
                                    <input name="ss_number" value="{{ $staff_rec->ss_number ?? old('ss_number') }}" type="number" min="0" class="form-control" id="ss_number">
                                </div>
                            </div>
                            {{--EMPLOYMENT NUMBER--}}
                            <div class="form-group row">
                                <label for="emp_no" class="col-lg-3 col-form-label font-weight-semibold">Employment Number:</label>
                                <div class="col-lg-9">
                                    <input name="emp_no" value="{{ $staff_rec->emp_no ?? old('emp_no') }}" type="number" min="0" class="form-control" id="emp_no">
                                </div>
                            </div>
                            {{--TIN NUMBER--}}
                            <div class="form-group row">
                                <label for="tin_number" class="col-lg-3 col-form-label font-weight-semibold">TIN Number:</label>
                                <div class="col-lg-9">
                                    <input name="tin_number" value="{{ $staff_rec->tin_number ?? old('tin_number') }}" type="number" min="0" class="form-control" id="tin_number">
                                </div>
                            </div>
                            {{--BANK ACCOUNT NUMBER--}}
                            <div class="form-group row">
                                <label for="bank_acc_no" class="col-lg-3 col-form-label font-weight-semibold">Bank Account Number:</label>
                                <div class="col-lg-9">
                                    <input name="bank_acc_no" value="{{ $staff_rec->bank_acc_no ?? old('bank_acc_no') }}" type="number" class="form-control" id="bank_acc_no">
                                </div>
                            </div>
                            {{--BANK NAME--}}
                            <div class="form-group row">
                                <label for="bank_name" class="col-lg-3 col-form-label font-weight-semibold">Bank Name:</label>
                                <div class="col-lg-9">
                                    <input name="bank_name" value="{{ $staff_rec->bank_name ?? old('bank_name') }}" type="text" class="form-control" id="bank_name">
                                </div>
                            </div>
                            {{--EDUCATION LEVEL--}}
                            <div class="form-group row">
                                <label for="education_level" class="col-lg-3 col-form-label font-weight-semibold">Education Level: </label>
                                <div class="col-lg-9">
                                    <select class="select-search form-control" id="education_level" name="education_level" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach(Usr::getEducationLevels() as $lv)
                                        <option @selected($staff_rec->education_level == $lv) value="{{ $lv ?? old('education_level') }}">{{ $lv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--COLLEGE ATTENDED--}}
                            <div class="form-group row">
                                <label for="college_attended" class="col-lg-3 col-form-label font-weight-semibold">College Attended: </label>
                                <div class="col-lg-9">
                                    <input name="college_attended" value="{{ $staff_rec->college_attended ?? old('college_attended') }}" type="text" class="form-control" id="college_attended">
                                </div>
                            </div>
                            {{--GRADUATION YEAR--}}
                            <div class="form-group row">
                                <label for="year_graduated" class="col-lg-3 col-form-label font-weight-semibold">Graduation Year: </label>
                                <div class="col-lg-9">
                                    <select name="year_graduated" data-placeholder="Choose..." id="year_graduated" class="select-search form-control">
                                        <option value=""></option>
                                        @for($y=date('Y', strtotime('- 30 years')); $y<=date('Y'); $y++) <option @selected(($staff_rec->year_graduated == $y)) value="{{ $y ?? old('year_graduated') }}">{{ $y }} </option> @endfor
                                    </select>
                                </div>
                            </div>
                            {{--ROLE--}}
                            <div class="form-group row">
                                <label for="role" class="col-lg-3 col-form-label font-weight-semibold">Role: </label>
                                <div class="col-lg-9">
                                    <select class="select-search form-control" id="role" name="role" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach(Usr::getStaffRoles() as $role)
                                        <option @selected($staff_rec->role == $role) value="{{ $role }}">{{ $role ?? old('role') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if (!Qs::userIsItGuy())
                            {{--NUMBER OF PERIODS--}}
                            <div class="form-group row">
                                <label for="no_of_periods" class="col-lg-3 col-form-label font-weight-semibold">Number of Periods:</label>
                                <div class="col-lg-9">
                                    <select name="no_of_periods" data-placeholder="Choose..." id="no_of_periods" class="select-search form-control">
                                        <option value=""></option>
                                        @for($i=1; $i<=30; $i++) <option @selected(($staff_rec->no_of_periods == $i)) value="{{ $i ?? old('no_of_periods') }}">{{ $i }} </option> @endfor
                                    </select>
                                </div>
                            </div>
                            @endif
                            {{--PLACE OF LIVING--}}
                            <div class="form-group row">
                                <label for="place_of_living" class="col-lg-3 col-form-label font-weight-semibold">Place of Living: </label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="place_of_living" id="place_of_living" value="{{ $staff_rec->place_of_living ?? old('place_of_living') }}" placeholder="Place of living">
                                </div>
                            </div>
                            {{--SUBJECTS STUDIED--}}
                            <div class="form-group row">
                                <label for="subjects_studied" class="col-lg-3 col-form-label font-weight-semibold">Subjects Studied <span class="text-info">(comma (,) separated)</span>: </label>
                                <div class="col-lg-9">
                                    <textarea name="subjects_studied" id="subjects_studied" class="form-control" placeholder="ie., Subject one, Subject two, ...">{{ implode(",", json_decode($staff_rec->subjects_studied ?? "") ?? []) }}</textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="float-right">
                                <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif

            <div class="tab-pane fade" id="other">
                <form method="post" action="{{ route('my_account.other') }}">
                    @csrf @method('put')
                    <div class="row">
                        <div class="col-md-4 d-none d-md-block">
                            {{--SIDE BAR MINIMIZED--}}
                            <div class="form-group row">
                                <label for="sidebar_minimized" class="col-lg-8 col-form-label font-weight-semibold">Minimize Sidebar <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <select class="form-control select" name="sidebar_minimized" id="sidebar_minimized">
                                        <option @selected(auth()->user()->sidebar_minimized == 1) value="1">Yes</option>
                                        <option @selected(auth()->user()->sidebar_minimized == 0) value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{--SOUND NOTIFICATIONS--}}
                            <div class="form-group row">
                                <label for="allow_system_sounds" class="col-lg-9 col-form-label font-weight-semibold">Allow System Sounds (ie., new message or notification) <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <select class="form-control select" name="allow_system_sounds" id="allow_system_sounds">
                                        <option @selected(auth()->user()->allow_system_sounds) value="1">Yes</option>
                                        <option @selected(auth()->user()->allow_system_sounds) value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            {{--SUBMIT BUTTON--}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-right float-right">
                                        <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-8 col-form-label font-weight-semibold">Reveall all hidden alert messages</label>
                            <div class="col-4 text-right">
                                <button type="button" id="clear-do-not-show-again-alert-msgs" class="btn btn-warning btn-sm">Reveal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--My Profile Ends--}}

@endsection
