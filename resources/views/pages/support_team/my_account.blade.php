@extends('layouts.master')
@section('page_title', 'My Account')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">My Account</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    {{-- Get password substring position from error message --}}
    @php $get_p_sub_str_pos_from_err_msg = strpos($errors->first(), 'password') @endphp

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#change-pass" class="{{ ($get_p_sub_str_pos_from_err_msg != false ? 'nav-link active' : ($errors->any() ? 'nav-link' : 'nav-link active')) }}" data-toggle="tab">Change Password</a></li>
            @if(Qs::userIsPTACLA() || Qs::userIsItGuy())
            <li class="nav-item"><a href="#edit-profile" class="{{ ($get_p_sub_str_pos_from_err_msg != false ? 'nav-link' : ($errors->any() ? 'nav-link active' : 'nav-link')) }}" data-toggle="tab">Manage Profile</i></a></li>
            @endif
            <li class="nav-item"><a href="#other" class="nav-link" data-toggle="tab">Other</a></li>
        </ul>

        <div class="tab-content">
            <div class="{{ ($get_p_sub_str_pos_from_err_msg != false ? 'tab-pane fade show active' : ($errors->any() ? 'tab-pane fade show' : 'tab-pane fade show active')) }}" id="change-pass">
                <div class="row">
                    <div class="col-md-8">
                        <form method="post" action="{{ route('my_account.change_pass') }}">
                            @csrf @method('put')
                            {{--CURRENT PASSWORD--}}
                            <div class="form-group row">
                                <label for="current_password" class="col-lg-3 col-form-label font-weight-semibold">Current Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="current_password" name="current_password" required type="password" class="form-control">
                                </div>
                            </div>
                            {{--NEW PASSWORD--}}
                            <div class="form-group row">
                                <label for="new_password" class="col-lg-3 col-form-label font-weight-semibold">New Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="new_password" name="new_password" required type="password" class="form-control">
                                </div>
                            </div>
                            {{--CONFIRM PASSWORD--}}
                            <div class="form-group row">
                                <label for="new_password_confirmation" class="col-lg-3 col-form-label font-weight-semibold">Confirm Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="new_password_confirmation" name="new_password_confirmation" required type="password" class="form-control">
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
            <div class="{{ ($get_p_sub_str_pos_from_err_msg != false ? 'tab-pane fade' : ($errors->any() ? 'tab-pane fade show' : 'tab-pane fade')) }}" id="edit-profile">
                <form enctype="multipart/form-data" method="post" action="{{ route('my_account.update') }}">
                    @csrf @method('put')
                    <div class="row">
                        <div class="col-md-8">
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
                                        <span class="input-group-text border-0 bg-transparent default"><a href="{{ route('verification.send') }}" class="text-success font-weight-semi-bold">Send Link</a></span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{--PHONE--}}
                            <div class="form-group row">
                                <label for="phone" class="col-lg-3 col-form-label font-weight-semibold">Phone </label>
                                <div class="col-lg-9">
                                    <input id="phone" value="{{ $my->phone ?? old('phone') }}" name="phone" type="text" data-mask="+999 9999 999 99" placeholder="+255 1234 567 89" class="form-control">
                                </div>
                            </div>
                            {{--TELEPHONE--}}
                            <div class="form-group row">
                                <label for="phone2" class="col-lg-3 col-form-label font-weight-semibold">Telephone </label>
                                <div class="col-lg-9">
                                    <input id="phone2" value="{{ $my->phone2 ?? old('phone2') }}" name="phone2" type="text" data-mask="+999 9999 999 99" placeholder="+255 1234 567 89" class="form-control">
                                </div>
                            </div>
                            {{--PRIMARY ID--}}
                            <div class="form-group row">
                                <label for="primary_id" class="col-lg-3 col-form-label font-weight-semibold">Primary ID:</label>
                                <div class="col-lg-9">
                                    <input value="{{ $my->primary_id ?? old('primary_id') }}" type="text" name="primary_id" data-mask="999999999" class="form-control" placeholder="123456789" id="primary_id">
                                </div>
                            </div>
                            {{--SECONDARY ID--}}
                            <div class="form-group row">
                                <label for="secondary_id" class="col-lg-3 col-form-label font-weight-semibold">Secondary ID:</label>
                                <div class="col-lg-9">
                                    <input value="{{ $my->secondary_id ?? old('secondary_id') }}" type="text" name="secondary_id" data-mask="99999999-99999-99999-99" class="form-control" placeholder="12345678-12345-12345-12">
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
                                <label for="address" class="col-lg-3 col-form-label font-weight-semibold">Change Photo </label>
                                <div class="col-lg-9">
                                    <input accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                </div>
                            </div>

                            @if((Qs::userIsSuperAdmin() || (Qs::userIsPTACLA()) && $staff_rec != null && $staff_rec->staff_data_edit === 1) || Qs::userIsItGuy())

                            <hr class="divider">

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
                                <label class="col-lg-3 col-form-label font-weight-semibold">Licence Number:</label>
                                <div for="licence_number" class="col-lg-9">
                                    <input name="licence_number" value="{{ $staff_rec->licence_number ?? old('licence_number') }}" type="text" class="form-control" data-mask="LTT 99999" placeholder="LTT 12345" id="licence_number">
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
                                <label for="tin_number" class="col-lg-3 col-form-label font-weight-semibold">Tin Number:</label>
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
                                <label for="education" class="col-lg-3 col-form-label font-weight-semibold">Education Level: </label>
                                <div class="col-lg-9">
                                    <select class="select-search form-control" id="education_level" name="education_level" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach(Usr::getEducationLevels() as $lv)
                                        <option {{ ($staff_rec->education_level == $lv ? 'selected' : '') }} value="{{ $lv ?? old('education_level') }}">{{ $lv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--COLLEGE ATTENDED--}}
                            <div class="form-group row">
                                <label for="college_attended" class="col-lg-3 col-form-label font-weight-semibold">College Ateended: </label>
                                <div class="col-lg-9">
                                    <input name="college_attended" value="{{ $staff_rec->college_attended ?? old('college_attended') }}" type="number" class="form-control" id="college_attended">
                                </div>
                            </div>
                            {{--GRADUATION YEAR--}}
                            <div class="form-group row">
                                <label for="year_graduated" class="col-lg-3 col-form-label font-weight-semibold">Graduation Year: </label>
                                <div class="col-lg-9">
                                    <select name="year_graduated" data-placeholder="Choose..." id="year_graduated" class="select-search form-control">
                                        <option value=""></option>
                                        @for($y=date('Y', strtotime('- 30 years')); $y<=date('Y'); $y++) <option {{ ($staff_rec->year_graduated == $y) ? 'selected' : '' }} value="{{ $y ?? old('year_graduated') }}">{{ $y }} </option> @endfor
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
                                        <option {{ ($staff_rec->role == $role ? 'selected' : '') }} value="{{ $role }}">{{ $role ?? old('role') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--NUMBER OF PERIODS--}}
                            <div class="form-group row">
                                <label for="no_of_periods" class="col-lg-3 col-form-label font-weight-semibold">Number of Periods:</label>
                                <div class="col-lg-9">
                                    <select name="no_of_periods" data-placeholder="Choose..." id="no_of_periods" class="select-search form-control">
                                        <option value=""></option>
                                        @for($i = 1; $i<=30; $i++) <option {{ ($staff_rec->no_of_periods == $i) ? 'selected' : '' }} value="{{ $i ?? old('no_of_periods') }}">{{ $i }} </option> @endfor
                                    </select>
                                </div>
                            </div>
                            {{--PLACE OF LIVING--}}
                            <div class="form-group row">
                                <label for="place_of_living" class="col-lg-3 col-form-label font-weight-semibold">Place of Living: </label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="place_of_living" id="place_of_living" value="{{ $staff_rec->place_of_living ?? old('place_of_living') }}" placeholder="Where you live?">
                                </div>
                            </div>
                            @endif
                        </div>

                        @if((Qs::userIsSuperAdmin() || (Qs::userIsPTACLA()) && $staff_rec != null && $staff_rec->staff_data_edit === 1) || Qs::userIsItGuy())
                        <div class="col-md-4">
                            <div class="row m-0">
                                {{--SUBJECTS STUDIED--}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="role">Subjects Studied: </label>
                                        <div>
                                            @foreach (array_unique(array_merge(Usr::getOLevelSubjects(), Usr::getALevelSubjects())) as $sub)
                                            <div class="form-check ml-1">
                                                <label class="form-check-label">
                                                    {{ $sub }}
                                                    <input type="checkbox" name="subjects_studied[]" value="{{ $sub }}" class="form-input-styled" data-fouc @if($staff_rec->subjects_studied && in_array($sub, json_decode($staff_rec->subjects_studied))) checked @endif>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="{{ Qs::userIsTeamSATCL() || Qs::userIsItGuy() ? 'float-right' : 'col-md-6 float-right' }}">
                        <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>
            @endif

            <div class="tab-pane fade show" id="other">
                <form method="post" action="{{ route('my_account.other') }}">
                    @csrf @method('put')
                    <div class="row">
                        <div class="col-md-4 d-none d-md-block">
                            {{--SIDE BAR MINIMIZED--}}
                            <div class="form-group row">
                                <label for="sidebar_minimized" class="col-lg-8 col-form-label font-weight-semibold">Minimize Sidebar <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <select class="form-control select" name="sidebar_minimized" id="sidebar_minimized">
                                        <option {{ auth()->user()->sidebar_minimized ? 'selected' : '' }} value="1">Yes</option>
                                        <option {{ auth()->user()->sidebar_minimized ?: 'selected' }} value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            {{--SIDE BAR MINIMIZED--}}
                            <div class="form-group row">
                                <label for="allow_system_sounds" class="col-lg-9 col-form-label font-weight-semibold">Allow System Sounds (ie., new message or notification) <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <select class="form-control select" name="allow_system_sounds" id="allow_system_sounds">
                                        <option {{ auth()->user()->allow_system_sounds ?: 'selected' }} value="1">Yes</option>
                                        <option {{ auth()->user()->allow_system_sounds ?: 'selected' }} value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-12">
                                    {{--SUBMIT BUTTON--}}
                                    <div class="text-right float-right">
                                        <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--My Profile Ends--}}

@endsection
