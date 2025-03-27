@extends('layouts.master')

@section('page_title', 'Edit Student')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 id="ajax-title" class="card-title">Please fill The form Below To Edit record of {{ $sr->user->name }}</h6>

        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-update" data-reload="#ajax-title" action="{{ route('students.update', Qs::hash($sr->id)) }}" data-fouc>
        @csrf @method('PUT')
        <h6>Personal data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Full Name: <span class="text-danger">*</span></label>
                        <input value="{{ $sr->user->name }}" required type="text" id="name" name="name" placeholder="Full Name" class="form-control text-uppercase">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Address: <span class="text-danger">*</span></label>
                        <input value="{{ $sr->user->address }}" class="form-control" id="address" placeholder="Address" name="address" type="text" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email">Email address:</label>
                        <input value="{{ $sr->user->email  }}" id="email" type="email" name="email" class="form-control" placeholder="your@email.com">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gender">Gender: <span class="text-danger">*</span></label>
                        <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                            <option value=""></option>
                            @foreach(Usr::getGenders() as $gender)
                            <option @selected($sr->user->gender == $gender) value="{{ $gender }}">{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input value="{{ $sr->user->phone  }}" id="phone" type="text" name="phone" class="form-control" placeholder="">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="telephone">Telephone:</label>
                        <input value="{{ $sr->user->phone2  }}" type="text" id="telephone" name="phone2" class="form-control" placeholder="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dob">Date of Birth: <span class="text-danger">*</span></label>
                        <input name="dob" value="{{ $sr->user->dob  }}" id="dob" required type="text" class="form-control date-pick" placeholder="Select Date...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                        <select onchange="getState(this.value)" data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                            <option value=""></option>
                            @foreach($nationals as $na)
                            <option @selected($sr->user->nal_id == $na->id) value="{{ $na->id }}">{{ $na->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="state_id">State: <span class="text-danger">*</span></label>
                        <select onchange="getLGA(this.value)" required data-placeholder="Choose.." class="select-search form-control" name="state_id" id="state_id">
                            @foreach($states->where('nationality_id', $sr->user->nal_id) as $st)
                            <option @selected($sr->user->state_id == $st->id) value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="lga_id">LGA: <span class="text-danger">*</span></label>
                        <select required data-placeholder="Select State First" class="select-search form-control" name="lga_id" id="lga_id">
                            @if($sr->user->lga_id)
                            <option selected value="{{ $sr->user->lga_id }}">{{ $sr->user->lga->name}}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="food_taboos" class="w-100">Food Taboos:
                            <i class="material-symbols-rounded float-right text-info" data-placement="left" data-toggle="tooltip" title="Refer to the restriction of specific food as a result of social, religious customs, or health problem(s).">info</i>
                        </label>
                        <input type="text" id="food_taboos" name="food_taboos" placeholder="Mention (if presents)" class="form-control" value="{{ $sr->food_taboos }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="disability">Disability:</label>
                        <select data-placeholder="Choose..." name="disability" id="disability" class="select-search form-control">
                            <option value=""></option>
                            @foreach(Usr::getDisabilities() as $key => $value)
                            <option title="{{ $key }}" @selected($sr->disability == $value) value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="p_status" class="w-100">Parent Status: <span class="text-danger">*</span>
                            <i class="material-symbols-rounded float-right text-info" data-toggle="tooltip" title="Choose option, search option, or type to add option">info</i>
                        </label>
                        <select required data-placeholder="Choose or type to add" name="p_status" id="p_status" class="select-tags form-control">
                            <option value=""></option>
                            @foreach(Usr::getStudentParentsStatus() as $status)
                            <option @selected($sr->p_status == $status) value="{{ $status }}">{{ $status }}</option>
                            @endforeach

                            @if(!in_array($sr->p_status, Usr::getStudentParentsStatus()))
                            <option selected value="{{ $sr->p_status }}">{{ $sr->p_status }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="bg_id">Blood Group:</label>
                        <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                            <option value=""></option>
                            @foreach(Usr::getBloodGroups() as $bg)
                            <option @selected($sr->user->bg_id == $bg->id) value="{{ $bg->id }}">{{ $bg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ps_name">Primary School Name: <span class="text-danger">*</span></label>
                        <input name="ps_name" id="ps_name" value="{{ $sr->ps_name ?? '' }}" type="text" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="photo" class="d-block">Upload Passport Photo:</label>
                        <input value="{{ old('photo') }}" id="photo" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                        <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ss_name">Secondary School Name:</label>
                        <input name="ss_name" id="ss_name" value="{{ $sr->ss_name ?? '' }}" type="text" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="house_no">House Number:</label>
                        <input type="text" name="house_no" id="house_no" placeholder="House number" class="form-control text-uppercase" value="{{ $sr->house_no }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="birth_certificate" class="d-block">Upload Birth Certificate:</label>
                        <input value="{{ old('birth_certificate') }}" id="birth_certificate" accept=".pdf, .jpg, .jpeg, .png" type="file" name="birth_certificate" class="form-input-styled" data-fouc>
                        <span class="form-text text-muted">Accepted Images: pdf, png, jpg, jpeg. Max file size 2Mb</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="religion">Religion: <span class="text-danger">*</span></label>
                        <select required data-placeholder="Choose..." name="religion" id="religion" class="select form-control">
                            <option value=""></option>
                            @foreach(Usr::getReligions() as $rel)
                            <option @selected(($sr->user->religion == $rel)) value="{{ $rel }}">{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label for="chp">Chronic Health Problem(s):</label>
                        <input type="text" name="chp" id="chp" placeholder="Mention (if presents)" class="form-control" value="{{ $sr->chp }}">
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
                        <select onchange="getClassSections(this.value)" name="my_class_id" required id="my_class_id" class="form-control select-search" data-placeholder="Select Class">
                            <option value=""></option>
                            @foreach($my_classes as $c)
                            <option @selected($sr->my_class_id == $c->id) value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="section_id">Section: <span class="text-danger">*</span></label>
                        <select name="section_id" required id="section_id" class="form-control select" data-placeholder="Select Section">
                            @foreach($class_sections as $section)
                            <option @selected($sr->section_id == $section->id) value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="my_parent_id">Parent:</label>
                        <select data-placeholder="Choose..." name="my_parent_id" id="my_parent_id" class="select-search form-control">
                            <option value=" ">None</option>
                            @foreach($parents as $p)
                            <option @selected(Qs::hash($sr->my_parent_id) == Qs::hash($p->id)) value="{{ Qs::hash($p->id) }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date_admitted">Date Admitted: <span class="text-danger">*</span></label>
                        <input required name="date_admitted" id="date_admitted" value="{{ $sr->date_admitted }}" type="text" class="form-control date-pick" placeholder="Select Date...">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="dorm_id">Dormitory:</label>
                    <select data-placeholder="Choose..." name="dorm_id" id="dorm_id" class="select-search form-control">
                        <option value=""></option>
                        @foreach($dorms as $d)
                        <option title="{{ $d->description }}" @selected($sr->dorm_id == $d->id) value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dorm_room_no">Dormitory Room No:</label>
                        <input type="text" id="dorm_room_no" name="dorm_room_no" placeholder="Dormitory Room No" class="form-control" value="{{ $sr->dorm_room_no }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="adm_no">Admission Number:</label>
                        <input type="text" id="adm_no" name="adm_no" placeholder="Admission Number" class="form-control" value="{{ $sr->adm_no }}">
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>

@endsection
