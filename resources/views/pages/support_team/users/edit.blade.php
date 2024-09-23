@extends('layouts.master')
@section('page_title', 'Edit User')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 id="ajax-title" class="card-title">Edit User Details - {{ $user->name }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-update" data-reload="#ajax-title" action="{{ route('users.update', Qs::hash($user->id)) }}" data-fouc>
            @csrf @method('PUT')
            <h6>Personal Data</h6>

            <fieldset>
                <div class="row">
                    {{--USER--}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="user_type"> Select User: <span class="text-danger">*</span></label>
                            <select @if(!Qs::headSA(Auth::id())) disabled="disabled" @endif class="form-control select" name="user_type" id="user_type">
                                @foreach ($user_types as $ut)
                                @if ($ut->title == 'parent')
                                <option class="parent" value="{{ Qs::hash($ut->id) }}" @if($user->user_type == $ut->title) selected @endif>
                                    {{ $ut->name }}</option>
                                @continue
                                @endif
                                <option value="{{ Qs::hash($ut->id) }}" @if($user->user_type == $ut->title) selected @endif>{{ $ut->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--NAME--}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Full Name: <span class="text-danger">*</span></label>
                            <input value="{{ $user->name }}" required type="text" name="name" placeholder="Full Name" class="form-control">
                        </div>
                    </div>
                    {{--ADDRESS--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address: <span class="text-danger">*</span></label>
                            <input value="{{ $user->address }}" class="form-control" placeholder="Address" name="address" type="text" required>
                        </div>
                    </div>
                </div>
                {{--EMAIL ADDRESS--}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Email address: </label>
                            <input value="{{ $user->email }}" type="email" name="email" class="form-control" placeholder="your@email.com">
                        </div>
                    </div>
                    {{--PHONE--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phone:</label>
                            <input value="{{ $user->phone }}" type="text" name="phone" data-mask="+999 9999 999 99" class="form-control" placeholder="+255 1234 567 89">
                        </div>
                    </div>
                    {{--TELEPHONE--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Telephone:</label>
                            <input value="{{ $user->phone2 }}" type="text" name="phone2" data-mask="+999 9999 999 99" class="form-control" placeholder="+255 1234 567 89">
                        </div>
                    </div>
                    {{--BLOOD GROUP--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bg_id">Blood Group: </label>
                            <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                @foreach($blood_groups as $bg)
                                <option {{ ($user->bg_id == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{--GENDER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="gender">Gender: <span class="text-danger">*</span></label>
                            <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                <option {{ ($user->gender == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                <option {{ ($user->gender == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    {{--NATIONALITY--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                            <select onchange="getState(this.value)" data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                <option value=""></option>
                                @foreach($nationals as $nal)
                                <option {{ ($user->nal_id == $nal->id) ? 'selected' : '' }} value="{{ $nal->id }}">{{ $nal->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--STATE OR REGION--}}
                    <div class="col-md-3">
                        <label for="state_id">State/Reion: <span class="text-danger">*</span></label>
                        <select onchange="getLGA(this.value)" required data-placeholder="Select Nationality First.." class="select-search form-control" name="state_id" id="state_id">
                            <option value="{{ $user->state_id ?? '' }}">{{ $user->state->name ?? '' }}</option>
                        </select>
                    </div>
                    {{--LGA--}}
                    <div class="col-md-3">
                        <label for="lga_id">LGA: <span class="text-danger">*</span></label>
                        <select required data-placeholder="Select State First" class="select-search form-control" name="lga_id" id="lga_id">
                            <option value="{{ $user->lga_id ?? '' }}">{{ $user->lga->name ?? '' }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    {{--PRIMARY ID--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="d-block">Primary ID:</label>
                            <input value="{{ $user->primary_id ?? '' }}" type="text" name="primary_id" data-mask="999999999" class="form-control" placeholder="123456789">
                        </div>
                    </div>
                    {{--SECONDARY ID--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="d-block">Secondary ID:</label>
                            <input value="{{ $user->secondary_id ?? '' }}" type="text" name="secondary_id" data-mask="99999999-99999-99999-99" class="form-control" placeholder="12345678-12345-12345-12">
                        </div>
                    </div>
                    {{--RELIGION--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="religion">Religion: <span class="text-danger">*</span></label>
                            <select required data-placeholder="Choose..." name="religion" id="religion" class="select-search form-control">
                                <option value=""></option>
                                @foreach(Qs::getReligions() as $rel)
                                <option {{ ($user->religion == $rel) ? 'selected' : '' }} value="{{ $rel }}">{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--DOB--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date of Birth:</label>
                            <input name="dob" value="{{ $user->dob }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                        </div>
                    </div>
                </div>

                @php $p_relative = Qs::getParentRelative($user->id) @endphp
                <div class="{{ Qs::userIsParent2($user->user_type) ? '' : 'display-none' }}" id="parent-data">
                    <div class="row">
                        {{--CLOSE RELATIVE NAME--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state_id">Close Relative Name: <span class="text-danger">*</span></label>
                                <input autocomplete="off" name="name2" required value="{{ $p_relative->name ?? '' }}" type="text" class="form-control text-capitalize" placeholder="Full name">
                            </div>
                        </div>
                        {{--RELATION WITH PARENT--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Parent's Relation with the Relative: <span class="text-danger">*</span></label>
                                <input value="{{ $p_relative->relation ?? '' }}" type="text" required name="relation" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{--PARENT WORK--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state_id">Parent Work: <span class="text-danger">*</span></label>
                                <input autocomplete="off" id="parent-work" required name="work" value="{{ $user->work ?? '' }}" type="text" class="form-control" placeholder="The Work he/she does">
                            </div>
                        </div>
                        {{--CLOSE RELATIVE NUMBERS--}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Close Relative Phone: <span class="text-danger">*</span></label>
                                <input value="{{ $p_relative->phone3 ?? ''  }}" required type="text" name="phone3" data-mask="+999 9999 999 999" class="form-control" placeholder="+255 1234 567 89">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Close Relative Mobile: </label>
                                <input value="{{ $p_relative->phone4 ?? ''  }}" type="text" name="phone4" data-mask="+999 9999 999 999" class="form-control" placeholder="+255 1234 567 89">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{--PASSPORT--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-block">Upload Passport Photo:</label>
                            <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                            <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Staff Data</h6>
            <fieldset>
                <div class="row">
                    {{--DATE OF EMPLOYMENT--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="emp-date">Date of Employment:</label>
                            <input autocomplete="off" id="emp-date" name="emp_date" value="{{ $staff_rec->emp_date ?? '' }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                        </div>
                    </div>
                    {{--DATE OF CONFIRMATION--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="confirmation-date">Date of Confirmation:</label>
                            <input autocomplete="off" id="confirmation-date" name="confirmation_date" value="{{ $staff_rec->confirmation_date ?? '' }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                        </div>
                    </div>
                    {{--LICENCE NUMBER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Licence Number:</label>
                            <input name="licence_number" value="{{ $staff_rec->licence_number ?? '' }}" type="text" class="form-control" data-mask="LTT 99999" placeholder="LTT 12345">
                        </div>
                    </div>
                    {{--FILE NUMBER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>File Number:</label>
                            <input name="file_number" value="{{ $staff_rec->file_number ?? '' }}" type="text" class="form-control" placeholder="EPP/123/12/1">
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{--SOCIAL SECURITY NUMBER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Social Security Number:</label>
                            <input name="ss_number" value="{{ $staff_rec->ss_number ?? '' }}" type="number" min="0" 0 class="form-control">
                        </div>
                    </div>
                    {{--EMPLOYMENT NUMBER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Employment Number:</label>
                            <input name="emp_no" value="{{ $staff_rec->emp_no ?? '' }}" type="number" min="0" class="form-control">
                        </div>
                    </div>
                    {{--TIN NUMBER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tin Number:</label>
                            <input name="tin_number" value="{{ $staff_rec->tin_number ?? '' }}" type="number" min="0" class="form-control">
                        </div>
                    </div>
                    {{--BANK ACCOUNT NUMBER--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Bank Account Number:</label>
                            <input name="bank_acc_no" value="{{ $staff_rec->bank_acc_no ?? '' }}" type="number" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{--BANK NAME--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Bank Name:</label>
                            <input name="bank_name" value="{{ $staff_rec->bank_name ?? '' }}" type="text" class="form-control">
                        </div>
                    </div>
                    {{--EDUCATION LEVEL--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="education">Education Level: </label>
                            <select class="select-search form-control" id="education_level" name="education_level" data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                @foreach(Usr::getEducationLevels() as $lv)
                                <option {{ (isset($staff_rec->education_level) && $staff_rec->education_level == $lv ? 'selected' : '') }} value="{{ $lv }}">{{ $lv }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--COLLEGE ATTENDED--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="college_attended">College Ateended: </label>
                            <input name="college_attended" value="{{ $staff_rec->college_attended ?? '' }}" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{--GRADUATION YEAR--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="year_graduated">Graduation Year: </label>
                            <select name="year_graduated" data-placeholder="Choose..." id="year_graduated" class="select-search form-control">
                                <option value=""></option>
                                @for($y=date('Y', strtotime('- 30 years')); $y<=date('Y'); $y++) <option {{ (isset($staff_rec->year_graduated) && $staff_rec->year_graduated == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }} </option> @endfor
                            </select>
                        </div>
                    </div>
                    {{--ROLE--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="role">Role: </label>
                            <select class="select form-control" id="role" name="role" data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                @foreach(Usr::getStaffRoles() as $role)
                                <option {{ (isset($staff_rec->role) && $staff_rec->role == $role ? 'selected' : '') }} value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--NUMBER OF PERIODS--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Number of Periods:</label>
                            <select name="no_of_periods" data-placeholder="Choose..." id="no_of_periods" class="select-search form-control">
                                <option value=""></option>
                                @for($i = 1; $i<=30; $i++) <option {{ (isset($staff_rec->no_of_periods) && $staff_rec->no_of_periods == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }} </option> @endfor
                            </select>
                        </div>
                    </div>
                    {{--PLACE OF BIRTH--}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="place_of_living">Place of Living: </label>
                            <input type="text" class="form-control" name="place_of_living" id="place_of_living" value="{{ $staff_rec->place_of_living ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row m-0">
                    {{--SUBJECTS STUDIED--}}
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="role">Subjects Studied: </label>
                            <div>
                                @foreach (array_unique(array_merge(Usr::getOLevelSubjects(), Usr::getALevelSubjects())) as $sub)
                                <div class="form-check ml-1">
                                    <label class="form-check-label">
                                        {{ $sub }}
                                        <input type="checkbox" name="subjects_studied[]" value="{{ $sub }}" class="form-input-styled" data-fouc @if(isset($staff_rec->subjects_studied) && $staff_rec->subjects_studied && in_array($sub, json_decode($staff_rec->subjects_studied))) checked @endif>
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
</div>

@if(Qs::userIsParent2($user->user_type))
<script>
    $(document).ready(function() {
        // Hide steps wizard previous and next buttons
        $(".wizard>.actions>ul>li:not(last-child)").hide();
        // Show submit button
        $(".wizard>.actions>ul>li:last-child").show();
    })

</script>

@else
<script>
    // Disable parent data input fields.
    $("#parent-data input").attr("disabled", "true");

</script>
@endif

@endsection
