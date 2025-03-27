@extends('layouts.master')

@section('page_title', 'Account Security')

@section('content')

{{-- 2fa set up --}}
<div class="{{ $google2FAIsActive ? 'card card-collapsed' : 'card' }}">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-semibold">Google Two Factor Authentication</h6>
        <div class="header-elements">
            <div class="list-icons">
                <span class="list-icons-item">@if($google2FAIsActive)<span class="badge bg-success font-size-base">active</span>@else <span class="badge bg-warning font-size-base">inactive</span> @endif</a></span>
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="remove"></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0 alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    With 2-Step Verification, also called two-factor authentication, you can add an extra layer of security to your account in case your password is stolen. After you set up 2-Step Verification, you can sign in to your account with:
                    <strong>Your password</strong> and <strong>Your phone</strong>.
                    <strong class="text-danger">
                        You must first download the Authenticator application from
                        <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US&pli=1">Play store</a>
                        or
                        <a target="_blank" href="https://apps.apple.com/us/app/google-authenticator/id388497605">App store</a>
                        before using Google authentication service.
                    </strong>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="google-2fa">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            @if(!$google2FAIsActive) <h4 class="text-center mt-4">Set up Google Authenticator</h4> @endif

                            <div class="text-center mt-2">

                                @if(!$google2FAIsActive)
                                <p class="mb-4">Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code <strong class="text-danger spacing-3 ml-2 item-output">{{ $twofa_secret_code }}</strong><button data-toggle="tooltip" title="Copy code" data-title="Copy code" class="item-copy material-symbols-rounded btn bg-transparent float-right font-size-xl p-0">content_copy</button></p>
                                <div class="d-inline-flex">
                                    {!! $QR_Image !!}
                                </div>

                                <form method="post" action="{{ route('2fa.update_secret_codes') }}">
                                    @csrf
                                    <input type="hidden" name="twofa_secret_code" value="{{ $twofa_secret_code }}">
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary">Complete Set up</button>
                                    </div>
                                </form>
                                @endif

                                @if($google2FAIsActive)
                                <a id="google-2fa" onclick="confirmOperation(this.id)" href="javascript:;" class="btn btn-danger"> Deactivate</a>
                                <form method="post" id="item-confirm-operation-google-2fa" action="{{ route('2fa.null_secret_code') }}" class="hidden">@csrf @method('patch')</form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2fa recovery codes --}}
@if(!$google2FAIsActive)
<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Google Two Factor Authentication Recovery Codes</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0 alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    Get recovery/backup codes to provide a way for you to recover from a lost two factor authentication, or any problem with it. Please note down these codes in a secure place.
                    And then you may use any one of them to recover your account.
                </span>
            </div>
        </div>
    </div>

    <div class="card-body pr-0 pl-0">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="google-2fa">
                <div class="container">
                    <div class="row">
                        <div class="col-12" id="ajax-codes">
                            <ul class="list-style-circle">
                                <button data-toggle="tooltip" title="Copy codes" data-title="Copy codes" class="item-copy material-symbols-rounded btn bg-transparent float-right font-size-xl p-0">content_copy</button>
                                @foreach($recovery_codes as $code)
                                <li class="spacing-2 font-size-xl item-output">{{ $code }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form method="get" class="ajax-update" data-reload="#ajax-codes" action="{{ route('account_security.index') }}">
                                @csrf
                                <button type="submit" data-text="Generating..." class="btn btn-s btn-primary mt-2 float-right">Regenerate Codes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Users with 2fa enabled --}}
@if(isset($users_with_2fa_enabled) && Qs::userIsHead())
@if($users_with_2fa_enabled->isNotEmpty())

<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Users with Google Two Factor Authentication enabled</h6>
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
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users_with_2fa_enabled as $user)
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
                            <td class="text-center">
                                {{--Deactivate--}}
                                <a id="{{ $user->id }}" onclick="confirmOperation(this.id)" href="javascript:;" class="btn btn-danger"> Deactivate</a>
                                <form method="post" id="item-confirm-operation-{{ $user->id }}" action="{{ route('2fa.null_secret_code', $user->id) }}" class="hidden">@csrf @method('patch')</form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endif
@endif

{{-- Browser Sessions --}}
<div class="card card-collapsed">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Browser Sessions & History</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0 alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    If necessary, you may logout your active sessions on other browsers and devices. Some of recent sessions are listed below;
                    however this list may not be exaustive.
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active">
                <div class="row">
                    <div class="col-md-7">
                        <ul class="list-unstyled">
                            @foreach ($browser_sessions as $bs)
                            <li><span class="material-symbols-rounded mr-2 mb-1">{{ $bs->device['desktop'] ? 'computer' : ($bs->device['tablet'] ? 'tablet' : ($bs->device['mobile'] ? 'smartphone' : 'devices')) }}</span>{{ $bs->device['platform'] }} - {{ $bs->device['browser'] }}</li>
                            <li><span class="material-symbols-rounded mr-2 mb-1">bring_your_own_ip</span>
                                {{ $bs->ip_address }},
                                @if($bs->is_current_device)
                                <strong class="text-green">this device</strong>
                                @else
                                last active {{ $bs->last_active }}
                                @endif
                            </li>
                            <br />
                            @endforeach

                            @if ($browser_sessions->isNotEmpty())
                            <form method="post" action="{{ route('account_security.logout_other_browser_sessions') }}">
                                @csrf
                                @method('DELETE')

                                <div class="form-group row">
                                    <div class="col-6">
                                        <input class="form-control form-control-sm" name="password" placeholder="Enter password" type="password" />
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-s btn-primary mt-1">Logout Other Sessions</button>
                            </form>
                            @endif
                        </ul>
                    </div>
                  
                    <div class="col-md-5">
                        @php
                        $login_history = auth()->user()->loginHistory()->first();
                        @endphp
                        <h6>Login History</h6>
                        <table class="table table-bordered table-respxonsive">
                            <tr>
                                <td class="font-weight-bold">Login Times</td>
                                <td>{{ $login_history->login_times}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Last Login</td>
                                <td>{{ Qs::fullDateTimeFormat($login_history->last_login) }}</td>
                            </tr>
                            @if ($login_history->last_reset)
                            <tr>
                                <td class="font-weight-bold">Last Reset</td>
                                <td>{{ Qs::fullDateTimeFormat($login_history->last_reset) }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
