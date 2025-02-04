@extends('layouts.master')
@section('page_title', 'My Notifications')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Notifications</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning border-0 alert-dismissible has-do-not-show-again-button" id="notifications-info">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    Enable push notifications by clicking "Request Permission" button or manually. Critical notifications are enabled by default for security and performance. Adjust other notifications below.
               </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('notifications.update_is_notifiable') }}">
            @csrf @method('put')
            <div class="form-group row">
                <div class="col-lg-3">
                    <label for="email_channel" class="col-form-label font-weight-semibold">Email notification</label>
                    <div class="form-group text-center">
                        <select class="form-control select" name="on_email_channel" id="email_channel">
                            <option {{ $email_channel_on == 1 ? 'selected' : '' }} value="1">On</option>
                            <option {{ $email_channel_on == 0 ? 'selected' : '' }} value="0">Off</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="push_channel" class="col-form-label font-weight-semibold">Push notification</label>
                    <div class="form-group text-center">
                        <select class="form-control select" name="on_push_channel" id="push_channel">
                            <option {{ $push_channel_on == 1 ? 'selected' : '' }} value="1">On</option>
                            <option {{ $push_channel_on == 0 ? 'selected' : '' }} value="0">Off</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="sms_channel" class="col-form-label font-weight-semibold">SMS notification</label>
                    <div class="form-group text-center">
                        <select class="form-control select" name="on_sms_channel" id="sms_channel">
                            <option {{ $sms_channel_on == 1 ? 'selected' : '' }} value="1">On</option>
                            <option {{ $sms_channel_on == 0 ? 'selected' : '' }} value="0">Off</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="status" class="col-form-label font-weight-semibold">Allow all Notifications <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <select class="form-control select" name="status" id="status">
                            <option {{ $notification_status == 1 ? 'selected' : '' }} value="1">Yes</option>
                            <option {{ $notification_status == 0 ? 'selected' : '' }} value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                 <div class="offset-lg-9 col-lg-3">
                    {{-- SUBMIT BUTTON --}}
                    <div class="form-group float-right">
                        <button type="submit" class="btn btn-sm btn-danger">Submit form<span class="material-symbols-rounded ml-2 pb-2px">send</span></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Cloud messaging or Web push notification --}}
        <div class="row mt-2" id="notifications-row">
            <div class="col-12">
                <div id="token-div" class="display-none">
                    <div class="alert alert-info border-0"><span id="token-info"></span></div>
                    <button id="delete-token-button" class="btn btn-warning btn-sm float-right ml-1 d-none">Delete Token</button>
                </div>
                <div id="permission-div">
                    <button id="request-permission-button" class="btn btn-primary btn-sm float-right">Request Permission</button>
                </div>
            </div>
        </div>

        <hr class="divider">

        <table class="table datatable-button-html5-tab">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Subject</th>
                    <th>Created At</th>
                    <th>Read At</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(auth()->user()->notifications()->get() as $notification)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a target="_blank" @if(isset($notification->data['receipt'])) href="{{ route('payments.receipts', [Qs::hash($notification->data['receipt']['pr_id']), $notification->id]) }}" @elseif($notification->data['url']) href="{{ $notification->data['url'] }}" @endif><strong>{{ $notification->data['subject'] }}</strong></a>
                    </td>
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
