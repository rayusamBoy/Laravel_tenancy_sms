@extends('layouts.master')
@section('page_title', 'Manage Users')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Users</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-user" class="nav-link active" data-toggle="tab">Create New User</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Users</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach ($user_types as $ut)
                    <a href="#ut-{{ Qs::hash($ut->id) }}" class="dropdown-item" data-toggle="tab">{{ $ut->name }}s</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="new-user">
                <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-stcore" action="{{ route('users.store') }}" data-fouc>
                    @csrf
                    {{-- PERSONAL DATA --}}
                    <h6 class="text-white">Personal Data</h6>

                    <fieldset>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="user_type"> Select User Type: <span class="text-danger">*</span></label>
                                    <select required data-placeholder="Select User" class="form-control select" name="user_type" id="user_type">
                                        @foreach ($user_types as $ut)
                                        @if ($ut->title == 'parent')
                                        <option class="parent" value="{{ Qs::hash($ut->id) }}">
                                            {{ $ut->name }}
                                        </option>
                                        @continue
                                        @endif
                                        <option value="{{ Qs::hash($ut->id) }}">{{ $ut->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Full Name: <span class="text-danger">*</span></label>
                                    <input value="{{ old('name') }}" required type="text" name="name" placeholder="Full Name" class="form-control text-capitalize">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address: <span class="text-danger">*</span></label>
                                    <input value="{{ old('address') }}" class="form-control" placeholder="Address" name="address" type="text" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Email address: </label>
                                    <input value="{{ old('email') }}" type="email" name="email" class="form-control" placeholder="your@email.com">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Username: </label>
                                    <input value="{{ old('username') }}" type="text" name="username" class="form-control" placeholder="Username">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Phone:</label>
                                    <input value="{{ old('phone') }}" type="text" name="phone" data-mask="+999 9999 999 99" class="form-control" placeholder="+255 1234 567 89">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Telephone:</label>
                                    <input value="{{ old('phone2') }}" type="text" name="phone2" data-mask="+999 9999 999 99" class="form-control" placeholder="+255 1234 567 89">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- BLOOD GROUP --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bg_id">Blood Group: </label>
                                    <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach ($blood_groups as $bg)
                                        <option {{ old('bg_id') == $bg->id ? 'selected' : '' }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="password">Password: </label>
                                    <input id="password" type="password" autocomplete="current-password" name="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">Gender: <span class="text-danger">*</span></label>
                                    <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        <option {{ old('gender') == 'Male' ? 'selected' : '' }} value="Male">Male</option>
                                        <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                                    <select onchange="getState(this.value)" data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                        <option value=""></option>
                                        @foreach ($nationals as $nal)
                                        <option @if (old('nal_id'==$nal->id)) selected @endif value="{{ $nal->id }}">{{ $nal->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- State --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="state_id">State/Region: <span class="text-danger">*</span></label>
                                    <select onchange="getLGA(this.value)" required data-placeholder="Select Nationality First" class="select-search form-control" name="state_id" id="state_id">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            {{-- LGA --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="lga_id">LGA: <span class="text-danger">*</span></label>
                                    <select required data-placeholder="Select State First" class="select-search form-control" name="lga_id" id="lga_id">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            {{-- PRIMARY ID --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="d-block">Primary ID:</label>
                                    <input value="{{ old('primary_id') }}" type="text" name="primary_id" data-mask="999999999" class="form-control" placeholder="123456789">
                                </div>
                            </div>
                            {{-- SECONDARY ID --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="d-block">Secondary ID:</label>
                                    <input value="{{ old('secondary_id') }}" type="text" name="secondary_id" data-mask="99999999-99999-99999-99" class="form-control" placeholder="12345678-12345-12345-12">
                                </div>
                            </div>
                        </div>

                        <div class="display-none" id="parent-data">
                            <div class="row">
                                {{-- CLOSE RELATIVE NAME --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state_id">Close Relative Name: <span class="text-danger">*</span></label>
                                        <input disabled autocomplete="off" name="name2" required value="{{ old('name2') }}" type="text" class="form-control text-capitalize" placeholder="Full name">
                                    </div>
                                </div>
                                {{-- RELATION WITH PARENT --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Parent's Relation with the Relative: <span class="text-danger">*</span></label>
                                        <input disabled value="{{ old('relation') }}" type="text" required name="relation" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- PARENT WORK --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state_id">Parent Work: <span class="text-danger">*</span></label>
                                        <input disabled autocomplete="off" id="parent-work" required name="work" value="{{ old('work') }}" type="text" class="form-control" placeholder="The Work he/she does">
                                    </div>
                                </div>
                                {{-- CLOSE RELATIVE NUMBERS --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Close Relative Phone: <span class="text-danger">*</span></label>
                                        <input disabled value="{{ old('phone3') }}" required type="text" name="phone3" data-mask="+999 9999 999 99" class="form-control" placeholder="+255 1234 567 89">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Close Relative Mobile: </label>
                                        <input disabled value="{{ old('phone4') }}" type="text" name="phone4" data-mask="+999 9999 999 99" class="form-control" placeholder="+255 1234 567 89">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- RELIGION --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="religion">Religion: <span class="text-danger">*</span></label>
                                    <select required data-placeholder="Choose..." name="religion" id="religion" class="select-search form-control">
                                        <option value=""></option>
                                        @foreach (Qs::getReligions() as $rel)
                                        <option {{ old('religion') == $rel ? 'selected' : '' }} value="{{ $rel }}">{{ $rel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- DOB --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date of Birth:</label>
                                    <input name="dob" value="{{ old('dob') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{-- PASSPORT --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block">Upload Passport Photo:</label>
                                    <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                    <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    {{-- STAFF DATA --}}
                    <h6>Staff Data</h6>
                    <fieldset>
                        <div class="row">
                            {{-- DATE OF EMPLOYMENT --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="emp-date">Date of Employment:</label>
                                    <input autocomplete="off" id="emp-date" name="emp_date" value="{{ old('emp_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{-- DATE OF CONFIRMATION --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="confirmation-date">Date of Confirmation:</label>
                                    <input autocomplete="off" id="confirmation-date" name="confirmation_date" value="{{ old('confirmation_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{-- LICENCE NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Licence Number:</label>
                                    <input name="licence_number" value="{{ old('licence_number') }}" type="text" class="form-control" data-mask="LTT 99999" placeholder="LTT 12345">
                                </div>
                            </div>
                            {{-- FILE NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>File Number:</label>
                                    <input name="file_number" value="{{ old('file_number') }}" type="text" class="form-control" placeholder="EFP/123/12/1">
                                </div>
                            </div>
                        </div>
                        {{-- SOCIAL SECURITY NUMBER --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Social Security Number:</label>
                                    <input name="ss_number" value="{{ old('ss_number') }}" type="number" min="" 0 class="form-control">
                                </div>
                            </div>
                            {{-- EMPLOYMENT NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Employment Number:</label>
                                    <input name="emp_no" value="{{ old('emp_no') }}" type="number" min="0" class="form-control">
                                </div>
                            </div>
                            {{-- TIN NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tin Number:</label>
                                    <input name="tin_number" value="{{ old('tin_number') }}" type="number" min="0" class="form-control">
                                </div>
                            </div>
                            {{-- BANK ACCOUNT NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bank Account Number:</label>
                                    <input name="bank_acc_no" value="{{ old('bank_acc_no') }}" type="number" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- BANK NAME --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bank Name:</label>
                                    <input name="bank_name" value="{{ old('bank_name') }}" type="text" class="form-control">
                                </div>
                            </div>
                            {{-- EDUCATION LEVEL --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="education">Education Level: </label>
                                    <select class="select-search form-control" id="education_level" name="education_level" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach (Usr::getEducationLevels() as $lv)
                                        <option {{ old('education_level') == $lv ? 'selected' : '' }} value="{{ $lv }}">{{ $lv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- COLLEGE ATTENDED --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="college_attended">College Ateended: </label>
                                    <input name="college_attended" value="{{ old('college_attended') }}" type="text" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- GRADUATION YEAR --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year_graduated">Graduation Year: </label>
                                    <select name="year_graduated" data-placeholder="Choose..." id="year_graduated" class="select-search form-control">
                                        <option value=""></option>
                                        @for ($y = date('Y', strtotime('- 30 years')); $y <= date('Y'); $y++) <option {{ old('year_graduated') == $y ? 'selected' : '' }} value="{{ $y }}">{{ $y }} </option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            {{-- ROLE --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="role">Role: </label>
                                    <select class="select form-control" id="role" name="role" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach (Usr::getStaffRoles() as $role)
                                        <option {{ old('role') == $role ? 'selected' : '' }} value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- NUMBER OF PERIODS --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Number of Periods:</label>
                                    <select name="no_of_periods" data-placeholder="Choose..." id="no_of_periods" class="select-search form-control">
                                        <option value=""></option>
                                        @for ($i = 1; $i <= 30; $i++) <option {{ old('no_of_periods') == $y ? 'selected' : '' }} value="{{ $i }}">{{ $i }} </option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            {{-- PLACE OF LIVING --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="place_of_living">Place of Living: </label>
                                    <input type="text" class="form-control" name="place_of_living" id="place_of_living" placeholder="Where you live?">
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            {{-- SUBJECTS STUDIED --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="role">Subjects Studied: </label>
                                    <div>
                                        @foreach (array_unique(array_merge(Usr::getOLevelSubjects(), Usr::getALevelSubjects())) as $sub)
                                        <div class="form-check ml-1">
                                            <label class="form-check-label">
                                                {{ $sub }}
                                                <input type="checkbox" name="subjects_studied[]" value="{{ $sub }}" class="form-input-styled" {{ old('subjects_studied') ? 'checked' : '' }} data-fouc>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </form>
            </div>

            @foreach ($user_types as $ut)
            <div class="tab-pane fade" id="ut-{{ Qs::hash($ut->id) }}">
                <span class="status-styled">{{ $ut->name }}s</span>

                <table class="table datatable-button-html5-columns ">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Email</th>
                            @if (in_array($ut->title, Qs::getStaff(['super_admin'])))
                            <th>Staff Data Edititable</th>
                            @endif
                            <th>Blocked</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users->where('user_type', $ut->title) as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ Usr::getTenantAwarePhoto($u->photo) }}" alt="photo"></td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->username }}</td>
                            <td><a href="tel: {{ $u->phone }}">{{ $u->phone }}</a></td>
                            <td><a href="mailto: {{ $u->email }}">{{ $u->email }}</a></td>
                            @if(Qs::userIsSuperAdmin() && in_array($ut->title, Qs::getStaff(['super_admin'])))
                            @if(isset($u->staff->first()->staff_data_edit))
                            <td><label class="form-switch m-0"><input id="checkbox-staff-{{ $u->id }}" onchange="updateStaffDataEdtiState(<?php echo $u->id ?>, this)" type="checkbox" @if($u->staff->first()->staff_data_edit) checked @endif><i></i></label></td>
                            @else
                            <td class="status-styled">Unavailable</td>
                            @endif
                            @endif
                            @if(Qs::headSA($u->id))
                            <td></td>
                            @else
                            <td><label class="form-switch m-0"><input id="checkbox-user-{{ $u->id }}" onchange="updateUserBlockedState(<?php echo $u->id ?>, this)" type="checkbox" @if($u->blocked) checked @endif><i></i></label></td>
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- Edit --}}
                                            <a href="{{ route('users.edit', Qs::hash($u->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            {{-- View Profile --}}
                                            <a href="{{ route('users.show', Qs::hash($u->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                            @if (Qs::userIsSuperAdmin())
                                            <a href="javascript:;" data-default_pass="user" data-href="{{ route('users.reset_pass', Qs::hash($u->id)) }}" class="dropdown-item needs-reset-pass-confirmation"><i class="material-symbols-rounded">lock_reset</i> Reset password</a>
                                            {{-- Delete --}}
                                            <a id="{{ Qs::hash($u->id) }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($u->id) }}" action="{{ route('users.destroy', Qs::hash($u->id)) }}" class="hidden">@csrf @method('delete')</form>
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
            @endforeach

        </div>
    </div>
</div>

{{-- Student List Ends --}}

@endsection
