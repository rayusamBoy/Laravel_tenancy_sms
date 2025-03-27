@extends('layouts.master')

@section('page_title', "User Profile - {$user->name}")

@section('content')

<div class="row">
    <div class="col-md-3 text-center">
        <div class="card">
            <div class="card-body">
                <img style="width: 90%; height:90%" src="{{ asset($user->photo) }}" alt="photo" class="rounded-circle">
                <br>
                <h3 class="mt-3">{{ $user->name }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="nav-item">
                        <a href="#basic-info" class="nav-link active" data-toggle="tab">Basic Info</a>
                    </li>
                    @if((isset($staff_rec) && $staff_rec != null) && (Qs::userIsHead() || $user->id == auth()->id()))
                    <li class="nav-item">
                        <a href="#staff-info" class="nav-link" data-toggle="tab">Staff Info</a>
                    </li>
                    @endif
                </ul>

                <div class="tab-content">
                    {{--Basic Info--}}
                    <div class="tab-pane fade show active" id="basic-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Name</td>
                                    <td class="break-all">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td>{{ $user->gender }}</td>
                                </tr>
                                @if($user->religion)
                                <tr>
                                    <td class="font-weight-bold">Religion</td>
                                    <td class="break-all">{{ $user->religion }}</td>
                                </tr>
                                @endif
                                @if($user->email)
                                <tr>
                                    <td class="font-weight-bold">Email</td>
                                    <td class="break-all">{{ $user->email }}</td>
                                </tr>
                                @endif
                                @if($user->primary_id)
                                <tr>
                                    <td class="font-weight-bold">Primary ID</td>
                                    <td class="break-all">{{ $user->primary_id ?? '-' }}</td>
                                </tr>
                                @endif
                                @if($user->secondary_id)
                                <tr>
                                    <td class="font-weight-bold">Secondary ID</td>
                                    <td class="break-all">{{ $user->secondary_id ?? '-' }}</td>
                                </tr>
                                @endif
                                @if($user->username)
                                <tr>
                                    <td class="font-weight-bold">Username</td>
                                    <td class="break-all">{{ $user->username }}</td>
                                </tr>
                                @endif
                                @if($user->phone)
                                <tr>
                                    <td class="font-weight-bold">Phone</td>
                                    <td>{{ $user->phone.' '.$user->phone2 }}</td>
                                </tr>
                                @endif
                                @if($user->dob)
                                <tr>
                                    <td class="font-weight-bold">Birthdate</td>
                                    <td class="break-all">{{ $user->dob }}</td>
                                </tr>
                                @endif
                                @if($user->bg_id)
                                <tr>
                                    <td class="font-weight-bold">Blood Group</td>
                                    <td>{{ $user->blood_group->name }}</td>
                                </tr>
                                @endif
                                @if($user->nal_id)
                                <tr>
                                    <td class="font-weight-bold">Nationality</td>
                                    <td>{{ $user->nationality->name }}</td>
                                </tr>
                                @endif
                                @if($user->state_id)
                                <tr>
                                    <td class="font-weight-bold">State</td>
                                    <td>{{ $user->state->name }}</td>
                                </tr>
                                @endif
                                @if($user->lga_id)
                                <tr>
                                    <td class="font-weight-bold">LGA</td>
                                    <td>{{ $user->lga->name }}</td>
                                </tr>
                                @endif
                                @if($user->address)
                                <tr>
                                    <td class="font-weight-bold">Address</td>
                                    <td class="break-all">{{ $user->address }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{--Staff Info--}}
                    @if((isset($staff_rec) && $staff_rec != null) && (Qs::userIsHead() || $user->id == auth()->id()))
                    <div class="tab-pane fade" id="staff-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Date of Employment</td>
                                    <td class="break-all">{{ $staff_rec->emp_date ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Confirmation Date</td>
                                    <td class="break-all">{{ $staff_rec->confirmation_date ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Licence Number</td>
                                    <td class="break-all">{{ $staff_rec->licence_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">File Number</td>
                                    <td class="break-all">{{ $staff_rec->file_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">ZSSSF Number</td>
                                    <td class="break-all">{{ $staff_rec->ss_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Employment Number</td>
                                    <td class="break-all">{{ $staff_rec->emp_no ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">TIN Number</td>
                                    <td class="break-all">{{ $staff_rec->tin_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Bank Account</td>
                                    <td class="break-all">{{ $staff_rec->bank_acc_no ?? '-' }} {{ $staff_rec->bank_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Education Level</td>
                                    <td class="break-all">{{ $staff_rec->education_level ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">College Attended</td>
                                    <td class="break-all">{{ $staff_rec->college_attended ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Graduation Yaar</td>
                                    <td class="break-all">{{ $staff_rec->year_graduated ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Role</td>
                                    <td class="break-all">{{ $staff_rec->role ?? '-' }}</td>
                                </tr>
                                @if(isset($staff_rec->subjects_studied))
                                <tr>
                                    <td class="font-weight-bold">Subjects Studied</td>
                                    <td class="break-all">
                                        @foreach(json_decode($staff_rec->subjects_studied) as $sub)
                                        {{ $loop->iteration }} - {{ $sub ?? '-' }}<br />
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{--User Profile Ends--}}

@endsection
