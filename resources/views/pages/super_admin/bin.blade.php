@extends('layouts.master')

@section('page_title', 'Recycle Bin')
<style>
    .media-body .col-6 {
        text-align: end;
        margin-bottom: 0;
        margin: auto;
        padding: 0;
    }

    div img {
        max-width: 7em;
    }

    table {
        font-size: inherit;
    }

    .info span {
        color: brown;
    }

    .card:not(:first-child) .card-body {
        overflow-x: auto;
    }
</style>
@section('content')

@if($payments->isEmpty() && $users->isEmpty() && $my_classes->isEmpty() && $exams->isEmpty() && $threads->isEmpty())
<div class="text-center">
    <img src="{{ asset('global_assets\icons\bin-empty.gif') }}"></img>
    <h6>Bin Empty.</h6>
</div>
@else

<div class="alert alert-info border-0 alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    <span>Please Choose a record from the categories listed below, to restore it.<br /><strong>NB:</strong> Permanently deleting the record, will erase with all its associated data. Be very careful!</span>
</div>

{{--Payments--}}
@if($payments->isNotEmpty())
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Payment(s)</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-payments">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Ref No</th>
                            <th>Class</th>
                            <th>Method</th>
                            <th>Info</th>
                            <th>Deleted At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->title }}</td>
                            <td>{{ $p->amount }}</td>
                            <td>{{ $p->ref_no }}</td>
                            <td>{{ $p->my_class_id ? $p->my_class->name : 'All' }}</td>
                            <td>{{ ucwords($p->method) }}</td>
                            <td>{{ $p->description }}</td>
                            <td>{{ Qs::onlyDateFormat($p->deleted_at) }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="javascript:;" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--Restore--}}
                                            <a id="{{ $p->id }}" onclick="$('form#pay-restore-'+this.id).submit();" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">undo</i> Restore</a>
                                            <form class="d-none" method="post" id="pay-restore-{{ $p->id }}" action="{{ route('payments.restore', $p->id) }}" class="hidden">@csrf</form>
                                            {{--Delete--}}
                                            <a id="{{ $p->id }}" onclick="confirmForceDelete(this.id, 'payment')" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form class="d-none" method="post" id="payment-force-delete-{{ $p->id }}" action="{{ route('payments.force_delete', $p->id) }}" class="hidden">@csrf</form>
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
    </div>
    <div class="card-footer p-1">
        <button id="payments" type="button" onclick="confirmPermanentDeleteTwice(this.id)" class="btn btn-danger btn-sm float-right"><i class="material-symbols-rounded">delete</i> Empty Payments</button>
        <form method="post" id="item-delete-payments" action="{{ route('model.empty_soft_deleted', 'payments') }}" class="hidden">@csrf @method('delete')</form>
    </div>
</div>
@endif

{{--Users--}}
@if($users->isNotEmpty())
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Users</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Usertype</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Deleted At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ str_starts_with($user->photo, "global_assets") ? asset($user->photo) : tenant_asset($user->photo) }}" alt="photo"></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucwords(str_replace('_', ' ',$user->user_type)) }}</td>
                            @if($user->user_type == 'student')
                            @php $s_recs = Usr::getStudentRecordByUserId($user->id, ['my_class', 'section']) @endphp
                            <td class="text-center" colspan="2">{{ $s_recs->first()->my_class->name . ' - ' . $s_recs->first()->section->name }}</td>

                            @else
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            @endif
                            <td>{{ Qs::onlyDateFormat($user->deleted_at) }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="javascript:;" data-toggle="dropdown">
                                            <i class="material-symbols-rounded">lists</i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--Restore--}}
                                            <a id="{{ $user->id }}" onclick="$('form#user-restore-'+this.id).submit();" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">undo</i> Restore</a>
                                            <form class="d-none" method="post" id="user-restore-{{ $user->id }}" action="{{ route('user.restore', $user->id) }}" class="hidden">@csrf</form>
                                            {{--Delete--}}
                                            <a id="{{ $user->id }}" onclick="confirmForceDelete(this.id, 'user')" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form class="d-none" method="post" id="user-force-delete-{{ $user->id }}" action="{{ route('user.force_delete', $user->id) }}" class="hidden">@csrf</form>
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
    </div>
    <div class="card-footer p-1">
        <button id="users" type="button" onclick="confirmPermanentDeleteTwice(this.id)" class="btn btn-danger btn-sm float-right"><i class="material-symbols-rounded">delete</i> Empty Users</button>
        <form method="post" id="item-delete-users" action="{{ route('model.empty_soft_deleted', 'users') }}" class="hidden">@csrf @method('delete')</form>
    </div>
</div>
@endif

{{--My Classes--}}
@if($my_classes->isNotEmpty())
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Class(es)</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-pane fade show active" id="all-classes">
            <table class="table datatable-button-html5-columns">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Class Type</th>
                        <th>Deleted At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($my_classes as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->class_type->name }}</td>
                        <td>{{ Qs::onlyDateFormat($c->deleted_at) }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="javascript:;" data-toggle="dropdown">
                                        <i class="material-symbols-rounded">lists</i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        {{--Retore--}}
                                        <a id="{{ $c->id }}" onclick="$('form#my_class-restore-'+this.id).submit();" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">undo</i> Restore</a>
                                        <form class="d-none" method="post" id="my_class-restore-{{ $c->id }}" action="{{ route('my_class.restore', $c->id) }}" class="hidden">@csrf</form>
                                        {{--Delete--}}
                                        <a id="{{ $c->id }}" onclick="confirmForceDelete(this.id, 'my_class')" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                        <form class="d-none" method="post" id="my_class-force-delete-{{ $c->id }}" action="{{ route('my_class.force_delete', $c->id) }}" class="hidden">@csrf</form>
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
    <div class="card-footer p-1">
        <button id="my_classes" type="button" onclick="confirmPermanentDeleteTwice(this.id)" class="btn btn-danger btn-sm float-right"><i class="material-symbols-rounded">delete</i> Empty Classes</button>
        <form method="post" id="item-delete-my_classes" action="{{ route('model.empty_soft_deleted', 'my_classes') }}" class="hidden">@csrf @method('delete')</form>
    </div>
</div>
@endif

{{--Exams--}}
@if($exams->isNotEmpty())
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Exam(s)</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-pane fade show active" id="all-exams">
            <table class="table datatable-button-html5-columns">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Term</th>
                        <th>Session</th>
                        <td>Category</td>
                        <td>Edit Status</td>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $ex)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ex->name }}</td>
                        <td>{{ 'Term '.$ex->term }}</td>
                        <td>{{ $ex->year }}</td>
                        <td>{{ $ex->category->name }}</td>
                        <td>{!! ($ex->editable) ? '<i class="material-symbols-rounded">check</i>' : '<i class="material-symbols-rounded">close</i>' !!}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="javascript:;" data-toggle="dropdown">
                                        <i class="material-symbols-rounded">lists</i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        {{--Retore--}}
                                        <a id="{{ $ex->id }}" onclick="$('form#exam-restore-'+this.id).submit();" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">undo</i> Restore</a>
                                        <form class="d-none" method="post" id="exam-restore-{{ $ex->id }}" action="{{ route('exam.restore', $ex->id) }}" class="hidden">@csrf</form>
                                        {{--Delete--}}
                                        <a id="{{ $ex->id }}" onclick="confirmForceDelete(this.id, 'exam')" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                        <form class="d-none" method="post" id="exam-force-delete-{{ $ex->id }}" action="{{ route('exam.force_delete', $ex->id) }}" class="hidden">@csrf</form>
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
    <div class="card-footer p-1">
        <button id="exams" type="button" onclick="confirmPermanentDeleteTwice(this.id)" class="btn btn-danger btn-sm float-right"><i class="material-symbols-rounded">delete</i> Empty Exams</button>
        <form method="post" id="item-delete-exams" action="{{ route('model.empty_soft_deleted', 'exams') }}" class="hidden">@csrf @method('delete')</form>
    </div>
</div>
@endif

{{--Messges Threads--}}
@if($threads->isNotEmpty())
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Messages Thread(s)</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-pane fade show active" id="all-exams">
            <table class="table datatable-button-html5-columns">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Subject</th>
                        <th>Creator</th>
                        <th>Latest Message</th>
                        <th>Participant(s)</th>
                        <td>Created At</td>
                        <td>Deleted At</td>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($threads as $thread)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $thread->subject }}</td>
                        <td>{{ $thread->creator()->name }}</td>
                        <td>{{ $thread->getLatestMessageAttribute()->body ?? '' }}</td>
                        @include('pages/modals/participants')
                        <td> <a href="javascript:;" data-toggle="modal" data-target="#thread-participants-{{ $thread->id }}">View Participant(s)</a></td>
                        <td>{{ $thread->created_at }}</td>
                        <td>{{ $thread->deleted_at }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="javascript:;" data-toggle="dropdown">
                                        <i class="material-symbols-rounded">lists</i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        {{--Retore--}}
                                        <a id="{{ $thread->id }}" onclick="$('form#thread-restore-'+this.id).submit();" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">undo</i> Restore</a>
                                        <form class="d-none" method="post" id="thread-restore-{{ $thread->id }}" action="{{ route('thread.restore', $thread->id) }}" class="hidden">@csrf</form>
                                        {{--Delete--}}
                                        <a id="{{ $thread->id }}" onclick="confirmForceDelete(this.id, 'thread')" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                        <form class="d-none" method="post" id="thread-force-delete-{{ $thread->id }}" action="{{ route('thread.force_delete', $thread->id) }}" class="hidden">@csrf</form>
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
    <div class="card-footer p-1">
        <button id="threads" type="button" onclick="confirmPermanentDeleteTwice(this.id)" class="btn btn-danger btn-sm float-right"><i class="material-symbols-rounded">delete</i> Empty Threads</button>
        <form method="post" id="item-delete-threads" action="{{ route('model.empty_soft_deleted', 'threads') }}" class="hidden">@csrf @method('delete')</form>
    </div>
</div>
@endif

@endif

@endsection