@extends('layouts.master')
@section('page_title', 'My Notifications')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Notifications</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" class="ajax-update" data-reload="#breadcrumb" action="{{ route('notifications.update_is_notifiable') }}">
            @csrf @method('put')
            <div class="row">
                <div class="col-md-6">
                    {{-- Notifications Subscription --}}
                    <div class="form-group row">
                        <label for="is_notifiable" class="col-lg-9 col-form-label font-weight-semibold">Subscribe to Notifications (ie., Email) <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select class="form-control select" name="is_notifiable" id="is_notifiable">
                                <option {{ auth()->user()->is_notifiable ?: 'selected' }} value="1">Yes</option>
                                <option {{ auth()->user()->is_notifiable ?: 'selected' }} value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    {{--SUBMIT BUTTON--}}
                    <div class="text-right float-right">
                        <button type="submit" class="btn btn-danger">Submit form<span class="material-symbols-rounded ml-2 pb-2px">send</span></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Cloud messaging or Web push notification --}}
        <div class="row mt-2" id="notifications-row">
            <div class="col-12">
                <div id="token-div" class="display-none">
                    <div class="alert alert-info border-0"><span id="token-info"></span></div>
                    <button id="delete-token-button" class="btn btn-warning float-right">Delete Token</button>
                </div>
                <div id="permission-div">
                    <div class="alert alert-info border-0">
                        <span>
                            To receive push notifications you need to allow receiving notifications for this browser by clicking the button on the right,
                            or by enabling it manually.
                        </span>
                    </div>
                    <button id="request-permission-button" class="btn btn-primary float-right">Request Permission</button>
                </div>
            </div>
        </div>

        <hr class="divider">

        <table class="table datatable-button-html5-tab">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Subject</th>
                    <th>Intended</th>
                    <th>Created At</th>
                    <th>Read At</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(auth()->user()->notifications()->get() as $notification)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $notification->data['subject'] }} </td>
                    @php
                        $href = is_null($notification->read_at) ? route('payments.receipts', [Qs::hash($notification->data['receipt']['pr_id']), $notification->id]) : route('payments.receipts', Qs::hash($notification->data['receipt']['pr_id']));
                    @endphp
                    <td><a target="_blank" href="{{ $href }}">View Receipt</a></td>
                    <td>{{ $notification->created_at }}</td>
                    <td>{{ $notification->read_at ?? 'Never' }}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                <div class="dropdown-menu dropdown-menu-left">
                                    {{-- Mark as read --}}
                                    @if(is_null($notification->read_at))
                                    <a href="{{ route('notifications.mark_as_read', $notification->id) }}" class="dropdown-item"><i class="material-symbols-rounded">done</i> Mark as read</a>
                                    @endif
                                    {{-- Delete --}}
                                    <a id="{{ $notification->id }}" onclick="confirmDelete(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                    <form method="post" id="item-delete-{{ $notification->id }}" action="{{ route('notifications.destroy', $notification->id) }}" class="hidden">@csrf @method('delete')</form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(auth()->user()->unreadNotifications()->get()->isNotEmpty())
        <a href="javascript:;" id="mark_all_read" class="btn btn-warning float-right mt-3" onclick="confirmOperation(this.id)">Mark all Read<i class="material-symbols-rounded ml-1 pb-2px">done_all</i></a>
        <form id="item-confirm-operation-mark_all_read" method="POST" action="{{ route('notifications.mark_all_read') }}">@csrf @method('PUT')</form>
        @endif
    </div>
</div>

@endsection
