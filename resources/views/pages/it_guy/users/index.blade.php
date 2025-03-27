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
            @if (Qs::userIsHead())
            <li class="nav-item"><a href="#new-user" class="nav-link active" data-toggle="tab">Create New User</a></li>
            @endif

            <li class="nav-item dropdown">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Users</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach ($user_types as $ut)
                    <a href="#ut-{{ Qs::hash($ut->id) }}" class="dropdown-item" data-toggle="tab">{{ $ut->name }}s</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            @if (Qs::userIsHead())
            <div class="tab-pane fade show active" id="new-user">
                <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-store" action="{{ route('users.store') }}" data-fouc>
                    @csrf
                    {{-- PERSONAL DATA --}}
                    <h6>Personal Data</h6>
                    <fieldset>
                        <div class="row">
                            {{-- USER TYPE --}}
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
                            {{-- FULL NAME --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Full Name: <span class="text-danger">*</span></label>
                                    <input value="{{ old('name') }}" required type="text" name="name" id="name" placeholder="Full Name" class="form-control text-capitalize">
                                </div>
                            </div>
                            {{-- ADDRESS --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address: <span class="text-danger">*</span></label>
                                    <input value="{{ old('address') }}" class="form-control" placeholder="Address" name="address" id="address" type="text" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- EMAIL ADDRESS --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email">Email address: </label>
                                    <input value="{{ old('email') }}" type="email" name="email" id="email" class="form-control" placeholder="your@email.com">
                                </div>
                            </div>
                            {{-- USERNAME --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="username">Username: </label>
                                    <input value="{{ old('username') }}" type="text" name="username" id="username" class="form-control" placeholder="Username">
                                </div>
                            </div>
                            {{-- PHONE --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input value="{{ old('phone') }}" type="text" name="phone" id="phone" data-mask="+9999?999999999" class="form-control" placeholder="+255 1234 567 89">
                                </div>
                            </div>
                            {{-- TELEPHONE --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone2">Telephone:</label>
                                    <input value="{{ old('phone2') }}" type="text" name="phone2" id="phone2" data-mask="+9999?999999999" class="form-control" placeholder="+255 1234 567 89">
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
                                        <option @selected(old('bg_id')==$bg->id) value="{{ $bg->id }}">{{ $bg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- PASSWORD --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="password">Password: </label>
                                    <input id="password" type="password" autocomplete="current-password" name="password" class="form-control">
                                </div>
                            </div>
                            {{-- GENDER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">Gender: <span class="text-danger">*</span></label>
                                    <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        <option @selected(old('gender')=='Male' ) value="Male">Male</option>
                                        <option @selected(old('gender')=='Female' ) value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            {{-- NATIONALITY --}}
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
                            {{-- STATE --}}
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
                                    <label for="primary_id" class="d-block">Primary ID:</label>
                                    <input value="{{ old('primary_id') }}" type="text" name="primary_id" id="primary_id" data-mask="www?wwwwwwwwwwwwwwwww" class="form-control" placeholder="123456789">
                                </div>
                            </div>
                            {{-- SECONDARY ID --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="secondary_id" class="d-block">Secondary ID:</label>
                                    <input value="{{ old('secondary_id') }}" type="text" name="secondary_id" id="secondary_id" data-mask="www?wwwwwwwwwwwwwwwww" class="form-control" placeholder="12345678123451234512">
                                </div>
                            </div>
                        </div>

                        <div class="display-none" id="parent-data">
                            <div class="row">
                                {{-- CLOSE RELATIVE NAME --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name2">Close Relative Name: <span class="text-danger">*</span></label>
                                        <input disabled autocomplete="off" name="name2" id="name2" required value="{{ old('name2') }}" type="text" class="form-control text-capitalize" placeholder="Full name">
                                    </div>
                                </div>
                                {{-- RELATION WITH PARENT --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="relation">Parent's Relation with the Relative: <span class="text-danger">*</span></label>
                                        <input disabled value="{{ old('relation') }}" type="text" required name="relation" id="relation" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- PARENT WORK --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="work">Parent Work: <span class="text-danger">*</span></label>
                                        <input disabled autocomplete="off" id="work" required name="work" value="{{ old('work') }}" type="text" class="form-control" placeholder="The Work he/she does">
                                    </div>
                                </div>
                                {{-- CLOSE RELATIVE NUMBERS --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone3">Close Relative Phone: <span class="text-danger">*</span></label>
                                        <input disabled value="{{ old('phone3') }}" required type="text" name="phone3" id="phone3" data-mask="+9999?999999999" class="form-control" placeholder="+255 1234 567 89">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone4">Close Relative Mobile: </label>
                                        <input disabled value="{{ old('phone4') }}" type="text" name="phone4" id="phone4" data-mask="+9999?999999999" class="form-control" placeholder="+255 1234 567 89">
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
                                        @foreach (Usr::getReligions() as $rel)
                                        <option @selected(old('religion')==$rel) value="{{ $rel }}">{{ $rel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- DOB --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dob">Date of Birth:</label>
                                    <input name="dob" id="dob" value="{{ old('dob') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{-- PASSPORT --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="photo" class="d-block">Upload Passport Photo:</label>
                                    <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" id="photo" class="form-input-styled" data-fouc>
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
                                    <label for="emp_date">Date of Employment:</label>
                                    <input autocomplete="off" id="emp_date" name="emp_date" value="{{ old('emp_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{-- DATE OF CONFIRMATION --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="confirmation_date">Date of Confirmation:</label>
                                    <input autocomplete="off" id="confirmation_date" name="confirmation_date" value="{{ old('confirmation_date') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                                </div>
                            </div>
                            {{-- LICENCE NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="licence_number">Licence Number:</label>
                                    <input name="licence_number" id="licence_number" value="{{ old('licence_number') }}" type="text" class="form-control" placeholder="Ie., LTT 12345">
                                </div>
                            </div>
                            {{-- FILE NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="file_number">File Number:</label>
                                    <input name="file_number" id="file_number" value="{{ old('file_number') }}" type="text" class="form-control" placeholder="EFP/123/12/1">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- SOCIAL SECURITY NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ss_number">Social Security Number:</label>
                                    <input name="ss_number" id="ss_number" value="{{ old('ss_number') }}" type="number" min="1" class="form-control">
                                </div>
                            </div>
                            {{-- EMPLOYMENT NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="emp_no">Employment Number:</label>
                                    <input name="emp_no" id="emp_no" value="{{ old('emp_no') }}" type="number" min="1" class="form-control">
                                </div>
                            </div>
                            {{-- TIN NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tin_number">TIN Number:</label>
                                    <input name="tin_number" id="tin_number" value="{{ old('tin_number') }}" type="text" class="form-control">
                                </div>
                            </div>
                            {{-- BANK ACCOUNT NUMBER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bank_acc_no">Bank Account Number:</label>
                                    <input name="bank_acc_no" id="bank_acc_no" value="{{ old('bank_acc_no') }}" type="number" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- BANK NAME --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name:</label>
                                    <input name="bank_name" id="bank_name" value="{{ old('bank_name') }}" type="text" class="form-control">
                                </div>
                            </div>
                            {{-- EDUCATION LEVEL --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="education_level">Education Level: </label>
                                    <select class="select-search form-control" id="education_level" name="education_level" data-fouc data-placeholder="Choose..">
                                        <option value=""></option>
                                        @foreach (Usr::getEducationLevels() as $lv)
                                        <option @selected(old('education_level')==$lv) value="{{ $lv }}">{{ $lv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- COLLEGE ATTENDED --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="college_attended">College Attended: </label>
                                    <input name="college_attended" id="college_attended" value="{{ old('college_attended') }}" type="text" class="form-control">
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

                        <div class="row">
                            {{-- GRADUATION YEAR --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year_graduated">Graduation Year: </label>
                                    <select name="year_graduated" data-placeholder="Choose..." id="year_graduated" class="select-search form-control">
                                        <option value=""></option>
                                        @for ($y = date('Y', strtotime('- 30 years')); $y <= date('Y'); $y++) <option @selected(old('year_graduated')==$y) value="{{ $y }}">{{ $y }} </option> @endfor
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
                                        <option @selected(old('role')==$role) value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- SUBJECTS STUDIED --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subjects_studied">Subjects Studied <span class="text-info">(comma (,) separated)</span>: </label>
                                    <div>
                                        <textarea name="subjects_studied" id="subjects_studied" class="form-control" placeholder="ie., Subject one, Subject two, ...">{{ old('subjects_studied') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            @endif

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
                            <th>Blocked</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users->where('user_type', $ut->title) as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ asset($u->photo) }}" alt="photo"></td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->username }}</td>
                            <td><a href="tel: {{ $u->phone }}">{{ $u->phone }}</a></td>
                            <td><a href="mailto: {{ $u->email }}">{{ $u->email }}</a></td>

                            @if(Qs::headSA($u->id))
                            <td></td>
                            @else
                            <td><label class="{{ Qs::userIsHead() ? 'form-switch m-0' : 'form-switch m-0 disabled' }}"><input id="checkbox-user-{{ $u->id }}" onchange="updateUserBlockedState({{ $u->id }}, this)" type="checkbox" @checked($u->blocked)><i></i></label></td>
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if (Qs::userIsHead())
                                            {{-- Edit --}}
                                            <a href="{{ route('users.edit', Qs::hash($u->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            @endif
                                            {{-- View Profile --}}
                                            <a href="{{ route('users.show', Qs::hash($u->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>

                                            @if (Qs::userIsHead())
                                            {{-- Reset Password --}}
                                            <a href="javascript:;" data-default_pass="user" data-href="{{ route('users.reset_pass', Qs::hash($u->id)) }}" class="dropdown-item needs-reset-pass-confirmation"><i class="material-symbols-rounded">lock_reset</i> Reset password</a>
                                            {{-- Delete --}}
                                            <a id="{{ Qs::hash($u->id) }}" onclick="confirmPermanentDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
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

{{-- Users List Ends --}}

@endsection
