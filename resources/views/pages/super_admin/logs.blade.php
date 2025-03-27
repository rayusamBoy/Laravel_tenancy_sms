@extends('layouts.master')

@section('page_title', 'Manage Logs')

@section('content')

<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Login Histories</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            {{--Login Histories--}}
            <li class="nav-item dropdown">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown">Select User Type</a>
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
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ Usr::getTenantAwarePhoto($lh->user->photo) }}" alt="photo"></td>
                            <td>{{ $lh->user->name }}</td>
                            <td>{{ $lh->login_times }}</td>
                            <td>{{ Qs::fullDateTimeFormat($lh->created_at) }}</td>
                            <td>{{ ($lh->last_login == NULL) ? 'Never' : Qs::fullDateTimeFormat($lh->last_login) }}</td>
                            <td>{{ ($lh->last_reset == NULL) ? 'Never' : Qs::fullDateTimeFormat($lh->last_reset) }}</td>

                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="javascript:;" class="material-symbols-rounded" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- View Profile --}}
                                            <a href="{{ route('users.show', Qs::hash($lh->user->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">visibility</i> View Profile</a>
                                            {{--Reset Login history--}}
                                            @php
                                            $route = route('login_histories.reset', Qs::hash($lh->user->id));
                                            @endphp
                                            <a href="javascript:;" onclick="confirmReset(null, '{{ $route }}')" class="dropdown-item"><i class="material-symbols-rounded">history</i> Reset Login History</a>
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
                        @foreach($activities->chunk(800) as $chunk)
                        @foreach($chunk as $chk)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $chk->event }}</td>
                            @php
                            $subject_type_exploded = explode("\\", $chk->subject_type);
                            @endphp
                            <td>{{ end($subject_type_exploded) }}</td>
                            @php
                            $causer_type_exploded = explode("\\", $chk->causer_type);
                            @endphp
                            <td>{{ $chk?->causer_type ? end($causer_type_exploded) : 'N/A' }}</td>
                            <td>{{ $chk?->user?->name ?? 'N/A' }}</td>
                            <td>
                                @include('pages/modals/activity_log_properties')
                                <a href="javascript:;" data-toggle="modal" data-target="#activity-log-properties-{{ $chk->id }}">show</a>
                            </td>
                            <td>{{ Qs::fullDateTimeFormat($chk->created_at) }}</td>
                            <td class="text-center">
                                {{--Delete--}}
                                <a id="{{ $chk->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="btn btn-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                <form class="d-none" method="post" id="item-delete-{{ $chk->id }}" action="{{ route('activity_log.delete', $chk->id) }}" class="hidden">@csrf</form>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Activity Log clean --}}
    <div class="m-2">
        <button class="btn btn-danger float-right" onclick="confirmOperation(this.id)" id="activity_log_clean" data-toggle="tooltip" title="The deletion of all recorded activity that is older than {{ config('activitylog.delete_records_older_than_days') }} days">Activity Log Clean</button>
        <form method="post" id="item-confirm-operation-activity_log_clean" action="{{ route('activity_log.clean') }}" class="hidden ajax-update page-block">@csrf</form>
    </div>

</div>

@endsection
