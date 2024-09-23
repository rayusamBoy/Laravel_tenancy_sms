@extends('layouts.login_master')

@section('page_title', 'Two Factor Authentication')

@section('content')
<div class="page-content login-cover">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center w-100">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header text-info"><strong>{{ ('Two Factor Authentication') }}</strong></div>

                        <div class="card-body text-muted">
                            <p>{{ ('You have two factor authentication enabled. Please open Google authenticator app and enter your one-time password to complete your login.') }}</p>

                            <form method="POST" action="{{ route('2fa.authenticate') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="one_time_password" class="col-md-4 col-form-label text-md-right">{{ ('One Time Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="one_time_password" type="text" class="form-control @if($errors->any()) is-invalid @endif" name="one_time_password" placeholder="One Time Password" required autofocus>

                                        @if ($errors->any())
                                        <strong class="invalid-feedback" role="alert">
                                            <em>{{ implode('<br>', $errors->all()) }}</em>
                                        </strong>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary btn-sm mb-1">{{ ('Authenticate') }}</button>
                                        {{-- If there was too many request; show recover account option --}}
                                        @if(session('error_code') === 429)
                                        <a type="button" href="{{ route('account_security.account_recovery') }}" class="btn btn-info btn-sm mb-1"> Recover Account</a>
                                        @endif
                                        <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger btn-sm mb-1"> Cancel</button>
                                    </div>
                                </div>
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
