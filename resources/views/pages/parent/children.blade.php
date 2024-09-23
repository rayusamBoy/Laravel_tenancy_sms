@extends('layouts.master')
@section('page_title', 'My Children')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">My Children</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <table class="table datatable-button-html5-columns">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>ADM No</th>
                    <th>Section</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ Usr::getTenantAwarePhoto($s->user->photo) }}" alt="photo"></td>
                    <td>{{ $s->user->name }}</td>
                    <td>{{ $s->adm_no }}</td>
                    <td>{{ $s->my_class->name.' '.$s->section->name }}</td>
                    <td>{{ $s->user->email }}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" data-toggle="dropdown">
                                    <i class="material-symbols-rounded">lists</i>
                                </a>

                                <div class="{{ $students->count() > 3 ? 'dropdown-menu dropdown-menu-left' : 'dropdown-menu dropdown-menu-left dropdown-menu-position-static-md-desc' }}">
                                    <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                    <a target="_self" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">bottom_sheets</i> Marksheet</a>
                                    <a target="_self" href="{{ route('assessments.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">two_pager</i> Assessment Sheet </a>

                                    <div class="dropdown-divider"></div>

                                    <ul class="list-circle">
                                        <li><a href="{{ route('payments.status', [Qs::hash($s->user_id)]) }}" class="dropdown-item pl-2">All Payments</a></li>
                                        <li><a href="{{ route('payments.status', [Qs::hash($s->user_id), Qs::getCurrentSession()]) }}" class="dropdown-item pl-2">This Session's Payments</a></li>
                                    </ul>

                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

{{--Student List Ends--}}

@endsection
