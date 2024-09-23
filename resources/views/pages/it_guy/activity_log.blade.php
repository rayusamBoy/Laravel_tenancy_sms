@extends('layouts.master')
@section('page_title', 'Manage Activity Log')
@section('content')

<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Login Histories</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            {{--Login Histories--}}
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Select User Type</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach ($user_types as $ut)
                    <a href="#ut-{{ Qs::hash($ut->id) }}" class="dropdown-item" data-toggle="tab">{{ $ut->name }}s</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            @foreach ($user_types as $ut)
            <div class="tab-pane fade" id="ut-{{ Qs::hash($ut->id) }}">
                <span class="status-styled">{{ $ut->name }}s</span>

                <table class="table datatable-button-html5-columns ">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Login Times</th>
                            <th>First Login</th>
                            <th>Last Login</th>
                            <th>Last Reset</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($login_histories->where('user.user_type', $ut->title) as $lh)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ tenant_asset($lh->user->photo) }}" alt="photo"></td>
                            <td>{{ $lh->user->name }}</td>
                            <td>{{ $lh->login_times }}</td>
                            <td>{{ Qs::fullDateTimeFormat($lh->created_at) }}</td>
                            <td>{{ ($lh->last_login == NULL) ? 'Never' : Qs::fullDateTimeFormat($lh->last_login) }}</td>
                            <td>{{ ($lh->last_reset == NULL) ? 'Never' : Qs::fullDateTimeFormat($lh->last_reset) }}</td>

                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="material-symbols-rounded" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- View Profile --}}
                                            <a href="{{ route('users.show', Qs::hash($lh->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                            {{--Reset Login history--}}
                                            @php
                                            $route = route('logs.login_history_reset', Qs::hash($lh->user->id));
                                            @endphp
                                            <a href="#" onclick="confirmReset(null, '{{ $route }}')" class="dropdown-item"><i class="material-symbols-rounded">history</i> Reset Login History</a>
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

<div class="card card-collapsed w-fit wmin-100-pcnt">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Activity Logs</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-payments">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event</th>
                            <th>Subject Type</th>
                            <th>Causer Type</th>
                            <th>Causer</th>
                            <th>Properties</th>
                            <th>Created At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $a)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $a->event }}</td>
                            <td>{{ $a->subject_type }}</td>
                            <td>{{ $a->causer_type }}</td>
                            <td>{{ $a->user->name ?? $a->causer_id }}</td>
                            <td>{{ $a->properties }}</td>
                            <td>{{ Qs::onlyDateFormat($a->created_at) }}</td>
                            <td class="text-center">
                                {{--Delete--}}
                                <a id="{{ $a->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="btn btn-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                <form class="d-none" method="post" id="item-delete-{{ $a->id }}" action="{{ route('logs.activity_log_delete', $a->id) }}" class="hidden">@csrf</form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection