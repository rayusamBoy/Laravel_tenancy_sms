@extends('layouts.master')

@section('page_title', 'Admit Student')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Please fill The form Below To Admit A New Student</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation" action="{{ route('students.store') }}" data-fouc>
            @csrf

            <h6>Personal data</h6>
            <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full Name: <span class="text-danger">*</span></label>
                            <input value="{{ old('name') }}" id="name" required type="text" name="name" placeholder="Full Name" class="form-control text-uppercase">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Address: <span class="text-danger">*</span></label>
                            <input value="{{ old('address') }}" class="form-control" id="address" placeholder="Address" name="address" type="text" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email address: </label>
                            <input type="email" value="{{ old('email') }}" id="email" name="email" class="form-control" placeholder="Email Address">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="gender">Gender: <span class="text-danger">*</span></label>
                            <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input value="{{ old('phone') }}" type="text" id="phone" data-mask="+999 9999 999 999" name="phone" class="form-control" placeholder="+255-1234-567-89">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="phone2">Telephone:</label>
                            <input value="{{ old('phone2') }}" type="text" id="phone2" name="phone2" data-mask="+999 9999 999 999" class="form-control" placeholder="+255-1234-567-89">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dob">Date of Birth:</label>
                            <input name="dob" value="{{ old('dob') }}" id="dob" type="text" class="form-control date-pick" placeholder="Select Date...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                            <select onchange="getState(this.value)" data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                <option value=""></option>
                                @foreach($nationals as $nal)
                                <option @if(old('nal_id'==$nal->id)) selected @endif value="{{ $nal->id }}">{{ $nal->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="state_id">State: <span class="text-danger">*</span></label>
                            <select onchange="getLGA(this.value)" required data-placeholder="Select Nationality First" class="select-search form-control" name="state_id" id="state_id">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lga_id">LGA: <span class="text-danger">*</span></label>
                            <select required data-placeholder="Select State First" class="select-search form-control" name="lga_id" id="lga_id">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="food_taboos" class="display-i-on-hover w-100">Food Taboos:
                                <i class="material-symbols-rounded float-right text-info display-none" data-toggle="tooltip" title="Refer to the restriction of specific food as a result of social, religious customs, or health problem(s).">info</i>
                            </label>
                            <input type="text" id="food_taboos" name="food_taboos" placeholder="Mention (if presents)" class="form-control" value="{{ old('food_taboos') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="disability">Disability: </label>
                            <select data-placeholder="Choose..." name="disability" id="disability" class="select-search form-control">
                                <option value=""></option>
                                @foreach(Usr::getDisabilities() as $key => $value)
                                <option title="{{ $key }}" {{ (old('disability') == $value) ? 'selected' : '' }} value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="p_status">Parent Status: <span class="text-danger">*</span></label>
                            <select required data-placeholder="Choose..." name="p_status" id="p_status" class="select form-control has-editable-option">
                                <option value=""></option>
                                @foreach(Usr::getStudentParentsStatus() as $status)
                                <option {{ (old('p_status') == $status) ? 'selected' : '' }} value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bg_id">Blood Group: </label>
                            <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                @foreach(Usr::getBloodGroups() as $bg)
                                <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ps_name">Primary School Name: <span class="text-danger">*</span></label>
                            <input required type="ps_name" value="{{ old('ps_name') }}" id="ps_name" name="ps_name" class="form-control" placeholder="Primary School">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-block">Upload Passport Photo:</label>
                            <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                            <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ss_name">Secondary School Name: </label>
                            <input type="ss_name" value="{{ old('ss_name') }}" id="ss_name" name="ss_name" class="form-control" placeholder="Secondary School">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>House Number:</label>
                            <input type="text" name="house_no" placeholder="House number" class="form-control text-uppercase" value="{{ old('house_no') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="d-block">Upload Birth Certificate:</label>
                            <input value="{{ old('birth_certificate') }}" accept=".pdf, .jpg, .jpeg, .png" type="file" name="birth_certificate" class="form-input-styled" data-fouc>
                            <span class="form-text text-muted">Accepted Files: pdf, png, jpg, jpeg. Max file size 2Mb</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="religion">Religion: <span class="text-danger">*</span></label>
                            <select required data-placeholder="Choose..." name="religion" id="religion" class="select-search form-control">
                                <option value=""></option>
                                @foreach(Qs::getReligions() as $rel)
                                <option {{ (old('religion') == $rel) ? 'selected' : '' }} value="{{ $rel }}">{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Chronic Health Problem(s):</label>
                            <input type="text" name="chp" placeholder="Mention (if presents)" class="form-control" value="{{ old('chp') }}">
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Student Data</h6>
            <fieldset>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                            <select onchange="getClassSections(this.value, '#section_id')" data-placeholder="Choose..." required name="my_class_id" id="my_class_id" class="select-search form-control">
                                <option value=""></option>
                                @foreach($my_classes as $c)
                                <option {{ (old('my_class_id') == $c->id ? 'selected' : '') }} value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="section_id">Section: <span class="text-danger">*</span></label>
                            <select data-placeholder="Select Class First" required name="section_id" id="section_id" class="select-search form-control">
                                <option {{ (old('section_id')) ? 'selected' : '' }} value="{{ old('section_id') }}">{{ (old('section_id')) ? 'Selected' : '' }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="my_parent_id">Parent: </label>
                            <select data-placeholder="Choose..." name="my_parent_id" id="my_parent_id" class="select-search form-control">
                                <option value=""></option>
                                @foreach($parents as $p)
                                <option {{ (old('my_parent_id') == Qs::hash($p->id)) ? 'selected' : '' }} value="{{ Qs::hash($p->id) }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date Admitted: <span class="text-danger">*</span></label>
                            <input required name="date_admitted" value="{{ old('date_admitted') }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="dorm_id">Dormitory: </label>
                        <select data-placeholder="Choose..." name="dorm_id" id="dorm_id" class="select-search form-control">
                            <option value=""></option>
                            @foreach($dorms as $d)
                            <option {{ (old('dorm_id') == $d->id) ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dormitory Room No:</label>
                            <input type="text" name="dorm_room_no" placeholder="Dormitory Room No" class="form-control" value="{{ old('dorm_room_no') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Admission Number:</label>
                            <input type="text" name="adm_no" placeholder="Admission Number" class="form-control" placeholder="Eg., 0712" value="{{ old('adm_no') }}">
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
@endsection