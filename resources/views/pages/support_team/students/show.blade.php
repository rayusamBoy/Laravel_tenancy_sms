@extends('layouts.master')
@section('page_title', 'Student Profile - '.$sr->user->name)
@section('content')

<div class="row">
    <div class="col-md-3 text-center">
        <div class="card">
            <div class="card-body">
                <img style="width: 90%; height: 90%" src="{{ Usr::getTenantAwarePhoto($sr->user->photo) }}" alt="photo" class="rounded-circle">
                <br>
                <h3 class="mt-3">{{ $sr->user->name }}</h3>
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
                    <li class="nav-item">
                        <a href="#school-info" class="nav-link" data-toggle="tab">School Info</a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{--Basic Info--}}
                    <div class="tab-pane fade show active" id="basic-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Name</td>
                                    <td>{{ $sr->user->name }}</td>
                                </tr>
                                @if($sr->my_parent_id)
                                <tr>
                                    <td class="font-weight-bold">Parent</td>
                                    <td>
                                        <span><a target="_blank" href="{{ route('users.show', Qs::hash($sr->my_parent_id)) }}">{{ $sr->my_parent->name }}</a></span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td>{{ $sr->user->gender }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Address</td>
                                    <td>{{ $sr->user->address }}</td>
                                </tr>
                                @if($sr->user->email)
                                <tr>
                                    <td class="font-weight-bold">Email</td>
                                    <td>{{ $sr->user->email }}</td>
                                </tr>
                                @endif
                                @if($sr->user->phone)
                                <tr>
                                    <td class="font-weight-bold">Phone</td>
                                    <td>{{ $sr->user->phone.' '.$sr->user->phone2 }}</td>
                                </tr>
                                @endif
                                @if($sr->user->dob)
                                <tr>
                                    <td class="font-weight-bold">Birthday</td>
                                    <td>{{ $sr->user->dob }}</td>
                                </tr>
                                @endif
                                @if($sr->user->bg_id)
                                <tr>
                                    <td class="font-weight-bold">Blood Group</td>
                                    <td>{{ $sr->user->blood_group->name }}</td>
                                </tr>
                                @endif
                                @if($sr->user->nal_id)
                                <tr>
                                    <td class="font-weight-bold">Nationality</td>
                                    <td>{{ $sr->user->nationality->name }}</td>
                                </tr>
                                @endif
                                @if($sr->user->state_id)
                                <tr>
                                    <td class="font-weight-bold">State</td>
                                    <td>{{ $sr->user->state->name }}</td>
                                </tr>
                                @endif
                                @if($sr->user->lga_id)
                                <tr>
                                    <td class="font-weight-bold">LGA</td>
                                    <td>{{ $sr->user->lga->name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="font-weight-bold">Religion</td>
                                    <td>{{ $sr->user->religion }}</td>
                                </tr>
                                @if($sr->food_taboos)
                                <tr>
                                    <td class="font-weight-bold">Food Taboos</td>
                                    <td>{{$sr->food_taboos }}</td>
                                </tr>
                                @endif
                                @if($sr->disability)
                                <tr>
                                    <td class="font-weight-bold">Disability</td>
                                    <td>{{$sr->disability }}</td>
                                </tr>
                                @endif
                                @if($sr->chp)
                                <tr>
                                    <td class="font-weight-bold">Chronic Health Problem</td>
                                    <td>{{$sr->chp }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{--School Info--}}
                    <div class="tab-pane fade show" id="school-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Status</td>
                                    <td>{{ $sr->grad == 1 ? 'Graduate' . ' (' . $sr->grad_date . ')' : 'Active' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Admission Number</td>
                                    <td>{{ $sr->adm_no }}</td>
                                </tr>
                                @if($sr->p_status)
                                <tr>
                                    <td class="font-weight-bold">Parental status</td>
                                    <td>{{ $sr->p_status }}</td>
                                </tr>
                                @endif
                                @if($sr->ps_name)
                                <tr>
                                    <td class="font-weight-bold">Primary School</td>
                                    <td>{{ $sr->ps_name }}</td>
                                </tr>
                                @endif
                                @if($sr->ss_name)
                                <tr>
                                    <td class="font-weight-bold">Secondary School</td>
                                    <td>{{ $sr->ss_name }}</td>
                                </tr>
                                @endif
                                @if($sr->house_no)
                                <tr>
                                    <td class="font-weight-bold">House Number</td>
                                    <td>{{ $sr->house_no }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="font-weight-bold">Class</td>
                                    <td>{{ $sr->my_class->name }} - {{ $sr->section->name }}</td>
                                </tr>
                                @if($sr->user->nal_id)
                                <tr>
                                    <td class="font-weight-bold">Nationality</td>
                                    <td>{{ $sr->user->nationality->name }}</td>
                                </tr>
                                @endif
                                @if($sr->user->state_id)
                                <tr>
                                    <td class="font-weight-bold">State</td>
                                    <td>{{ $sr->user->state->name }}</td>
                                </tr>
                                @endif
                                @if($sr->user->lga_id)
                                <tr>
                                    <td class="font-weight-bold">LGA</td>
                                    <td>{{ $sr->user->lga->name }}</td>
                                </tr>
                                @endif
                                @if($sr->date_admitted)
                                <tr>
                                    <td class="font-weight-bold">Date Admitted</td>
                                    <td>{{$sr->date_admitted }}</td>
                                </tr>
                                @endif
                                @if($sr->birth_certificate && Qs::userIsTeamSA())
                                <tr>
                                    <td class="font-weight-bold">Birth Certificate</td>
                                    <td><a href="{{ tenant_asset($sr->birth_certificate) }}">View</a></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{--Student Profile Ends--}}

@endsection
